<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Post;
use App\Models\Portfolio;
use App\Models\Package;
use App\Models\Service;

class SitemapController
{
    public function xml(Request $request): never
    {
        $base = rtrim((string) config('app.url'), '/');
        $urls = [];

        $add = function (string $loc, ?string $lastmod = null, string $freq = 'weekly', string $priority = '0.7') use (&$urls, $base) {
            $urls[] = [
                'loc' => $base . $loc,
                'lastmod' => $lastmod ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d'),
                'changefreq' => $freq,
                'priority' => $priority,
            ];
        };

        // Statik sayfalar
        $add('/', null, 'daily', '1.0');
        $add('/hizmetler', null, 'monthly', '0.8');
        $add('/paketler', null, 'monthly', '0.9');
        $add('/portfolyo', null, 'weekly', '0.8');
        $add('/hakkimizda', null, 'yearly', '0.5');
        $add('/blog', null, 'daily', '0.8');
        $add('/iletisim', null, 'yearly', '0.6');
        $add('/kvkk', null, 'yearly', '0.3');
        $add('/gizlilik-politikasi', null, 'yearly', '0.3');
        $add('/mesafeli-satis-sozlesmesi', null, 'yearly', '0.3');
        $add('/cerez-politikasi', null, 'yearly', '0.3');
        $add('/teslimat-ve-iade', null, 'yearly', '0.3');

        try {
            foreach ((new Service())->active() as $s) {
                $add('/hizmetler/' . $s['slug'], $s['updated_at'] ?? null, 'monthly', '0.7');
            }
            foreach ((new Package())->active() as $p) {
                $add('/paketler/' . $p['slug'], $p['updated_at'] ?? null, 'monthly', '0.8');
            }
            foreach ((new Portfolio())->active() as $w) {
                $add('/portfolyo/' . $w['slug'], $w['created_at'] ?? null, 'monthly', '0.6');
            }
            foreach ((new Post())->published(500) as $post) {
                $add('/blog/' . $post['slug'], $post['updated_at'] ?? $post['published_at'], 'weekly', '0.7');
            }
        } catch (\Throwable $e) {
            // DB yoksa statik sayfalarla devam et
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        http_response_code(200);
        header('Content-Type: application/xml; charset=utf-8');
        echo $xml;
        exit;
    }
}
