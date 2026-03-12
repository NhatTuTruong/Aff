<?php

declare(strict_types=1);

/**
 * Run:
 *   php scripts/gemini_smoke_test.php
 */

$key = getenv('GEMINI_API_KEY') ?: '';
$model = getenv('GEMINI_MODEL') ?: 'gemini-1.5-flash';

if (trim($key) === '') {
    fwrite(STDERR, "GEMINI_API_KEY is empty\n");
    exit(2);
}

$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . rawurlencode($model) . ':generateContent?key=' . urlencode($key);
$payload = json_encode([
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => 'Return ONLY: <h2>Introduction</h2><p>Test</p>'],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_TIMEOUT => 25,
]);

$out = curl_exec($ch);
if ($out === false) {
    fwrite(STDERR, 'curl error: ' . curl_error($ch) . "\n");
    exit(3);
}

$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "http={$code}\n";
echo substr((string) $out, 0, 800) . "\n";

