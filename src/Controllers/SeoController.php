<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\I18n;
use App\Core\Request;
use App\Core\Response;
use App\Models\Project;

final class SeoController
{
    public function sitemap(Request $req): Response
    {
        $base = rtrim((string) env('APP_URL', ''), '/');
        $locales = I18n::enabledLocales();
        $urls = [];

        foreach ($locales as $locale) {
            foreach (['/', '/work', '/contact'] as $path) {
                $urls[] = ['loc' => "{$base}/{$locale}{$path}", 'changefreq' => 'weekly', 'priority' => $path === '/' ? '1.0' : '0.8'];
            }
            foreach (Project::published() as $project) {
                $urls[] = [
                    'loc' => "{$base}/{$locale}/work/" . $project['slug'],
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                    'lastmod' => $project['updated_at'] ?? $project['published_at'] ?? null,
                ];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= "  <url>\n    <loc>" . e($url['loc']) . "</loc>\n";
            if (!empty($url['lastmod'])) {
                $xml .= '    <lastmod>' . e(substr((string) $url['lastmod'], 0, 10)) . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n"
                . '    <priority>' . $url['priority'] . "</priority>\n  </url>\n";
        }
        $xml .= '</urlset>';

        return new Response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function robots(Request $req): Response
    {
        $base = rtrim((string) env('APP_URL', ''), '/');
        $body = "User-agent: *\n"
            . "Allow: /\n"
            . "Disallow: /admin\n"
            . "Disallow: /api\n"
            . "\nSitemap: {$base}/sitemap.xml\n";
        return new Response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
