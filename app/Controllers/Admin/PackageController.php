<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Models\Package;

class PackageController extends AdminController
{
    public function index(Request $request): never
    {
        $packages = (new Package())->all('sort_order ASC, id ASC');
        $this->view('admin/packages', ['pageTitle' => 'Paketler', 'packages' => $packages]);
    }

    public function create(Request $request): never
    {
        $this->view('admin/package-form', ['pageTitle' => 'Yeni Paket', 'package' => null]);
    }

    public function edit(Request $request, array $params): never
    {
        $package = (new Package())->find((int) $params['id']);
        if (!$package) {
            $this->abort(404);
        }
        $this->view('admin/package-form', ['pageTitle' => 'Paket Duzenle', 'package' => $package]);
    }

    public function store(Request $request): never
    {
        $data = $this->validateData($request);
        $id = (new Package())->create($data);
        $this->audit('package.create', ['id' => $id]);
        $this->withSuccess('Paket olusturuldu.');
        $this->redirect('/admin/paketler');
    }

    public function update(Request $request, array $params): never
    {
        $package = (new Package())->find((int) $params['id']);
        if (!$package) {
            $this->abort(404);
        }
        $data = $this->validateData($request, (int) $package['id']);
        (new Package())->update((int) $package['id'], $data);
        $this->audit('package.update', ['id' => $package['id']]);
        $this->withSuccess('Paket guncellendi.');
        $this->redirect('/admin/paketler');
    }

    public function destroy(Request $request, array $params): never
    {
        (new Package())->delete((int) $params['id']);
        $this->audit('package.delete', ['id' => $params['id']]);
        $this->withSuccess('Paket silindi.');
        $this->redirect('/admin/paketler');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $input = $request->only(['title', 'slug', 'short_desc', 'description', 'features', 'price', 'currency', 'icon', 'meta_title', 'meta_description']);
        $v = new Validator($input, ['title' => 'Baslik', 'price' => 'Fiyat']);
        if (!$v->validate(['title' => 'required|max:190', 'price' => 'numeric'])) {
            Session::flashInput($input);
            Session::set('_errors', $v->firstErrors());
            $this->back($id ? "/admin/paketler/{$id}" : '/admin/paketler/yeni');
        }

        $slug = $input['slug'] ? slugify($input['slug']) : slugify($input['title']);
        // features: satir basina madde
        $features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $input['features']))));

        return [
            'title'            => $input['title'],
            'slug'             => $slug,
            'short_desc'       => $input['short_desc'] ?: null,
            'description'      => $input['description'] ?: null,
            'features_json'    => json_encode($features, JSON_UNESCAPED_UNICODE),
            'price'            => (float) str_replace(',', '.', (string) $input['price']),
            'currency'         => $input['currency'] ?: 'TRY',
            'icon'             => $input['icon'] ?: 'package',
            'is_quote_only'    => $request->bool('is_quote_only') ? 1 : 0,
            'is_popular'       => $request->bool('is_popular') ? 1 : 0,
            'is_active'        => $request->bool('is_active') ? 1 : 0,
            'sort_order'       => $request->int('sort_order'),
            'meta_title'       => $input['meta_title'] ?: null,
            'meta_description' => $input['meta_description'] ?: null,
        ];
    }
}
