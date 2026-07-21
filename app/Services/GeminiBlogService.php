<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use App\Support\AdminSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiBlogService
{
    /** @var string|null Lưu lỗi gần nhất để debug */
    public ?string $lastError = null;

    /**
     * @param  array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     * @param  string|null  $brandSubject  Khi có: nội dung bài viết về brand/domain, không theo danh mục.
     */
    public function generateBlog(string $category, string $variant, array $extras = [], ?string $brandSubject = null): ?array
    {
        $preferredModel = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60));

        if (AdminSettings::geminiApiKeys() === []) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';
            return null;
        }

        $brandSubject = trim((string) $brandSubject);
        $isBrandFocused = $brandSubject !== '';
        $lengthInstruction = $this->resolveLengthInstruction($extras, '1,500-2,200 words');
        $editorPriorityBlock = $this->buildEditorPriorityBlock($extras, forEnglish: true);

        if ($isBrandFocused) {
            $brandSubject = Str::limit($brandSubject, 240, '');
            $subjectSafe = htmlspecialchars($brandSubject, ENT_QUOTES, 'UTF-8');

            $topicPrompt = match ($variant) {
                'best' => "Write a \"Best of\" / top picks style article about the brand/store **{$subjectSafe}** — what shoppers should know, strengths, and why it stands out.",
                'guide' => "Write a **buying guide** helping readers evaluate and shop at **{$subjectSafe}** (brand or store).",
                'comparison' => "Write a **comparison** article positioning **{$subjectSafe}** against typical alternatives or competitors in its space.",
                default => "Write a high-quality article about **{$subjectSafe}**.",
            };

            $focusBlock = <<<FOCUS
## Subject focus (MANDATORY)
- Primary subject: **{$subjectSafe}** (brand name or domain from the editor).
- The **entire article** must be about this brand/store/domain.
- **Do NOT** write a generic "{$category}" category article.
- Ignore category "{$category}" for content — it is metadata only. Do not mention the category unless absolutely unavoidable.
- Do not invent specific prices, coupon codes, or time-limited promotions unless provided in editor extras.
- If the subject looks like a domain, you may mention it as the likely official site. Add at most one link to `https://` + that host only if clearly a domain (`rel="nofollow noopener"`).
FOCUS;

            $prompt = <<<PROMPT
You are an expert English SEO copywriter for a deals and shopping blog.

{$editorPriorityBlock}{$this->buildEditorExtrasBlock($extras, forEnglish: true)}

## Article requirements
- Language: **English**, SEO-friendly, target length **{$lengthInstruction}**.
- Structure: exactly one `<h1>`, main sections with `<h2>`, optional `<h3>`.
- Helpful, neutral-to-positive tone — not overly salesy.
- Return a complete HTML fragment: `<h1>`, `<p>`, `<ul>`/`<ol>`, `<h2>`, `<h3>`. No `<html>`/`<body>`.
- Do NOT use Markdown. Do NOT wrap output in code fences like ```html ... ```.

{$focusBlock}

## Article type: {$variant}
{$topicPrompt}
PROMPT;
        } else {
            $topicPrompt = match ($variant) {
                'best' => "Viết bài kiểu \"Best {$category}\" (vd: Best {$category} deals, Best {$category} products).",
                'guide' => "Viết bài hướng dẫn chọn mua sản phẩm trong danh mục {$category} (Category buying guide).",
                'comparison' => "Viết bài so sánh (comparison) giữa các nhóm sản phẩm/phương án phổ biến trong danh mục {$category}.",
                default => "Viết bài blog chất lượng cao về chủ đề {$category}.",
            };

            $prompt = <<<PROMPT
Bạn là copywriter SEO tiếng Anh chuyên nghiệp.

{$this->buildEditorPriorityBlock($extras, forEnglish: false)}{$this->buildEditorExtrasBlock($extras)}

Yêu cầu bài viết:
- Ngôn ngữ: tiếng Anh, chuẩn SEO, độ dài {$lengthInstruction}.
- Cấu trúc: 1 thẻ <h1> duy nhất, các phần chính dùng <h2>, có thể thêm <h3>.
- Nội dung hữu ích, không quảng cáo thương hiệu cụ thể.
- Trả về HTML hoàn chỉnh: <h1>, <p>, <ul>/<ol>, <h2>, <h3>. Không bọc <html>/<body>.
- KHÔNG dùng Markdown, KHÔNG bọc nội dung trong code fence như ```html ... ```.

## Trọng tâm nội dung (BẮT BUỘC)
- Danh mục: **{$category}**
- Toàn bộ bài viết phải xoay quanh danh mục **{$category}** — tips, sản phẩm, xu hướng, hướng dẫn mua trong niche này.
- Không viết về một brand/cửa hàng cụ thể làm chủ đề chính.

Loại bài: {$variant}
{$topicPrompt}
PROMPT;
        }

        return $this->callGeminiWithKeyAndModelFallback($preferredModel, $prompt, $timeout);
    }

    /**
     * Bài ngắn về một brand/visit chỉ từ tên hoặc domain người dùng nhập — không dùng dữ liệu DB trên site.
     *
     * @return array{title: string, content: string, featured_image: ?string}|null
     */
    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    public function generateBrandSpotlightFromHint(string $hint, array $extras = []): ?array
    {
        $preferredModel = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60));

        if (AdminSettings::geminiApiKeys() === []) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';
            return null;
        }

        $hint = trim($hint);
        if ($hint === '') {
            $this->lastError = 'Thiếu tên brand hoặc domain.';
            return null;
        }

        $hint = Str::limit($hint, 240, '');
        $hintSafe = htmlspecialchars($hint, ENT_QUOTES, 'UTF-8');
        $lengthInstruction = $this->resolveLengthInstruction($extras, '450-700 words');
        $editorPriorityBlock = $this->buildEditorPriorityBlock($extras, forEnglish: true);
        $extrasBlock = $this->buildEditorExtrasBlock($extras, forEnglish: true);

        $prompt = <<<PROMPT
