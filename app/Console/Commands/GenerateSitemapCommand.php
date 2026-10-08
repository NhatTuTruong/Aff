<?php

namespace App\Console\Commands;

use App\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate public/sitemap.xml from DB (reviews, blogs, stores, static pages)';

    public function handle(SitemapGenerator $generator): int
    {
        $path = $generator->write();
        $count = $generator->urlCount();

        $this->info("Sitemap written: {$path} ({$count} URLs).");

        return self::SUCCESS;
    }
}
