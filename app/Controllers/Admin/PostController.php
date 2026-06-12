<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Core\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Services\Upload;

class PostController extends AdminController
{
    public function index(Request $request): never
    {
        $posts = (new Post())->allForAdmin();
        $this->view('admin/posts', ['pageTitle' => 'Blog Yazilari', 'posts' => $posts]);
    }

    public function create(Request $request): never
    {
        $this->view('admin/post-form', [
            'pageTitle'  => 'Yeni Yazi',
            'post'       => null,
            'categories' => (new Category())->all('name ASC'),
        ]);
    }

    public function edit(Request $request, array $params): never
    {
        $post = (new Post())->find((int) $params['id']);
        if (!$post) {
            $this->abort(404);
        }
        $this->view('admin/post-form', [
            'pageTitle'  => 'Yazi Duzenle',
            'post'       => $post,
            'categories' => (new Category())->all('name ASC'),
        ]);
    }

    public function store(Request $request): never
    {
        $data = $this->validateData($request);
        $id = (new Post())->create($data);
        $this->audit('post.create', ['id' => $id]);
        $this->withSuccess('Yazi olusturuldu.');
        $this->redirect('/admin/blog');
    }

    public function update(Request $request, array $params): never
    {
        $post = (new Post())->find((int) $params['id']);
        if (!$post) {
            $this->abort(404);
        }
        $data = $this->validateData($request, (int) $post['id'], $post);
        (new Post())->update((int) $post['id'], $data);
        $this->audit('post.update', ['id' => $post['id']]);
        $this->withSuccess('Yazi guncellendi.');
        $this->redirect('/admin/blog');
    }

    public function destroy(Request $request, array $params): never
    {
        (new Post())->delete((int) $params['id']);
        $this->audit('post.delete', ['id' => $params['id']]);
        $this->withSuccess('Yazi silindi.');
        $this->redirect('/admin/blog');
    }

    private function validateData(Request $request, ?int $id = null, ?array $existing = null): array
    {
        $input = $request->only(['title', 'slug', 'excerpt', 'body', 'category_id', 'status', 'published_at', 'meta_title', 'meta_description']);
        $v = new Validator($input, ['title' => 'Baslik', 'body' => 'Icerik']);
        if (!$v->validate(['title' => 'required|max:190', 'body' => 'required'])) {
            Session::flashInput($input);
            Session::set('_errors', $v->firstErrors());
            $this->back($id ? "/admin/blog/{$id}" : '/admin/blog/yeni');
        }

        $slug = $input['slug'] ? slugify($input['slug']) : slugify($input['title']);
        $status = in_array($input['status'], ['draft', 'published', 'scheduled'], true) ? $input['status'] : 'draft';

        // Yayin tarihi
        $publishedAt = $existing['published_at'] ?? null;
        if ($status === 'published') {
            $publishedAt = $publishedAt ?: date('Y-m-d H:i:s');
        } elseif ($status === 'scheduled' && $input['published_at']) {
            $publishedAt = date('Y-m-d H:i:s', strtotime($input['published_at']));
        }

        $cover = $existing['cover_image'] ?? null;
        try {
            $uploaded = Upload::image($request->file('cover_image'), 'blog');
            if ($uploaded) {
                $cover = $uploaded; // web yolu: /assets/uploads/blog/...
            }
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage());
        }

        return [
            'title'            => $input['title'],
            'slug'             => $slug,
            'excerpt'          => $input['excerpt'] ?: str_excerpt(strip_tags($input['body']), 180),
            'body'             => $input['body'],
            'category_id'      => $input['category_id'] ? (int) $input['category_id'] : null,
            'author_id'        => $existing['author_id'] ?? Auth::id(Auth::GUARD_ADMIN),
            'status'           => $status,
            'published_at'     => $publishedAt,
            'reading_time'     => reading_time($input['body']),
            'cover_image'      => $cover,
            'meta_title'       => $input['meta_title'] ?: null,
            'meta_description' => $input['meta_description'] ?: null,
        ];
    }
}
