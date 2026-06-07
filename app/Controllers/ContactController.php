<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Core\Mailer;
use App\Core\RateLimiter;
use App\Models\ContactMessage;
use App\Services\Seo;

class ContactController extends Controller
{
    public function index(Request $request): never
    {
        $seo = Seo::build([
            'title'       => 'Iletisim — Punch Yazilim | Ankara',
            'description' => 'Projeniz icin bizimle iletisime gecin. Telefon, e-posta, WhatsApp ve iletisim formu.',
        ]);
        $jsonld = [Seo::localBusiness(), Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Iletisim' => base_url('iletisim')])];
        $this->render('pages/contact', compact('seo', 'jsonld'));
    }

    public function store(Request $request): never
    {
        // Rate limit: dakikada/IP basina sinirla
        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5, 600)) {
            $this->withError('Cok fazla deneme yaptiniz. Lutfen birazdan tekrar deneyin.');
            $this->back('/iletisim');
        }

        // Bal kupu (bot tuzagi)
        if ($request->string('website') !== '') {
            $this->withSuccess('Mesajiniz alindi. En kisa surede donus yapacagiz.');
            $this->redirect('/iletisim');
        }

        $data = $request->only(['name', 'email', 'phone', 'subject', 'message']);
        $v = new Validator($data, [
            'name' => 'Ad Soyad', 'email' => 'E-posta', 'message' => 'Mesaj',
        ]);
        if (!$v->validate([
            'name' => 'required|min:2|max:120',
            'email' => 'required|email',
            'phone' => 'phone',
            'subject' => 'max:160',
            'message' => 'required|min:10|max:3000',
        ])) {
            Session::flashInput($data);
            Session::set('_errors', $v->firstErrors());
            $this->withError('Lutfen formdaki hatalari duzeltin.');
            $this->back('/iletisim');
        }

        RateLimiter::hit($key, 600);

        (new ContactMessage())->create([
            'name'    => $data['name'],
            'email'   => strtolower($data['email']),
            'phone'   => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'ip'      => $request->ip(),
        ]);

        Mailer::toAdmin('Yeni Iletisim Mesaji: ' . ($data['subject'] ?: $data['name']), 'admin-contact', ['data' => $data]);

        Session::clearOld();
        $this->withSuccess('Mesajiniz alindi! En kisa surede size donus yapacagiz.');
        $this->redirect('/iletisim');
    }
}