You are an expert English copywriter for a deals and shopping blog.

The editor typed this brand or store identifier (it may be a company name OR a domain like example.com). Use it only as the **subject label** — you have **no access** to our internal database, coupons, or verified facts about this merchant.

**Subject (verbatim from editor):** {$hintSafe}

{$editorPriorityBlock}{$extrasBlock}

## Your task
Write ONE **short** editorial-style article in **English** about this brand/visit as a general shopping subject.

## Length & tone
- Target **{$lengthInstruction}** (shorter than a full review unless the editor idea specifies otherwise; scannable).
- Helpful, neutral-to-positive, **not** salesy. Do **not** invent specific prices, coupon codes, percentages, or time-limited promotions.
- You may use **high-level, generic** industry knowledge only; if unsure, stay vague and recommend readers check the official site.
- If the subject looks like a **domain**, you may mention it as the likely official web presence. Add **at most one** link to `https://` + that host only if it is clearly a domain (use `rel="nofollow noopener"`). If it is only a brand name with no clear domain, **do not** invent URLs.

## HTML rules
- Return a **complete HTML fragment** only: one `<h1>`, several `<h2>`, `<p>`, optional `<ul>`. No `<html>` / `<body>`.
- Do NOT use Markdown. Do NOT wrap output in code fences like ```html ... ```.
- The `<h1>` should naturally include the brand/visit subject.

## Structure (suggested)
1. Brief intro: who/what readers might look for.
2. What shoppers typically care about for this kind of brand (generic).
3. Practical tips to evaluate deals safely (generic).
4. Short closing + soft disclaimer that details change and readers should verify on the official site.

