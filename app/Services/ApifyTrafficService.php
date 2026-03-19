<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ApifyTrafficService
{
    public function runTrafficActor(string $token, array $domains, string $actorId = 'aqPbs3KeH9aD8b22w'): array
    {
        $runInput = [
            'domains' => array_values($domains),
            'proxyConfiguration' => [
                'useApifyProxy' => false,
            ],
        ];

        // Apify token: query param
        $response = Http::timeout(120)
            ->acceptJson()
            ->asJson()
            ->post("https://api.apify.com/v2/acts/{$actorId}/runs?token=" . urlencode($token), $runInput);

        $response->throw();
        $json = $response->json();

        $data = $json['data'] ?? null;
        if (! is_array($data)) {
            throw new \RuntimeException('Apify response invalid: missing data');
        }

        $runId = $data['id'] ?? null;
        if (! is_string($runId) || $runId === '') {
            // If no run id, just return the raw data (best effort)
            return $data;
        }

        // Poll run status until it finishes or timeout (~2 minutes)
        $status = $data['status'] ?? null;
        $startedAt = microtime(true);
        while (in_array($status, ['READY', 'RUNNING', 'STARTING', 'SCHEDULED'], true)) {
            if (microtime(true) - $startedAt > 120) {
                break;
            }

            usleep(3_000_000); // 3 seconds

            $check = Http::timeout(30)
                ->acceptJson()
                ->get("https://api.apify.com/v2/actor-runs/{$runId}", [
                    'token' => $token,
                ])
                ->throw()
                ->json();

            $data = $check['data'] ?? $data;
            $status = is_array($data) ? ($data['status'] ?? $status) : $status;
        }

        return is_array($data) ? $data : ['status' => $status, 'id' => $runId];
    }

    public function fetchDatasetItems(string $token, string $datasetId): array
    {
        $response = Http::timeout(120)
            ->acceptJson()
            ->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
                'token' => $token,
                'format' => 'json',
                'clean' => 'true',
            ])
            ->throw();

        $items = $response->json();
        return is_array($items) ? $items : [];
    }

    public function fetchDatasetItemsPage(string $token, string $datasetId, int $offset, int $limit): array
    {
        $response = Http::timeout(120)
            ->acceptJson()
            ->get("https://api.apify.com/v2/datasets/{$datasetId}/items", [
                'token' => $token,
                'format' => 'json',
                'clean' => 'true',
                'offset' => $offset,
                'limit' => $limit,
            ])
            ->throw();

        $items = $response->json();
        return is_array($items) ? $items : [];
    }
}

