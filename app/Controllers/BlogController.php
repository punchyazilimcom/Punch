<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Post;
use App\Models\Category;
use App\Services\Seo;

class BlogController extends Controller
{
    private int $perPage = 9;

    public function index(Request $request): never
    {
        $page = max(1, $request->int('sayfa', 1));
        $offset = ($page - 1) * $this->perPage;

        $postModel = new Post();
        $posts = $postModel->published($this->perPage, $offset);
        $total = $postModel->publishedCount();
        $categories = (new Category())->withCounts();

        $seo = Seo::build([
            'title'       => 'Blog — Dijital Pazarlama & Yazilim | Punch Yazilim',
            'description' => 'Web tasarim, e-ticaret, SEO ve sosyal medya uzerine guncel rehberler ve ipuclari.',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Blog' => base_url('blog')])];

        $this->render('pages/blog', [
            'posts' => $posts, 'categories' => $categories, 'activeCategory' => null,
            'page' => $page, 'total' => $total, 'perPage' => $this->perPage,
            'seo' => $seo, 'jsonld' => $jsonld,
        ]);
    }

    public function category(Request $request, array $params): never
    {
        $category = (new Category())->bySlug($params['slug']);
        if (!$category) {
            $this->abort(404);
        }
        $page = max(1, $request->int('sayfa', 1));
        $offset = ($page - 1) * $this->perPage;

        $postModel = new Post();
        $posts = $postModel->published($this->perPage, $offset, (int) $category['id']);
        $total = $postModel->publishedCount((int) $category['id']);
        $categories = (new Category())->withCounts();

        $seo = Seo::build([
            'title'       => $category['name'] . ' — Blog | Punch Yazilim',
            'description' => $category['name'] . ' kategorisindeki yazilar — Punch Yazilim blog.',
        ]);
        $jsonld = [Seo::breadcrumb([
            'Ana Sayfa' => base_url(), 'Blog' => base_url('blog'),
            $category['name'] => base_url('blog/kategori/' . $category['slug']),
        ])];

        $this->render('pages/blog', [
            'posts' => $posts, 'categories' => $categories, 'activeCategory' => $category,
            'page' => $page, 'total' => $total, 'perPage' => $this->perPage,
            'seo' => $seo, 'jsonld' => $jsonld,
        ]);
    }

    public function show(Request $request, array $params): never
    {
        $postModel = new Post();
        $post = $postModel->bySlug($params['slug']);
        if (!$post || $post['status'] !== 'published') {
            $this->abort(404);
        }
        // Goruntulenme sayaci
        $postModel->update((int) $post['id'], ['views' => (int) $post['views'] + 1]);

        $related = $postModel->related((int) $post['id'], $post['category_id'] ? (int) $post['category_id'] : null);

        $seo = Seo::build([
            'title'       => $post['meta_title'] ?: ($post['title'] . ' | Punch Yazilim Blog'),
            'description' => $post['meta_description'] ?: str_excerpt($post['excerpt'] ?? $post['body'], 155),
            'og_type'     => 'article',
            'og_image'    => $post['og_image'] ? base_url(ltrim($post['og_image'], '/')) : Seo::build()['og_image'],
        ]);
        $jsonld = [
            Seo::blogPosting($post),
            Seo::breadcrumb([
                'Ana Sayfa' => base_url(), 'Blog' => base_url('blog'),
                $post['title'] => base_url('blog/' . $post['slug']),
            ]),
        ];

        $this->render('pages/blog-detail', compact('post', 'related', 'seo', 'jsonld'));
    }
}