Do not claim the brand partners with our site or that we verified offers.
PROMPT;

        $result = $this->callGeminiWithKeyAndModelFallback($preferredModel, $prompt, max(60, $timeout), [
            'maxOutputTokens' => 4096,
            'temperature' => 0.75,
        ]);

        if ($result !== null) {
            $result['featured_image'] = null;
        }

        return $result;
    }

    /**
     * Blog giới thiệu brand + coupon landing, có CTA và link affiliate.
     * Tên brand KHÔNG gắn link trong nội dung - link chỉ ở đầu và cuối bài.
     * Coupon codes được nhúng vào nội dung.
     *
     * @return array{title: string, content: string, featured_image: ?string}|null
     */
    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    public function generateBrandIntroBlog(Brand $brand, Campaign $campaign, string $categoryName, array $extras = []): ?array
    {
        $preferredModel = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = max(120, (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60)));

        if (AdminSettings::geminiApiKeys() === []) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';
            return null;
        }

        $brand->loadMissing('category');
        $campaign->loadMissing('couponItems');

        $campaignSlug = (string) $campaign->slug;
        $affiliateTrackingUrl = route('click.redirect', ['slug' => $campaignSlug], true);

        $brandName = $brand->name;
        $domain = $brand->domain ? trim((string) $brand->domain) : '';
        $shortDesc = $brand->short_description ? trim((string) $brand->short_description) : '';
        $campaignIntro = $campaign->intro ? trim(strip_tags((string) $campaign->intro)) : '';
        $campaignTitle = trim((string) $campaign->title);
        $benefits = $campaign->benefits;
        $benefitsText = is_array($benefits)
            ? implode('; ', array_filter(array_map('strval', $benefits)))
            : (string) $benefits;

        // Lấy tối đa 5 coupon codes để nhúng vào nội dung
        $couponCodes = $campaign->couponItems
            ->take(5)
            ->map(function ($c) {
                $parts = array_filter([
                    $c->offer ? "<strong>{$c->offer}</strong>" : null,
                    $c->code ? "Code: <code>{$c->code}</code>" : null,
                    $c->description ? Str::limit(strip_tags((string) $c->description), 100) : null,
                ]);
                return $parts ? implode(' — ', $parts) : null;
            })
            ->filter()
            ->values()
            ->toArray();

        $couponListHtml = '';
        if (!empty($couponCodes)) {
            $couponItems = [];
            foreach ($couponCodes as $code) {
                $couponItems[] = "<li>{$code}</li>";
            }
            $couponListHtml = "<h2>Available Coupons</h2>\n<ul>\n" . implode("\n", $couponItems) . "\n</ul>";
        }

        $extrasBlock = $this->buildEditorExtrasBlock(
            array_merge($extras, ['affiliate_url' => $affiliateTrackingUrl]),
            forEnglish: true,
            forcePromoSection: true,
        );
        $lengthInstruction = $this->resolveLengthInstruction($extras, '900-1,200 words');
        $editorPriorityBlock = $this->buildEditorPriorityBlock($extras, forEnglish: true);

        $prompt = <<<PROMPT
You are an expert English SEO copywriter for affiliate coupon sites.

{$editorPriorityBlock}{$extrasBlock}

Write ONE blog article introducing the store/brand below. Language: **English**. Target length **{$lengthInstruction}**. Tone: helpful, trustworthy, conversion-oriented but honest.

## Brand & campaign facts (use only as facts; do not invent unavailable data)
- Category niche: {$categoryName}
- Brand / store name: {$brandName}
- Domain (if any): {$domain}
- Short brand description: {$shortDesc}
- Campaign title: {$campaignTitle}
- Campaign intro: {$campaignIntro}
- Benefits / highlights: {$benefitsText}

