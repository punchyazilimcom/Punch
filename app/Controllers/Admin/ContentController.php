<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Setting;

/**
 * Ana sayfa & site icerikleri (hero, istatistik, yorum, SSS, hukuki metinler).
 */
class ContentController extends AdminController
{
    public function index(Request $request): never
    {
        $s = new Setting();
        $this->view('admin/content', [
            'pageTitle'    => 'Sayfa Icerikleri',
            'hero'         => [
                'eyebrow'  => $s->get('hero_eyebrow', ''),
                'title'    => $s->get('hero_title', ''),
                'subtitle' => $s->get('hero_subtitle', ''),
            ],
            'stats'        => $s->getJson('stats_json', []),
            'testimonials' => $s->getJson('testimonials_json', []),
            'faqs'         => $s->getJson('faq_json', []),
            'legal'        => [
                'kvkk'     => $s->get('legal_kvkk', ''),
                'privacy'  => $s->get('legal_privacy', ''),
                'sales'    => $s->get('legal_sales', ''),
                'cookie'   => $s->get('legal_cookie', ''),
                'delivery' => $s->get('legal_delivery', ''),
            ],
        ]);
    }

    public function update(Request $request): never
    {
        $s = new Setting();

        // Hero
        $s->setMany([
            'hero_eyebrow'  => $request->string('hero_eyebrow'),
            'hero_title'    => $request->string('hero_title'),
            'hero_subtitle' => $request->string('hero_subtitle'),
        ]);

        // Istatistik (paralel diziler)
        $stats = $this->pairArrays($request->input('stat_value', []), $request->input('stat_label', []), ['value', 'label']);
        $s->set('stats_json', json_encode($stats, JSON_UNESCAPED_UNICODE));

        // Yorumlar
        $names = $request->input('t_name', []);
        $companies = $request->input('t_company', []);
        $texts = $request->input('t_text', []);
        $testimonials = [];
        foreach ((array) $names as $i => $name) {
            if (trim((string) $name) === '') continue;
            $testimonials[] = [
                'name'    => trim((string) $name),
                'company' => trim((string) ($companies[$i] ?? '')),
                'text'    => trim((string) ($texts[$i] ?? '')),
                'rating'  => 5,
            ];
        }
        $s->set('testimonials_json', json_encode($testimonials, JSON_UNESCAPED_UNICODE));

        // SSS
        $faqs = $this->pairArrays($request->input('faq_q', []), $request->input('faq_a', []), ['q', 'a']);
        $s->set('faq_json', json_encode($faqs, JSON_UNESCAPED_UNICODE));

        // Hukuki metinler (yonetici girisi HTML)
        $s->setMany([
            'legal_kvkk'     => (string) $request->input('legal_kvkk', ''),
            'legal_privacy'  => (string) $request->input('legal_privacy', ''),
            'legal_sales'    => (string) $request->input('legal_sales', ''),
            'legal_cookie'   => (string) $request->input('legal_cookie', ''),
            'legal_delivery' => (string) $request->input('legal_delivery', ''),
        ]);

        $this->audit('content.update');
        $this->withSuccess('Sayfa icerikleri guncellendi.');
        $this->redirect('/admin/icerik');
    }

    private function pairArrays($a, $b, array $keys): array
    {
        $a = (array) $a;
        $b = (array) $b;
        $out = [];
        foreach ($a as $i => $val) {
            if (trim((string) $val) === '' && trim((string) ($b[$i] ?? '')) === '') {
                continue;
            }
            $out[] = [$keys[0] => trim((string) $val), $keys[1] => trim((string) ($b[$i] ?? ''))];
        }
        return $out;
    }
}
