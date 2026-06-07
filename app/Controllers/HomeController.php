<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Package;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Post;
use App\Services\Seo;

class HomeController extends Controller
{
    public function index(Request $request): never
    {
        $packages  = (new Package())->featured(3);
        $services  = (new Service())->active();
        $works     = (new Portfolio())->featured(6);
        $posts     = (new Post())->published(3);

        $stats        = setting_json('stats_json', []);
        $testimonials = setting_json('testimonials_json', []);
        $faqs         = setting_json('faq_json', []);

        $seo = Seo::build([
            'title'       => setting('seo_default_title', 'Punch Yazilim — Ankara Web Tasarim & Dijital Ajans'),
            'description' => setting('seo_default_description', 'Ankara merkezli dijital yazilim ajansi. Web tasarim, e-ticaret, sosyal medya ve ozel yazilim cozumleri.'),
            'og_type'     => 'website',
        ]);

        $jsonld = [
            Seo::organization(),
            Seo::localBusiness(),
            Seo::website(),
        ];
        if (!empty($faqs)) {
            $jsonld[] = Seo::faqPage($faqs);
        }

        $this->render('pages/home', [
            'seo'          => $seo,
            'jsonld'       => $jsonld,
            'heroCanvas'   => true,
            'packages'     => $packages,
            'services'     => $services,
            'works'        => $works,
            'posts'        => $posts,
            'stats'        => $stats,
            'testimonials' => $testimonials,
            'faqs'         => $faqs,
        ]);
    }
}
