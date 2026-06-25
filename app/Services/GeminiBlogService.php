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
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    public function generateBlog(string $category, string $variant, array $extras = []): ?array
    {
        $apiKey = AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'));
        $model = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60));

        if (empty($apiKey)) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';
            return null;
        }

        $topicPrompt = match ($variant) {
            'best' => "Viết bài kiểu \"Best {$category}\" (vd: Best {$category} deals, Best {$category} products).",
            'guide' => "Viết bài hướng dẫn chọn mua sản phẩm trong danh mục {$category} (Category buying guide).",
            'comparison' => "Viết bài so sánh (comparison) giữa các nhóm sản phẩm/phương án phổ biến trong danh mục {$category}.",
            default => "Viết bài blog chất lượng cao về chủ đề {$category}.",
        };

        $prompt = <<<PROMPT
Bạn là copywriter SEO tiếng Anh chuyên nghiệp.

Yêu cầu bài viết:
- Ngôn ngữ: tiếng Anh, chuẩn SEO, độ dài 1,500-2,200 từ.
- Cấu trúc: 1 thẻ <h1> duy nhất, các phần chính dùng <h2>, có thể thêm <h3>.
- Nội dung hữu ích, không quảng cáo thương hiệu cụ thể.
- Trả về HTML hoàn chỉnh: <h1>, <p>, <ul>/<ol>, <h2>, <h3>. Không bọc <html>/<body>.
- KHÔNG dùng Markdown, KHÔNG bọc nội dung trong code fence như ```html ... ```.

{$this->buildEditorExtrasBlock($extras)}

