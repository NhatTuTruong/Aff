<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(SitemapGenerator $generator): Response
    {
        $path = (string) config('sitemap.write_path', public_path('sitemap.xml'));

        if (is_file($path)) {
            $xml = file_get_contents($path);

            return response($xml !== false ? $xml : $generator->buildXml(), 200, $this->headers());
        }

        return response($generator->buildXml(), 200, $this->headers());
    }

    /**
     * @return array<string, string>
     */
    private function headers(): array
    {
        return [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ];
    }
}
