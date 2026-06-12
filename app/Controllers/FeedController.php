<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Post;

/**
 * Blog RSS 2.0 beslemesi (/feed.xml) — SEO ve okuyucu uygulamalari icin.
 */
class FeedController
{
    public function rss(Request $request): never
    {
        $base = rtrim((string) config('app.url'), '/');
        $title = setting('seo_default_title', config('app.name'));
        $desc = setting('seo_default_description', 'Punch Yazilim blog.');

        $items = [];
        try {
            $items = (new Post())->published(30);
        } catch (\Throwable $e) {
            // DB yoksa bos besleme dondur
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"><channel>' . "\n";
        $xml .= '  <title>' . htmlspecialchars($title, ENT_XML1) . "</title>\n";
        $xml .= '  <link>' . htmlspecialchars($base . '/blog', ENT_XML1) . "</link>\n";
        $xml .= '  <description>' . htmlspecialchars($desc, ENT_XML1) . "</description>\n";
        $xml .= '  <language>tr-TR</language>' . "\n";
        $xml .= '  <atom:link href="' . htmlspecialchars($base . '/feed.xml', ENT_XML1) . '" rel="self" type="application/rss+xml" />' . "\n";
        foreach ($items as $p) {
            $url = $base . '/blog/' . $p['slug'];
            $xml .= "  <item>\n";
            $xml .= '    <title>' . htmlspecialchars($p['title'], ENT_XML1) . "</title>\n";
            $xml .= '    <link>' . htmlspecialchars($url, ENT_XML1) . "</link>\n";
            $xml .= '    <guid isPermaLink="true">' . htmlspecialchars($url, ENT_XML1) . "</guid>\n";
            $xml .= '    <description>' . htmlspecialchars((string)($p['excerpt'] ?? ''), ENT_XML1) . "</description>\n";
            if (!empty($p['published_at'])) {
                $xml .= '    <pubDate>' . date(DATE_RSS, strtotime($p['published_at'])) . "</pubDate>\n";
            }
            if (!empty($p['category_name'])) {
                $xml .= '    <category>' . htmlspecialchars($p['category_name'], ENT_XML1) . "</category>\n";
            }
            $xml .= "  </item>\n";
        }
        $xml .= '</channel></rss>';

        http_response_code(200);
        header('Content-Type: application/rss+xml; charset=utf-8');
        echo $xml;
        exit;
    }
}