## Required HTML output rules
- Return **complete HTML fragment** only: use `<h1>` once for the main title, then `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<strong>` as needed. **Do not** wrap in `<html>` or `<body>`.
- Do NOT use Markdown. Do NOT wrap output in code fences like ```html ... ```.
- **DO NOT link** the brand name "{$brandName}" anywhere in the article content (headings or paragraphs). Just write it as plain text.
- **ALL links** in the article must use this affiliate tracking URL only: {$affiliateTrackingUrl}
- **NEVER** link to coupon landing pages, `/visit/` URLs, or internal site pages for this campaign.

## Article structure
1. **Title (`<h1>`)**: Include brand name + main category keyword.
2. **Opening CTA (`<p>` at top)**: `<a href="{$affiliateTrackingUrl}" rel="nofollow sponsored noopener" target="_blank">Shop now at {$brandName}</a>` — put this link in the first paragraph.
3. **Brief intro (`<h2>`)**: What the brand is, main benefit.
4. **Products/Services (`<h2>`)**: Main offerings.
5. **Pros and Cons (`<h2>`)**: Two subsections or bullet lists.
6. **Available Coupons**: {$couponListHtml}
7. **Closing CTA (`<p>` at bottom)**: `<a href="{$affiliateTrackingUrl}" rel="nofollow sponsored noopener" target="_blank">Shop now at {$brandName}</a>` — put this affiliate link in the last paragraph.

