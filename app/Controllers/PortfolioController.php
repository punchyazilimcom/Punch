<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Portfolio;
use App\Services\Seo;

class PortfolioController extends Controller
{
    public function index(Request $request): never
    {
        $model = new Portfolio();
        $works = $model->active();
        $categories = $model->categories();
        $seo = Seo::build([
            'title'       => 'Portfolyo & Referanslar | Punch Yazilim',
            'description' => 'Tamamladigimiz web tasarim, e-ticaret ve yazilim projeleri. Punch Yazilim referanslari.',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Portfolyo' => base_url('portfolyo')])];
        $this->render('pages/portfolio', compact('works', 'categories', 'seo', 'jsonld'));
    }

    public function show(Request $request, array $params): never
    {
        $work = (new Portfolio())->bySlug($params['slug']);
        if (!$work || !$work['is_active']) {
            $this->abort(404);
        }
        $gallery = json_decode($work['gallery_json'] ?? '[]', true) ?: [];
        $seo = Seo::build([
            'title'       => $work['title'] . ' — Portfolyo | Punch Yazilim',
            'description' => str_excerpt($work['summary'] ?? '', 155),
        ]);
        $jsonld = [Seo::breadcrumb([
            'Ana Sayfa' => base_url(), 'Portfolyo' => base_url('portfolyo'),
            $work['title'] => base_url('portfolyo/' . $work['slug']),
        ])];
        $this->render('pages/portfolio-detail', compact('work', 'gallery', 'seo', 'jsonld'));
    }
}
