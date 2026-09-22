<?php

namespace App\Services;

use App\Support\AdminSettings;
use App\Support\RasterWebpConverter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogApifyImageService
{
    public const DEFAULT_ACTOR_ID = 'IOrPh0bOfzJiGxsvk';

    /** ~800 KB — bỏ qua ảnh quá nặng. */
    public const MAX_FILE_BYTES = 819_200;

    public const MAX_CANDIDATES = 10;

    public const INLINE_IMAGE_COUNT = 2;

    /**
     * @param  array{title: string, content: string, featured_image?: ?string}  $result
     * @return array{title: string, content: string, featured_image: ?string, images?: list<string>}
     */
    public function enrich(array $result, string $searchQuery, ?string $categoryLabel = null): array
    {
        $searchQuery = trim($searchQuery);
        if ($searchQuery === '') {
            return $result;
        }

        $tokens = AdminSettings::apifyTokens();
        if ($tokens === []) {
            return $result;
        }

        $lastError = null;
        foreach ($tokens as $token) {
            try {
                $candidates = $this->fetchImageCandidates($token, $searchQuery);
                if ($candidates === []) {
                    continue;
                }

                $stored = $this->downloadBestImages($candidates);
                if ($stored === []) {
                    continue;
                }

                $featuredPath = $stored[0]['path'];
                $inlinePaths = array_slice(array_column($stored, 'public_url'), 1, self::INLINE_IMAGE_COUNT);

                $result['featured_image'] = $featuredPath;
                $result['content'] = $this->insertImagesEvenly(
                    (string) ($result['content'] ?? ''),
                    $inlinePaths
                );

                if (count($stored) > 1) {
                    $result['images'] = array_slice(array_column($stored, 'path'), 1);
                }

                return $result;
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
                Log::debug('BlogApifyImageService token failed, trying next', [
                    'query' => $searchQuery,
                    'error' => $lastError,
                ]);
            }
        }

        if ($lastError !== null) {
            Log::warning('BlogApifyImageService failed (all tokens)', [
                'query' => $searchQuery,
                'category' => $categoryLabel,
                'error' => $lastError,
            ]);
        }

        return $result;
    }

    /**
     * @return list<array{imageUrl: string, score: int}>
     */
    public function fetchImageCandidates(string $token, string $query): array
    {
        $actorId = (string) AdminSettings::get('apify_blog_image_actor_id', self::DEFAULT_ACTOR_ID);
        $runInput = [
            'query' => $query,
            'country' => 'us',
            'language' => 'en',
            'num' => (string) self::MAX_CANDIDATES,
            'max_pages' => 1,
            'date_range' => 'anytime',
        ];

        $runResponse = Http::timeout(120)
            ->acceptJson()
            ->asJson()
            ->post(
                'https://api.apify.com/v2/acts/'.rawurlencode($actorId).'/runs?token='.urlencode($token),
                $runInput
            );

        if (! $runResponse->successful()) {
            throw new \RuntimeException('Apify run failed: HTTP '.$runResponse->status());
        }

        $runData = $runResponse->json('data');
        if (! is_array($runData)) {
            throw new \RuntimeException('Apify run response missing data');
        }

        $runId = (string) ($runData['id'] ?? '');
        if ($runId === '') {
            throw new \RuntimeException('Apify run missing id');
        }

        $datasetId = $this->waitForRunDataset($token, $runId, $runData);
        if ($datasetId === null || $datasetId === '') {
            throw new \RuntimeException('Apify run did not produce dataset');
        }

        $itemsResponse = Http::timeout(60)
            ->acceptJson()
            ->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
                'token' => $token,
                'format' => 'json',
                'clean' => 'true',
                'limit' => self::MAX_CANDIDATES,
            ]);

        if (! $itemsResponse->successful()) {
            throw new \RuntimeException('Apify dataset fetch failed');
        }

        $items = $itemsResponse->json();
        if (! is_array($items)) {
            return [];
        }

        $candidates = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $url = trim((string) ($item['imageUrl'] ?? ''));
            if ($url === '' || $this->isLowQualityUrl($url)) {
                continue;
            }

            $score = $this->scoreImageCandidate($item, $url);
            if ($score < 0) {
                continue;
            }

            $candidates[] = ['imageUrl' => $url, 'score' => $score];
        }

        usort($candidates, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        $unique = [];
        $seen = [];
        foreach ($candidates as $candidate) {
            $hostPath = parse_url($candidate['imageUrl'], PHP_URL_PATH) ?: $candidate['imageUrl'];
            if (isset($seen[$hostPath])) {
                continue;
            }
            $seen[$hostPath] = true;
            $unique[] = $candidate;
        }

        return $unique;
    }

    /**
     * @param  list<array{imageUrl: string, score: int}>  $candidates
     * @return list<array{path: string, public_url: string}>
     */
    public function downloadBestImages(array $candidates): array
    {
        $needed = 1 + self::INLINE_IMAGE_COUNT;
        $stored = [];

        foreach ($candidates as $candidate) {
            if (count($stored) >= $needed) {
                break;
            }

            $saved = $this->downloadToPublicDisk($candidate['imageUrl']);
            if ($saved === null) {
                continue;
            }

            $stored[] = $saved;
        }

        return $stored;
    }

    /**
     * @return array{path: string, public_url: string}|null
     */
    public function downloadToPublicDisk(string $url): ?array
    {
        try {
            $response = Http::timeout(45)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; BlogBot/1.0)'])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            $body = $response->body();
            $size = strlen($body);
            if ($size === 0) {
                return null;
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            $ext = $this->extensionFromContentType($contentType, $url);
            if ($ext === null) {
                return null;
            }

            $webpBody = RasterWebpConverter::convertBinary($body);
            if ($webpBody !== null) {
                $body = $webpBody;
                $ext = 'webp';
            } elseif (RasterWebpConverter::isSupported()) {
                Log::debug('BlogApifyImageService webp conversion failed', ['url' => $url]);

                return null;
            } elseif ($size > self::MAX_FILE_BYTES) {
                return null;
            }

            if (strlen($body) > self::MAX_FILE_BYTES) {
                return null;
            }

            $filename = Str::uuid()->toString().'.'.$ext;
            $path = 'blogs/apify/'.$filename;

            Storage::disk('public')->put($path, $body);

            return [
                'path' => $path,
                'public_url' => Storage::disk('public')->url($path),
            ];
        } catch (\Throwable $e) {
            Log::debug('BlogApifyImageService download skipped', ['url' => $url, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
    /**
     * Tách ảnh inline đã có và phân bố lại đều giữa các đoạn văn.
     */
    public function redistributeContentImages(string $html): string
    {
        if (! preg_match_all('/<p\b[^>]*>\s*<img\b[^>]*\bsrc=(["\'])([^"\']+)\1/is', $html, $matches)) {
            return $html;
        }

        $urls = array_values(array_unique($matches[2]));
        if ($urls === []) {
            return $html;
        }

        return $this->insertImagesEvenly($html, $urls);
    }

    /**
     * Chèn ảnh xen kẽ đều giữa các đoạn văn (giữ nguyên heading/ul/cấu trúc khác).
     *
     * @param  list<string>  $imagePublicUrls
     */
    public function insertImagesEvenly(string $html, array $imagePublicUrls): string
    {
        $imagePublicUrls = array_values(array_filter(array_map('trim', $imagePublicUrls)));
        if ($imagePublicUrls === []) {
            return $html;
        }

        // Gỡ ảnh Apify cũ (chỉ <p> chứa <img>) để tránh dồn đầu bài khi enrich lại.
        $html = preg_replace('/<p\b[^>]*>\s*<img\b[^>]*>\s*<\/p>\s*/is', '', $html) ?? $html;

        $html = $this->normalizeLooseTextParagraphs($html);

        if (! preg_match_all('/<p\b[^>]*>.*?<\/p>/is', $html, $matches, PREG_OFFSET_CAPTURE)) {
            $extra = '';
            foreach ($imagePublicUrls as $url) {
                $extra .= $this->imageParagraph($url);
            }

            return rtrim($html).$extra;
        }

        /** @var list<array{0: string, 1: int}> $contentParas */
        $contentParas = [];
        foreach ($matches[0] as $match) {
            if ($this->isInsertableContentParagraph($match[0])) {
                $contentParas[] = $match;
            }
        }

        if ($contentParas === []) {
            $extra = '';
            foreach ($imagePublicUrls as $url) {
                $extra .= $this->imageParagraph($url);
            }

            return rtrim($html).$extra;
        }

        $pCount = count($contentParas);
        $imgCount = count($imagePublicUrls);

        /** @var array<int, list<string>> $insertAfter */
        $insertAfter = [];
        for ($i = 0; $i < $imgCount; $i++) {
            // Phân bố đều: ảnh thứ k sau đoạn gần vị trí k/(n+1).
            $paraIndex = max(0, (int) round(($i + 1) * $pCount / ($imgCount + 1)) - 1);
            $paraIndex = min($pCount - 1, $paraIndex);
            while (isset($insertAfter[$paraIndex]) && $paraIndex < $pCount - 1) {
                $paraIndex++;
            }
            $insertAfter[$paraIndex][] = $imagePublicUrls[$i];
        }

        // Chèn từ cuối → đầu để offset không lệch.
        krsort($insertAfter);
        foreach ($insertAfter as $paraIndex => $urls) {
            $target = $contentParas[$paraIndex];
            $endPos = $target[1] + strlen($target[0]);
            $chunk = '';
            foreach ($urls as $url) {
                $chunk .= $this->imageParagraph($url);
            }
            $html = substr($html, 0, $endPos)."\n".$chunk.substr($html, $endPos);
        }

        return $html;
    }

    protected function isInsertableContentParagraph(string $pHtml): bool
    {
        if (preg_match('/<img\b/i', $pHtml)) {
            return false;
        }

        if (preg_match('/blog-aff-cta|blog-coupon-code/i', $pHtml)) {
            return false;
        }

        if (preg_match('/<strong>\s*Code:\s*<\/strong>/i', $pHtml)) {
            return false;
        }

        $text = trim(preg_replace('/\s+/u', ' ', strip_tags($pHtml)) ?? '');
        if ($text === '') {
            return false;
        }

        if (preg_match('/^(?:Shop now(?: at .*)?|Visit the store|Discover .+|Get the latest offers from the store\.?)$/iu', $text)) {
            return false;
        }

        return true;
    }

    /**
     * Bọc text thuần (AI hay trả sau heading) thành <p> để có điểm chèn ảnh giữa bài.
     */
    protected function normalizeLooseTextParagraphs(string $html): string
    {
        $html = preg_replace_callback(
            '/(<\/h[1-6]>)\s*(.+?)(?=\s*<(?:h[1-6]|p|ul|ol|div|figure|blockquote|table|hr)\b|\z)/is',
            function (array $m): string {
                return $m[1].$this->wrapPlainTextBlocks($m[2]);
            },
            $html
        ) ?? $html;

        $html = preg_replace_callback(
            '/\A\s*((?:(?!<(?:h[1-6]|p|ul|ol|div)\b).)+?)(?=\s*<(?:h[1-6]|p|ul|ol|div)\b)/is',
            function (array $m): string {
                return $this->wrapPlainTextBlocks($m[1]);
            },
            $html
        ) ?? $html;

        return $html;
    }

    protected function wrapPlainTextBlocks(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return '';
        }

        if (preg_match('/^\s*<p\b/i', $raw)) {
            return "\n\n".$raw;
        }

        $out = '';
        foreach (preg_split('/\n\s*\n+/u', $raw) as $para) {
            $para = trim($para);
            if ($para === '') {
                continue;
            }
            if (preg_match('/^<[a-z][\s>]/i', $para)) {
                $out .= "\n\n".$para;
            } else {
                $out .= "\n\n<p>".$para.'</p>';
            }
        }

        return $out;
    }

    protected function imageParagraph(string $publicUrl): string
    {
        $escaped = htmlspecialchars($publicUrl, ENT_QUOTES, 'UTF-8');

        return '<p><img src="'.$escaped.'" alt="" loading="lazy" decoding="async" style="max-width:100%;height:auto;border-radius:8px;"></p>';
    }

    /**
     * @param  array<string, mixed>  $item
     */
    protected function scoreImageCandidate(array $item, string $imageUrl): int
    {
        if ($this->isLowQualityUrl($imageUrl)) {
            return -1;
        }

        $score = 10;
        $googleUrl = (string) ($item['googleUrl'] ?? '');

        if (preg_match('/[?&]w=(\d+)/i', $googleUrl.'&'.$imageUrl, $m)) {
            $width = (int) $m[1];
            if ($width >= 800 && $width <= 1600) {
                $score += 60;
            } elseif ($width >= 500) {
                $score += 40;
            } elseif ($width >= 300) {
                $score += 20;
            } elseif ($width < 200) {
                return -1;
            }
        }

        if (preg_match('/\.(webp|jpe?g)(\?|$)/i', $imageUrl)) {
            $score += 15;
        } elseif (preg_match('/\.png(\?|$)/i', $imageUrl)) {
            $score += 5;
        }

        if (preg_match('/width=(\d+)/i', $imageUrl, $m)) {
            $width = (int) $m[1];
            if ($width >= 600 && $width <= 1400) {
                $score += 25;
            }
        }

        return $score;
    }

    protected function isLowQualityUrl(string $url): bool
    {
        $lower = strtolower($url);

        return str_contains($lower, 'encrypted-tbn')
            || str_contains($lower, 'gstatic.com/images?q=tbn')
            || str_contains($lower, 'favicon')
            || str_contains($lower, 'logo.svg');
    }

    protected function extensionFromContentType(string $contentType, string $url): ?string
    {
        return match (true) {
            str_contains($contentType, 'image/webp') => 'webp',
            str_contains($contentType, 'image/jpeg') => 'jpg',
            str_contains($contentType, 'image/png') => 'png',
            str_contains($contentType, 'image/gif') => 'gif',
            preg_match('/\.(webp)(?:\?|$)/i', $url) === 1 => 'webp',
            preg_match('/\.(jpe?g)(?:\?|$)/i', $url) === 1 => 'jpg',
            preg_match('/\.(png)(?:\?|$)/i', $url) === 1 => 'png',
            preg_match('/\.(gif)(?:\?|$)/i', $url) === 1 => 'gif',
            default => null,
        };
    }

    /**
     * @param  array<string, mixed>  $initialRunData
     */
    protected function waitForRunDataset(string $token, string $runId, array $initialRunData): ?string
    {
        $data = $initialRunData;
        $status = (string) ($data['status'] ?? 'RUNNING');
        $startedAt = microtime(true);
        $timeoutSeconds = 90;

        while (in_array($status, ['READY', 'RUNNING', 'STARTING', 'SCHEDULED'], true)) {
            if (microtime(true) - $startedAt > $timeoutSeconds) {
                throw new \RuntimeException('Apify run timeout');
            }

            usleep(2_500_000);

            $check = Http::timeout(30)
                ->acceptJson()
                ->get("https://api.apify.com/v2/actor-runs/{$runId}", ['token' => $token]);

            if (! $check->successful()) {
                continue;
            }

            $data = $check->json('data') ?? $data;
            $status = (string) ($data['status'] ?? $status);
        }

        if ($status !== 'SUCCEEDED') {
            throw new \RuntimeException('Apify run ended with status: '.$status);
        }

        return isset($data['defaultDatasetId']) ? (string) $data['defaultDatasetId'] : null;
    }
}
