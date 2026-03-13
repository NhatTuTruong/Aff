<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiBlogService
{
    /** @var string|null Lưu lỗi gần nhất để debug */
    public ?string $lastError = null;

    public function generateBlog(string $category, string $variant): ?array
    {
        $apiKey = config('gemini.api_key');
        $model = config('gemini.model', 'gemini-1.5-flash-latest');
        $timeout = (int) config('gemini.timeout', 60);

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

    protected function callGemini(string $apiKey, string $model, string $prompt, int $timeout): ?array
    {
        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            $model
        );

        $payload = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'temperature' => 0.9,
                'topP' => 0.8,
                'topK' => 40,
            ],
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
