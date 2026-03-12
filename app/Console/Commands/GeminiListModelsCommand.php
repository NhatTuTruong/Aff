<?php

namespace App\Console\Commands;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GeminiListModelsCommand extends Command
{
    protected $signature = 'gemini:list-models {--contains=gemini}';

    protected $description = 'List available Gemini models for the API key';

    public function handle(): int
    {
        $apiKey = (string) env('GEMINI_API_KEY', '');
        if (trim($apiKey) === '') {
            $this->error('GEMINI_API_KEY is empty (or not loaded).');
            return self::FAILURE;
        }

        $contains = (string) $this->option('contains');

        $client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout' => 20,
            'verify' => CaBundle::getBundledCaBundlePath() ?: true,
        ]);

        $resp = $client->get('v1beta/models', [
            'query' => ['key' => $apiKey],
        ]);

        $data = json_decode((string) $resp->getBody(), true);
        $models = (array) ($data['models'] ?? []);

        $filtered = [];
        foreach ($models as $m) {
            $name = (string) ($m['name'] ?? '');
            $methods = (array) ($m['supportedGenerationMethods'] ?? []);

            if ($contains !== '' && stripos($name, $contains) === false) {
                continue;
            }
            if (! in_array('generateContent', $methods, true)) {
                continue;
            }

            $filtered[] = [
                'name' => $name,
                'methods' => implode(',', $methods),
            ];
        }

        if ($filtered === []) {
            $this->warn('No models found that match your filters and support generateContent.');
            $this->line('Try: php artisan gemini:list-models --contains=');
            return self::SUCCESS;
        }

        foreach ($filtered as $row) {
            $this->line($row['name'] . ' | ' . $row['methods']);
        }

        return self::SUCCESS;
    }
}

