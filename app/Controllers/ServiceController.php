<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Service;
use App\Models\Package;
use App\Services\Seo;

class ServiceController extends Controller
{
    public function index(Request $request): never
    {
        $services = (new Service())->active();
        $seo = Seo::build([
            'title'       => 'Hizmetlerimiz — Web, E-Ticaret, Sosyal Medya & Yazilim | Punch Yazilim',
            'description' => 'Web tasarim, e-ticaret cozumleri, sosyal medya yonetimi ve ozel yazilim gelistirme hizmetleri. Ankara merkezli dijital ajans.',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Hizmetler' => base_url('hizmetler')])];
        $this->render('pages/services', compact('services', 'seo', 'jsonld'));
    }

    public function show(Request $request, array $params): never
    {
        $service = (new Service())->bySlug($params['slug']);
        if (!$service || !$service['is_active']) {
            $this->abort(404);
        }
        $packages = (new Package())->featured(3);
        $seo = Seo::build([
            'title'       => $service['meta_title'] ?: ($service['title'] . ' | Punch Yazilim'),
            'description' => $service['meta_description'] ?: str_excerpt($service['short_desc'] ?? $service['description'] ?? '', 155),
        ]);
        $jsonld = [
            [
                '@context' => 'https://schema.org', '@type' => 'Service',
                'name' => $service['title'], 'description' => $service['short_desc'] ?? '',
                'provider' => ['@type' => 'Organization', 'name' => config('app.name')],
                'areaServed' => 'TR',
            ],
            Seo::breadcrumb([
                'Ana Sayfa' => base_url(), 'Hizmetler' => base_url('hizmetler'),
                $service['title'] => base_url('hizmetler/' . $service['slug']),
            ]),
        ];
        $this->render('pages/service-detail', compact('service', 'packages', 'seo', 'jsonld'));
    }
}