Do not claim discounts or codes that are not in the facts.
PROMPT;

        $result = $this->callGeminiWithKeyAndModelFallback($preferredModel, $prompt, $timeout, [
            'maxOutputTokens' => 6144,
            'temperature' => 0.8,
        ]);

        // Không gán ảnh brand: để featured_image null → Blog tự chọn ngẫu nhiên ảnh theo danh mục khi lưu.
        if ($result !== null) {
            $result['content'] = $this->normalizeStoreBlogAffiliateLinks(
                (string) $result['content'],
                $campaignSlug,
                $affiliateTrackingUrl,
            );
            $result['featured_image'] = null;
        }

        return $result;
    }

    /**
     * Đảm bảo bài giới thiệu cửa hàng không còn link trang coupon (/visit/, /store/) — chỉ dùng link affiliate (/out/).
     */
    protected function normalizeStoreBlogAffiliateLinks(string $html, string $campaignSlug, string $affiliateTrackingUrl): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $slug = preg_quote($campaignSlug, '#');
        $replacements = array_unique(array_filter([
            route('landing.show', ['slug' => $campaignSlug], true),
            url('/store/'.$campaignSlug),
            url('/visit/'.$campaignSlug),
            '/store/'.$campaignSlug,
            '/visit/'.$campaignSlug,
        ]));

        usort($replacements, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($replacements as $from) {
            $html = str_replace($from, $affiliateTrackingUrl, $html);
        }

        $html = preg_replace(
            '~https?://[^"\'\s<>]+/store/'.$slug.'(?:[/?#"\s]|$)~i',
            $affiliateTrackingUrl,
            $html
        ) ?? $html;

        $html = preg_replace(
            '~https?://[^"\'\s<>]+/visit/'.$slug.'(?:[/?#"\s]|$)~i',
            $affiliateTrackingUrl,
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    protected function resolveLengthInstruction(array $extras, string $defaultEnglish): string
    {
        $idea = trim((string) ($extras['idea'] ?? ''));
        $fromIdea = $this->extractWordCountFromIdea($idea);

        return $fromIdea ?? $defaultEnglish;
    }

    /**
     * Trích số từ từ ý tưởng người dùng (VD: "800 từ", "1500 words", "800-1000 words").
     */
    protected function extractWordCountFromIdea(string $idea): ?string
    {
        $idea = trim($idea);
        if ($idea === '') {
            return null;
        }

        if (preg_match('/(?:khoảng|about|around|roughly|~|tầm|dài)\s*(\d{2,5})\s*[-–—]\s*(\d{2,5})\s*(?:words?|từ|tu\b)/iu', $idea, $matches)) {
            return $this->formatWordCountRange((int) $matches[1], (int) $matches[2]);
        }

        if (preg_match('/(\d{2,5})\s*[-–—]\s*(\d{2,5})\s*(?:words?|từ|tu\b)/iu', $idea, $matches)) {
            return $this->formatWordCountRange((int) $matches[1], (int) $matches[2]);
        }

        if (preg_match('/(?:khoảng|about|around|roughly|~|tầm|viết|write|length|độ dài|target|dài)\s*(\d{2,5})\s*(?:words?|từ|tu\b)/iu', $idea, $matches)) {
            return $this->formatWordCountSingle((int) $matches[1]);
        }

        if (preg_match('/(\d{2,5})\s*(?:words?|từ|tu\b)/iu', $idea, $matches)) {
            return $this->formatWordCountSingle((int) $matches[1]);
        }

        return null;
    }

    protected function formatWordCountSingle(int $count): string
    {
        $count = max(100, min(10000, $count));

        return number_format($count).' words';
    }

    protected function formatWordCountRange(int $min, int $max): string
    {
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        $min = max(100, min(10000, $min));
        $max = max($min, min(10000, $max));

        return number_format($min).'-'.number_format($max).' words';
    }

    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    protected function buildEditorPriorityBlock(array $extras, bool $forEnglish = false): string
    {
        $idea = trim((string) ($extras['idea'] ?? ''));
        if ($idea === '') {
            return '';
        }

        $wordCount = $this->extractWordCountFromIdea($idea);

        if ($forEnglish) {
            $lines = [
                '## Editor idea — HIGHEST PRIORITY',
                '- The editor idea below overrides default article type, topic focus, structure, tone, and length when they conflict.',
                '- Follow the editor idea first; only use defaults for details the idea does not mention.',
            ];
            if ($wordCount !== null) {
                $lines[] = "- Word count detected in the editor idea: **{$wordCount}** — this overrides any default length.";
            }

            return implode("\n", $lines)."\n\n";
        }

        $lines = [
            '## Ý tưởng người nhập — ƯU TIÊN CAO NHẤT',
            '- Ý tưởng bên dưới ghi đè loại bài, chủ đề, cấu trúc, giọng văn và độ dài mặc định nếu mâu thuẫn.',
            '- Bám sát ý tưởng trước; chỉ dùng mặc định cho phần người dùng không nhắc tới.',
        ];
        if ($wordCount !== null) {
            $lines[] = "- Phát hiện số từ trong ý tưởng: **{$wordCount}** — ưu tiên hơn độ dài mặc định.";
        }

        return implode("\n", $lines)."\n\n";
    }

    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    protected function buildEditorExtrasBlock(array $extras, bool $forEnglish = false, bool $forcePromoSection = false): string
    {
        $idea = trim((string) ($extras['idea'] ?? ''));
        $affiliateUrl = trim((string) ($extras['affiliate_url'] ?? ''));
        $couponCode = trim((string) ($extras['coupon_code'] ?? ''));

        // Hard limits to keep prompt stable.
        $idea = Str::limit($idea, 1800, '');
        $affiliateUrl = Str::limit($affiliateUrl, 1800, '');
        $couponCode = Str::limit($couponCode, 120, '');

        $hasAny = ($idea !== '') || ($affiliateUrl !== '') || ($couponCode !== '');
        if (! $hasAny && ! $forcePromoSection) {
            return '';
        }

        $ideaSafe = htmlspecialchars($idea, ENT_QUOTES, 'UTF-8');
        $affiliateSafe = htmlspecialchars($affiliateUrl, ENT_QUOTES, 'UTF-8');
        $couponSafe = htmlspecialchars($couponCode, ENT_QUOTES, 'UTF-8');

        if ($forEnglish) {
            $lines = [];
            $lines[] = "## Editor requirements (follow these as hard constraints, do not ignore)";
            if ($ideaSafe !== '') {
                $lines[] = "- Core article idea / outline (**PRIMARY SOURCE** — topic, angle, structure, tone, and any length hint): {$ideaSafe}";
                $lines[] = '- Treat every detail in the idea as mandatory unless it contradicts HTML output rules or factual constraints above.';
            }
            if ($affiliateSafe !== '') {
                $lines[] = "- Affiliate link to include (use exactly; do not modify): {$affiliateSafe}";
            }
            if ($couponSafe !== '') {
                $lines[] = "- Coupon code to show exactly as typed (do not invent conditions or extra discounts): {$couponSafe}";
            }
            $lines[] = "- The article must stay tightly aligned with the editor idea (section flow, focus points, requested style). Only adjust for grammar and global coherence.";
            $lines[] = "- If an affiliate link is provided, include ONE clear CTA link near the end using `<a href=\"...\" rel=\"nofollow sponsored noopener\" target=\"_blank\">`.";
            $lines[] = "- If a coupon code is provided, include a short section titled `<h2>Coupon code</h2>` containing the code in HTML (e.g. `<p><strong>Code:</strong> CODE</p>`).";
            $lines[] = "- Do not invent extra codes, discount percentages, or time-limited claims beyond what is explicitly provided above.";

            return implode("\n", $lines) . "\n";
        }

        $lines = [];
        $lines[] = "Yêu cầu bổ sung từ người nhập (bắt buộc, coi như ràng buộc chính):";
        if ($ideaSafe !== '') {
            $lines[] = "- Ý tưởng / outline (**NGUỒN CHÍNH** — chủ đề, góc viết, cấu trúc, giọng văn và gợi ý độ dài): {$ideaSafe}";
            $lines[] = '- Mọi chi tiết trong ý tưởng đều bắt buộc trừ khi mâu thuẫn với quy tắc HTML hoặc ràng buộc thực tế phía trên.';
        }
        if ($affiliateSafe !== '') {
            $lines[] = "- Link affiliate cần chèn (dùng đúng link): {$affiliateSafe}";
        }
        if ($couponSafe !== '') {
            $lines[] = "- Mã coupon cần hiển thị (giữ nguyên, không bịa thêm điều kiện/discount): {$couponSafe}";
        }
        $lines[] = "- Nội dung bài viết phải bám sát ý tưởng người nhập (chỉ được chỉnh nhẹ để mạch lạc hơn, không đổi chủ đề).";
        $lines[] = "- Nếu có link affiliate: chèn 1 CTA rõ ràng gần cuối bài với rel=\"nofollow sponsored noopener\" và target=\"_blank\".";
        $lines[] = "- Nếu có mã coupon: tạo 1 mục `<h2>` riêng để hiển thị mã (không bịa thêm điều kiện).";

        return implode("\n", $lines) . "\n";
    }

    /**
     * @param  array<string, mixed>  $generationConfigOverrides
     */
    protected function callGemini(string $apiKey, string $model, string $prompt, int $timeout, array $generationConfigOverrides = []): ?array
    {
        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            $model
        );

        $generationConfig = array_merge([
            'temperature' => 0.9,
            'topP' => 0.8,
            'topK' => 40,
        ], $generationConfigOverrides);

        $payload = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => $generationConfig,
        ];

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->asJson()
                ->post($endpoint . '?key=' . urlencode($apiKey), $payload);
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::warning('GeminiBlogService HTTP error', ['error' => $e->getMessage(), 'model' => $model]);
            return null;
        }

        if (! $response->successful()) {
            $body = $response->json();
            $msg = data_get($body, 'error.message', $response->body());
            $this->lastError = "HTTP {$response->status()}: {$msg}";
            Log::warning('GeminiBlogService API error', ['status' => $response->status(), 'body' => $body]);
            return null;
        }

        $data = $response->json();

        $blockReason = data_get($data, 'candidates.0.finishReason');
        if (in_array($blockReason, ['SAFETY', 'RECITATION', 'OTHER'], true)) {
            $this->lastError = "Response blocked: {$blockReason}";
            return null;
        }

        $parts = data_get($data, 'candidates.0.content.parts', []);
        if (empty($parts)) {
            $this->lastError = 'Response không có parts.';
            return null;
        }

        $text = '';
        foreach ($parts as $part) {
            if (isset($part['text']) && is_string($part['text'])) {
                $text .= $part['text'];
            }
        }

        $text = $this->normalizeGeneratedHtml($text);

        if (trim($text) === '') {
            $this->lastError = 'Response không có nội dung text.';
            return null;
        }

        $title = null;
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $text, $m)) {
            $title = trim(strip_tags($m[1]));
        }
        if (! $title) {
            $firstLine = strtok($text, "\n");
            $title = Str::limit(trim(strip_tags((string) $firstLine)), 120, '');
        }

        return [
            'title' => $title,
            'content' => $text,
            'featured_image' => null,
        ];
    }

    /**
     * Thử lần lượt từng API key; mỗi key lại fallback qua các model.
     *
     * @param  array<string, mixed>  $generationConfigOverrides
     */
    protected function callGeminiWithKeyAndModelFallback(
        string $preferredModel,
        string $prompt,
        int $timeout,
        array $generationConfigOverrides = []
    ): ?array {
        $apiKeys = AdminSettings::geminiApiKeys();
        if ($apiKeys === []) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';

            return null;
        }

        $lastError = null;

        foreach ($apiKeys as $index => $apiKey) {
            $result = $this->callGeminiWithFallback(
                $apiKey,
                $preferredModel,
                $prompt,
                $timeout,
                $generationConfigOverrides
            );

            if ($result !== null) {
                return $result;
            }

            $lastError = $this->lastError;
            Log::warning('GeminiBlogService API key failed, trying next', [
                'key_index' => $index + 1,
                'keys_total' => count($apiKeys),
                'error' => $this->lastError,
            ]);
        }

        $this->lastError = $lastError ?? 'Không thể tạo bài viết, vui lòng thử lại sau.';

        return null;
    }

    /**
     * Gọi Gemini với fallback: nếu model ưu tiên lỗi → thử lần lượt các model khác.
     * Nếu tất cả đều lỗi → trả lỗi cuối cùng.
     *
     * @param  array<string, mixed>  $generationConfigOverrides
     */
    protected function callGeminiWithFallback(string $apiKey, string $preferredModel, string $prompt, int $timeout, array $generationConfigOverrides = []): ?array
    {
        $supportedModels = config('gemini.supported_models', []);

        $modelOrder = array_values(array_unique([
            $preferredModel,
            ...$supportedModels,
        ]));

        $lastError = null;

        foreach ($modelOrder as $model) {
            $result = $this->callGemini($apiKey, (string) $model, $prompt, $timeout, $generationConfigOverrides);

            if ($result !== null) {
                return $result;
            }

            $lastError = $this->lastError;
            Log::debug('GeminiBlogService model failed, trying next', [
                'model' => $model,
                'error' => $this->lastError,
            ]);
        }

        $this->lastError = $lastError ?? 'Không thể tạo bài viết, vui lòng thử lại sau.';

        return null;
    }

    /**
     * Gemini đôi khi trả HTML dưới dạng Markdown code fence (```html ... ```).
     * Normalization này đảm bảo editor nhận đúng HTML fragment.
     */
    private function normalizeGeneratedHtml(string $text): string
    {
        $t = trim($text);

        // Full fenced block
        if (preg_match('/^\s*```(?:html)?\s*\R([\s\S]*?)\R```\s*$/i', $t, $m)) {
            return trim((string) $m[1]);
        }

        // Leading fence only
        $t = preg_replace('/^\s*```(?:html)?\s*\R?/i', '', $t) ?? $t;
        // Trailing fence only
        $t = preg_replace('/\R?```\s*$/', '', $t) ?? $t;

        return trim($t);
    }
}
