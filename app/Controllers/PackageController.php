<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Package;
use App\Services\Seo;

class PackageController extends Controller
{
    public function index(Request $request): never
    {
        $packages = (new Package())->active();
        $faqs = setting_json('faq_json', []);
        $seo = Seo::build([
            'title'       => 'Paketler & Fiyatlar — Kurumsal Web, E-Ticaret | Punch Yazilim',
            'description' => 'Kurumsal web site, e-ticaret ve sosyal medya yonetimi paketleri. Sabit fiyatli paketleri online satin alin veya teklif isteyin.',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Paketler' => base_url('paketler')])];
        foreach ($packages as $p) {
            $jsonld[] = Seo::product($p);
        }
        if ($faqs) { $jsonld[] = Seo::faqPage($faqs); }
        $this->render('pages/packages', compact('packages', 'faqs', 'seo', 'jsonld'));
    }

    public function show(Request $request, array $params): never
    {
        $package = (new Package())->bySlug($params['slug']);
        if (!$package || !$package['is_active']) {
            $this->abort(404);
        }
        $seo = Seo::build([
            'title'       => $package['meta_title'] ?: ($package['title'] . ' | Punch Yazilim'),
            'description' => $package['meta_description'] ?: str_excerpt($package['short_desc'] ?? '', 155),
            'og_type'     => 'product',
        ]);
        $jsonld = [
            Seo::product($package),
            Seo::breadcrumb([
                'Ana Sayfa' => base_url(), 'Paketler' => base_url('paketler'),
                $package['title'] => base_url('paketler/' . $package['slug']),
            ]),
        ];
        $this->render('pages/package-detail', compact('package', 'seo', 'jsonld'));
    }
}
