<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Core\Auth;
use App\Core\Mailer;
use App\Core\RateLimiter;
use App\Models\Quote;
use App\Models\Package;

class QuoteRequestController extends Controller
{
    public function store(Request $request): never
    {
        $key = 'quote:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5, 600)) {
            $this->withError('Cok fazla talep gonderdiniz. Lutfen birazdan tekrar deneyin.');
            $this->back('/paketler');
        }

        if ($request->string('website') !== '') {
            $this->withSuccess('Teklif talebiniz alindi.');
            $this->redirect('/paketler');
        }

        $data = $request->only(['name', 'email', 'phone', 'message', 'package_slug']);
        $v = new Validator($data, ['name' => 'Ad Soyad', 'email' => 'E-posta', 'message' => 'Mesaj']);
        if (!$v->validate([
            'name' => 'required|min:2|max:120',
            'email' => 'required|email',
            'phone' => 'phone',
            'message' => 'required|min:10|max:2000',
        ])) {
            Session::flashInput($data);
            Session::set('_errors', $v->firstErrors());
            $this->withError('Lutfen formu eksiksiz doldurun.');
            $this->back('/paketler');
        }

        RateLimiter::hit($key, 600);

        $packageId = null;
        if (!empty($data['package_slug'])) {
            $pkg = (new Package())->bySlug($data['package_slug']);
            $packageId = $pkg['id'] ?? null;
        }

        (new Quote())->create([
            'user_id'    => Auth::id(Auth::GUARD_USER),
            'package_id' => $packageId,
            'name'       => $data['name'],
            'email'      => strtolower($data['email']),
            'phone'      => $data['phone'] ?? null,
            'message'    => $data['message'],
            'status'     => 'new',
        ]);

        Mailer::toAdmin('Yeni Teklif Talebi: ' . $data['name'], 'admin-quote', ['data' => $data]);

        $this->withSuccess('Teklif talebiniz alindi! Ekibimiz en kisa surede size ozel teklifle donus yapacak.');
        $this->back('/paketler');
    }
}
