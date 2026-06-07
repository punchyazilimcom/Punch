<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\Seo;

class PageController extends Controller
{
    public function about(Request $request): never
    {
        $stats = setting_json('stats_json', []);
        $seo = Seo::build([
            'title'       => 'Hakkimizda — Punch Yazilim | Ankara Dijital Ajans',
            'description' => 'Punch Yazilim; Ankara merkezli, deneyim odakli dijital cozumler ureten bir yazilim ajansidir. Hikayemiz, degerlerimiz ve ekibimiz.',
        ]);
        $jsonld = [Seo::organization(), Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Hakkimizda' => base_url('hakkimizda')])];
        $this->render('pages/about', compact('stats', 'seo', 'jsonld'));
    }

    /**
     * Hukuki sayfalar tek controller'da; yol uzerinden icerik secilir.
     */
    public function legal(Request $request): never
    {
        $map = [
            '/on-bilgilendirme-formu'    => 'legal_preinfo',
            '/mesafeli-satis-sozlesmesi' => 'legal_sales',
            '/teslimat-ve-iade'          => 'legal_delivery',
            '/kvkk'                      => 'legal_kvkk',
            '/gizlilik-politikasi'       => 'legal_privacy',
            '/cerez-politikasi'          => 'legal_cookie',
        ];
        $key = $map[$request->path] ?? null;
        if (!$key) {
            $this->abort(404);
        }

        $title = \App\Services\LegalText::title($key);
        $body  = \App\Services\LegalText::get($key);

        $seo = Seo::build([
            'title'       => $title . ' | Punch Yazilim',
            'description' => $title . ' — Punch Yazilim, Ankara dijital yazilim ajansi.',
            'robots'      => 'index, follow',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), $title => Seo::currentUrl()])];
        $this->render('pages/legal', [
            'pageTitle' => $title,
            'body'      => $body,
            'seo'       => $seo,
            'jsonld'    => $jsonld,
        ]);
    }
}
