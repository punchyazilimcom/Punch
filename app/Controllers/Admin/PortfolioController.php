<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Models\Portfolio;
use App\Services\Upload;

class PortfolioController extends AdminController
{
    public function index(Request $request): never
    {
        $works = (new Portfolio())->all('sort_order ASC, id DESC');
        $this->view('admin/portfolio', ['pageTitle' => 'Portfolyo', 'works' => $works]);
    }

    public function create(Request $request): never
    {
        $this->view('admin/portfolio-form', ['pageTitle' => 'Yeni Proje', 'work' => null]);
    }

    public function edit(Request $request, array $params): never
    {
        $work = (new Portfolio())->find((int) $params['id']);
        if (!$work) {
            $this->abort(404);
        }
        $this->view('admin/portfolio-form', ['pageTitle' => 'Proje Duzenle', 'work' => $work]);
    }

    public function store(Request $request): never
    {
        $data = $this->validateData($request);
        $id = (new Portfolio())->create($data);
        $this->audit('portfolio.create', ['id' => $id]);
        $this->withSuccess('Proje eklendi.');
        $this->redirect('/admin/portfolyo');
    }

    public function update(Request $request, array $params): never
    {
        $work = (new Portfolio())->find((int) $params['id']);
        if (!$work) {
            $this->abort(404);
        }
        $data = $this->validateData($request, (int) $work['id'], $work);
        (new Portfolio())->update((int) $work['id'], $data);
        $this->audit('portfolio.update', ['id' => $work['id']]);
        $this->withSuccess('Proje guncellendi.');
        $this->redirect('/admin/portfolyo');
    }

    public function destroy(Request $request, array $params): never
    {
        (new Portfolio())->delete((int) $params['id']);
        $this->audit('portfolio.delete', ['id' => $params['id']]);
        $this->withSuccess('Proje silindi.');
        $this->redirect('/admin/portfolyo');
    }

    private function validateData(Request $request, ?int $id = null, ?array $existing = null): array
    {
        $input = $request->only(['title', 'slug', 'summary', 'body', 'category', 'client', 'url']);
        $v = new Validator($input, ['title' => 'Baslik']);
        if (!$v->validate(['title' => 'required|max:190', 'url' => $input['url'] ? 'url' : ''])) {
            Session::flashInput($input);
            Session::set('_errors', $v->firstErrors());
            $this->back($id ? "/admin/portfolyo/{$id}" : '/admin/portfolyo/yeni');
        }

        $cover = $existing['cover_image'] ?? null;
        try {
            $uploaded = Upload::image($request->file('cover_image'), 'portfolio');
            if ($uploaded) {
                $cover = $uploaded;
            }
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage());
        }

        return [
            'title'       => $input['title'],
            'slug'        => $input['slug'] ? slugify($input['slug']) : slugify($input['title']),
            'summary'     => $input['summary'] ?: null,
            'body'        => $input['body'] ?: null,
            'category'    => $input['category'] ?: null,
            'client'      => $input['client'] ?: null,
            'url'         => $input['url'] ?: null,
            'cover_image' => $cover,
            'is_active'   => $request->bool('is_active') ? 1 : 0,
            'sort_order'  => $request->int('sort_order'),
        ];
    }
}
