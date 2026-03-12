<?php

namespace App\Console\Commands;

use App\Services\GeminiCampaignReviewService;
use Illuminate\Console\Command;

class GeminiSmokeTestCommand extends Command
{
    protected $signature = 'gemini:smoke-test {--brand=TestBrand} {--domain=example.com}';

    protected $description = 'Smoke test Gemini API connectivity';

    public function handle(): int
    {
        $brand = (string) $this->option('brand');
        $domain = (string) $this->option('domain');
        $campaignTitle = 'Test Campaign';

        $caBundle = \Composer\CaBundle\CaBundle::getBundledCaBundlePath();
        $this->line('CA bundle (composer/ca-bundle): ' . ($caBundle ?: '(none)') . ' exists=' . ($caBundle && is_file($caBundle) ? 'yes' : 'no'));
        $this->line('php.ini curl.cainfo=' . (ini_get('curl.cainfo') ?: '(empty)'));
        $this->line('php.ini openssl.cafile=' . (ini_get('openssl.cafile') ?: '(empty)'));

        $error = null;
        $html = app(GeminiCampaignReviewService::class)->generateIntroHtml($brand, $domain, $campaignTitle, $error);

        if (! empty($html)) {
            $this->info('OK: received HTML');
            $this->line(substr($html, 0, 200) . '...');
            return self::SUCCESS;
        }

        $this->error('FAILED');
        $this->line($error ?: '(no error)');
        return self::FAILURE;
    }
}

