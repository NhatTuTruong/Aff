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

    public function generateBlog(string $category, string $variant): ?array
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
- Ngôn ngữ: tiếng Anh, chuẩn SEO, độ dài 1,500–2,200 từ.
- Cấu trúc: 1 thẻ <h1> duy nhất, các phần chính dùng <h2>, có thể thêm <h3>.
- Nội dung hữu ích, không quảng cáo thương hiệu cụ thể.
- Trả về HTML hoàn chỉnh: <h1>, <p>, <ul>/<ol>, <h2>, <h3>. Không bọc <html>/<body>.

Danh mục: {$category}
Loại bài: {$variant}
{$topicPrompt}
PROMPT;

        return $this->callGemini($apiKey, $model, $prompt, $timeout);
    }

    /**
     * Blog giới thiệu brand + coupon landing, có CTA và link affiliate khi nhắc tên brand.
     *
     * @return array{title: string, content: string, featured_image: ?string}|null
     */
    public function generateBrandIntroBlog(Brand $brand, Campaign $campaign, string $categoryName): ?array
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

        $couponLines = $campaign->couponItems
            ->take(12)
            ->map(function ($c) {
                $parts = array_filter([
                    $c->offer ?? null,
                    $c->code ? "code: {$c->code}" : null,
                    $c->description ? Str::limit(strip_tags((string) $c->description), 120) : null,
                ]);

                return $parts ? implode(' — ', $parts) : null;
            })
            ->filter()
            ->join("\n");

        if ($couponLines === '') {
            $couponLines = '(No separate coupon rows; focus on store deals and the landing page.)';
        }

        $year = (int) now()->format('Y');
        $linkExample = '<a href="' . $affiliateTrackingUrl . '" rel="nofollow sponsored">' . htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8') . '</a>';

        $prompt = <<<PROMPT
You are an expert English SEO copywriter for affiliate coupon sites.

Write ONE long-form blog article introducing the store/brand below. Language: **English**. Target length **1,800–2,600 words**. Tone: helpful, trustworthy, conversion-oriented but honest.

## Brand & campaign facts (use only as facts; do not invent unavailable data)
- This row is an **imported campaign** in our system. The **store name** in the article must be the Brand name below (same store as on the campaign landing / coupon page).
- Category niche: {$categoryName}
- Brand / store name (exact spelling): {$brandName}
- Domain (if any): {$domain}
- Short brand description: {$shortDesc}
- Campaign / store page title: {$campaignTitle}
- Campaign intro (may be HTML stripped): {$campaignIntro}
- Stated benefits / highlights: {$benefitsText}
- Sample coupons / offers (lines):
{$couponLines}

## Required HTML output rules
- Return **complete HTML fragment** only: use `<h1>` once for the main title, then `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<strong>` as needed. **Do not** wrap in `<html>` or `<body>`.
- **Every time** the brand name "{$brandName}" appears in body copy (headings or paragraphs), it MUST be this exact link (adjust only if the name appears in possessive/plural form—still link the brand token):
  {$linkExample}
- Use `rel="nofollow sponsored"` on every affiliate link to the brand name.
- Primary **CTA** at the end: a clear button-style sentence linking to the **coupon/deals landing page** (not the raw merchant site) using this exact URL only: {$couponLandingUrl}
  Example: `<p><a href="{$couponLandingUrl}" rel="nofollow">View latest coupons &amp; deals</a></p>`

## Required article structure (use these section ideas; use clear `<h2>` titles in English)
1. **Title (`<h1>`)**: Include brand name + main category keyword + conversion angle. You may naturally add one of: Review, Coupon, Discount, or {$year} where it fits (do not stuff).
2. **Opening hook (`<h2>`)**: Problem the reader faces; transition to this brand and the main benefit.
3. **What is {$brandName} (`<h2>`)**: What the brand is, what it sells, unique selling points (USP).
4. **Main products or services (`<h2>`)**: Main lines; optional `<h3>` by category if it fits the facts.
5. **Pros and cons (`<h2>`) — MANDATORY**: Two subsections or bullet lists — **Pros** and **Cons** (balanced, builds trust). Do not skip cons.
6. **In-depth review (`<h2>`)**: Experience/features, who it is for, optional short comparison with typical alternatives (only if generic, no fake competitor names/data).
7. **Closing + CTA (`<h2>` or final `<p>`)**: Summarize and drive to the coupon landing link above.

Do not claim discounts or codes that are not in the facts; you may say readers can find current offers on your deals page.
PROMPT;

        $result = $this->callGemini($apiKey, $model, $prompt, $timeout, [
            'maxOutputTokens' => 8192,
            'temperature' => 0.85,
        ]);

        // Không gán ảnh brand: để featured_image null → Blog dùng ảnh mặc định theo category (slug danh mục).
        if ($result !== null) {
            $result['featured_image'] = null;
        }

        return $result;
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
}
