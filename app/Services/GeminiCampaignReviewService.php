<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Composer\CaBundle\CaBundle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiCampaignReviewService
{
    /**
     * Generate a short HTML intro (~<400 words) that only contains:
     * - Introduction (store, main products, promotions)
     * - Features and benefits
     */
    public function generateIntroHtml(string $brandName, ?string $domain = null, ?string $campaignTitle = null, ?string &$error = null): ?string
    {
        $error = null;

        $apiKey = (string) env('GEMINI_API_KEY', '');
        if (trim($apiKey) === '') {
            $error = 'GEMINI_API_KEY is empty (or not loaded).';
            return null;
        }

        $brandName = trim($brandName);
        if ($brandName === '') {
            $error = 'Brand name is empty.';
            return null;
        }

        $domain = $this->cleanDomain($domain);

        // Example models: gemini-flash-latest, gemini-pro-latest, gemini-2.0-flash
        $model = (string) env('GEMINI_MODEL', 'gemini-flash-latest');
        $model = trim($model);
        if (str_starts_with($model, 'models/')) {
            $model = substr($model, strlen('models/'));
        }
        if ($model === '') {
            $model = 'gemini-flash-latest';
        }
        // Keep this small because imports may call the API many times in a single request.
        $timeout = (int) env('GEMINI_TIMEOUT_SECONDS', 4);
        $timeout = max(2, min($timeout, 6));

        $verify = CaBundle::getBundledCaBundlePath() ?: true;

        $client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout' => max(5, $timeout),
            // On some Windows/PHP setups the system CA bundle isn't configured (cURL error 60).
            // Use Composer CA bundle when available.
            'verify' => $verify,
        ]);

        $system = <<<SYS
You are an affiliate marketing copywriter.
Write in English. Return ONLY valid HTML (no markdown, no code fences).
Use <h2> for section headings, <p> for paragraphs, and <ul><li> for lists.
Do not invent specific prices, guarantees, endorsements, or measurable claims.
If details are unknown, be transparent and use sensible, non-committal language.
SYS;

        $domainText = $domain ?: '(not provided)';
        $campaignTitle = trim((string) $campaignTitle);

        $prompt = <<<USR
Create a SHORT "About / Review" introduction for an affiliate campaign.

Use the following context IN THIS PRIORITY ORDER:
1) Store domain: {$domainText}
2) Store (brand) name: {$brandName}
3) Campaign name: {$campaignTitle}

The article MUST contain ONLY TWO sections, in this exact order, each as <h2>:
1) Introduction
   - Briefly introduce the store, its main products or services, and highlight any typical promotions or deals it usually offers.
   - This section must also naturally mention the campaign if the campaign name is provided.
2) Features and benefits
   - Summarise key features of the products/services and the main benefits for customers.

Output requirements:
- HTML only.
- Total length strictly under 400 English words.
- Keep it clear, concise, realistic, and focused on helping readers quickly understand the store and why they might be interested.
- If concrete details are unknown, be transparent and use generic but plausible descriptions (e.g., "a wide range of digital tools", "regular seasonal promotions") without inventing specific numbers or guarantees.
USR;

        try {
            $endpoint = "v1beta/models/{$model}:generateContent";
            $resp = $client->post($endpoint, [
                'query' => ['key' => $apiKey],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $system],
                        ],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2048,
                    ],
                ],
            ]);

            $data = json_decode((string) $resp->getBody(), true);
            $html = (string) ($data['candidates'][0]['content']['parts'][0]['text'] ?? '');
            $html = trim($html);

            if ($html === '') {
                $error = 'Gemini returned empty content.';
                return null;
            }

            if (! Str::contains($html, '<h2>') || ! Str::contains($html, '<p>')) {
                $error = 'Gemini returned non-HTML content.';
                return null;
            }

            return $html;
        } catch (RequestException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $body = $e->getResponse() ? (string) $e->getResponse()->getBody() : null;

            $error = trim(sprintf(
                'Gemini request failed%s%s',
                $status ? " (HTTP {$status})" : '',
                $body ? (': ' . Str::limit(preg_replace('/\s+/', ' ', $body), 350)) : ''
            ));

            Log::error('Gemini generateContent failed', [
                'status' => $status,
                'response' => $body,
                'message' => $e->getMessage(),
                'model' => $model,
            ]);

            return null;
        } catch (\Throwable $e) {
            $error = 'Gemini request failed: ' . $e->getMessage();
            Log::error('Gemini generateContent exception', [
                'message' => $e->getMessage(),
                'model' => $model,
            ]);
            return null;
        }
    }

    private function cleanDomain(?string $domain): ?string
    {
        $domain = trim((string) $domain);
        if ($domain === '') {
            return null;
        }
        $domain = preg_replace('/^https?:\/\//i', '', $domain);
        $domain = preg_replace('/^www\./i', '', $domain);
        $domain = explode('/', $domain)[0] ?? $domain;
        $domain = trim($domain);

        return $domain !== '' ? $domain : null;
    }
}