Danh mục: {$category}
Loại bài: {$variant}
{$topicPrompt}
PROMPT;

        return $this->callGemini($apiKey, $model, $prompt, $timeout);
    }

    /**
     * Bài ngắn về một brand/store chỉ từ tên hoặc domain người dùng nhập — không dùng dữ liệu DB trên site.
     *
     * @return array{title: string, content: string, featured_image: ?string}|null
     */
    /**
     * @param array{idea?:string, affiliate_url?:string, coupon_code?:string} $extras
     */
    public function generateBrandSpotlightFromHint(string $hint, array $extras = []): ?array
    {
        $apiKey = AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'));
        $model = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60));

        if (empty($apiKey)) {
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
        $extrasBlock = $this->buildEditorExtrasBlock($extras, forEnglish: true);

        $prompt = <<<PROMPT
You are an expert English copywriter for a deals and shopping blog.

The editor typed this brand or store identifier (it may be a company name OR a domain like example.com). Use it only as the **subject label** — you have **no access** to our internal database, coupons, or verified facts about this merchant.

**Subject (verbatim from editor):** {$hintSafe}

{$extrasBlock}

## Your task
Write ONE **short** editorial-style article in **English** about this brand/store as a general shopping subject.

## Length & tone
- Target **450-700 words** (shorter than a full review; scannable).
- Helpful, neutral-to-positive, **not** salesy. Do **not** invent specific prices, coupon codes, percentages, or time-limited promotions.
- You may use **high-level, generic** industry knowledge only; if unsure, stay vague and recommend readers check the official site.
- If the subject looks like a **domain**, you may mention it as the likely official web presence. Add **at most one** link to `https://` + that host only if it is clearly a domain (use `rel="nofollow noopener"`). If it is only a brand name with no clear domain, **do not** invent URLs.

## HTML rules
- Return a **complete HTML fragment** only: one `<h1>`, several `<h2>`, `<p>`, optional `<ul>`. No `<html>` / `<body>`.
- Do NOT use Markdown. Do NOT wrap output in code fences like ```html ... ```.
- The `<h1>` should naturally include the brand/store subject.

## Structure (suggested)
1. Brief intro: who/what readers might look for.
2. What shoppers typically care about for this kind of brand (generic).
3. Practical tips to evaluate deals safely (generic).
4. Short closing + soft disclaimer that details change and readers should verify on the official site.

Do not claim the brand partners with our site or that we verified offers.
PROMPT;

        $result = $this->callGemini($apiKey, $model, $prompt, max(60, $timeout), [
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
        $apiKey = AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'));
        $model = (string) AdminSettings::get('gemini_model', config('gemini.model', 'gemini-1.5-flash-latest'));
        $timeout = max(120, (int) AdminSettings::get('gemini_timeout', config('gemini.timeout', 60)));

        if (empty($apiKey)) {
            $this->lastError = 'GEMINI_API_KEY chưa được cấu hình.';
            return null;
        }

        $brand->loadMissing('category');
        $campaign->loadMissing('couponItems');

        $campaignSlug = (string) $campaign->slug;
        $affiliateTrackingUrl = route('click.redirect', ['slug' => $campaignSlug], true);
        $couponLandingUrl = route('landing.show', ['slug' => $campaignSlug], true);

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

        $extrasBlock = $this->buildEditorExtrasBlock($extras, forEnglish: true, forcePromoSection: true);

        $prompt = <<<PROMPT
You are an expert English SEO copywriter for affiliate coupon sites.

Write ONE blog article introducing the store/brand below. Language: **English**. Target length **900-1,200 words**. Tone: helpful, trustworthy, conversion-oriented but honest.

## Brand & campaign facts (use only as facts; do not invent unavailable data)
- Category niche: {$categoryName}
- Brand / store name: {$brandName}
- Domain (if any): {$domain}
- Short brand description: {$shortDesc}
- Campaign title: {$campaignTitle}
- Campaign intro: {$campaignIntro}
- Benefits / highlights: {$benefitsText}

{$extrasBlock}

## Required HTML output rules
- Return **complete HTML fragment** only: use `<h1>` once for the main title, then `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<strong>` as needed. **Do not** wrap in `<html>` or `<body>`.
- Do NOT use Markdown. Do NOT wrap output in code fences like ```html ... ```.
- **DO NOT link** the brand name "{$brandName}" anywhere in the article content (headings or paragraphs). Just write it as plain text.
- **ONLY link** when using these CTA buttons at the BEGINNING and END of the article.

## Article structure
1. **Title (`<h1>`)**: Include brand name + main category keyword.
2. **Opening CTA (`<p>` at top)**: `<a href="{$affiliateTrackingUrl}" rel="nofollow sponsored">Shop now at {$brandName}</a>` — put this link in the first paragraph.
3. **Brief intro (`<h2>`)**: What the brand is, main benefit.
4. **Products/Services (`<h2>`)**: Main offerings.
5. **Pros and Cons (`<h2>`)**: Two subsections or bullet lists.
6. **Available Coupons**: {$couponListHtml}
7. **Closing CTA (`<p>` at bottom)**: `<a href="{$couponLandingUrl}" rel="nofollow">View all coupons & deals</a>` — put this link in the last paragraph.

Do not claim discounts or codes that are not in the facts.
PROMPT;

        $result = $this->callGemini($apiKey, $model, $prompt, $timeout, [
            'maxOutputTokens' => 6144,
            'temperature' => 0.8,
        ]);

        // Không gán ảnh brand: để featured_image null → Blog dùng ảnh mặc định theo category (slug danh mục).
        if ($result !== null) {
            $result['featured_image'] = null;
        }

        return $result;
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
                $lines[] = "- Core article idea / outline (the article MUST closely follow this; do not change the topic or structure unless impossible): {$ideaSafe}";
            }
            if ($affiliateSafe !== '') {
                $lines[] = "- Affiliate link to include (use exactly; do not modify): {$affiliateSafe}";
            }
            if ($couponSafe !== '') {
                $lines[] = "- Coupon code to show exactly as typed (do not invent conditions or extra discounts): {$couponSafe}";
            }
            $lines[] = "- The article should stay tightly aligned with the idea above (section flow, focus points). Only adjust for grammar and global coherence.";
            $lines[] = "- If an affiliate link is provided, include ONE clear CTA link near the end using `<a href=\"...\" rel=\"nofollow sponsored noopener\" target=\"_blank\">`.";
            $lines[] = "- If a coupon code is provided, include a short section titled `<h2>Coupon code</h2>` containing the code in HTML (e.g. `<p><strong>Code:</strong> CODE</p>`).";
            $lines[] = "- Do not invent extra codes, discount percentages, or time-limited claims beyond what is explicitly provided above.";

            return implode("\n", $lines) . "\n";
        }

        $lines = [];
        $lines[] = "Yêu cầu bổ sung từ người nhập (bắt buộc, coi như ràng buộc chính):";
        if ($ideaSafe !== '') {
            $lines[] = "- Ý tưởng / outline: {$ideaSafe}";
        }
        if ($affiliateSafe !== '') {
            $lines[] = "- Link affiliate cần chèn (dùng đúng link): {$affiliateSafe}";
        }
        if ($couponSafe !== '') {
            $lines[] = "- Mã coupon cần hiển thị (giữ nguyên, không bịa thêm điều kiện/discount): {$couponSafe}";
        }
        $lines[] = "- Nội dung bài viết phải bám sát ý tưởng/outline trên (chỉ được chỉnh nhẹ để mạch lạc hơn, không đổi chủ đề).";
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
