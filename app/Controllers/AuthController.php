<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Validator;
use App\Core\RateLimiter;
use App\Core\Mailer;
use App\Models\User;
use App\Services\Seo;

class AuthController extends Controller
{
    public function showLogin(Request $request): never
    {
        $this->render('auth/login', [
            'seo' => Seo::build(['title' => 'Giris Yap | Punch Yazilim', 'robots' => 'noindex, nofollow']),
        ], 'layouts/auth');
    }

    public function login(Request $request): never
    {
        $key = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 6, 600)) {
            $this->withError('Cok fazla basarisiz deneme. ' . RateLimiter::availableIn($key) . ' saniye sonra tekrar deneyin.');
            $this->back('/giris');
        }

        $email = $request->string('email');
        $password = (string) $request->input('password', '');

        $v = new Validator(['email' => $email, 'password' => $password], ['email' => 'E-posta', 'password' => 'Sifre']);
        if (!$v->validate(['email' => 'required|email', 'password' => 'required'])) {
            Session::flashInput(['email' => $email]);
            Session::set('_errors', $v->firstErrors());
            $this->back('/giris');
        }

        if (!Auth::attempt(Auth::GUARD_USER, $email, $password)) {
            RateLimiter::hit($key, 600);
            Session::flashInput(['email' => $email]);
            $this->withError('E-posta veya sifre hatali.');
            $this->back('/giris');
        }

        RateLimiter::clear($key);
        Session::clearOld();
        $intended = Session::get('_intended', '/panel');
        Session::forget('_intended');
        $this->redirect($intended);
    }

    public function showRegister(Request $request): never
    {
        $this->render('auth/register', [
            'seo' => Seo::build(['title' => 'Kayit Ol | Punch Yazilim', 'robots' => 'noindex, nofollow']),
        ], 'layouts/auth');
    }

    public function register(Request $request): never
    {
        $key = 'register:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5, 1800)) {
            $this->withError('Cok fazla kayit denemesi. Lutfen daha sonra tekrar deneyin.');
            $this->back('/kayit');
        }

        $data = $request->only(['name', 'email', 'phone', 'password', 'password_confirmation']);
        $v = new Validator($data, [
            'name' => 'Ad Soyad', 'email' => 'E-posta', 'password' => 'Sifre',
        ]);
        $ok = $v->validate([
            'name' => 'required|min:2|max:120',
            'email' => 'required|email|max:190',
            'phone' => 'phone',
            'password' => 'required|min:8|max:72|confirmed',
        ]);

        $userModel = new User();
        if ($ok && $userModel->emailExists($data['email'])) {
            Session::set('_errors', ['email' => 'Bu e-posta zaten kayitli.']);
            $ok = false;
        }
        if (!$request->bool('terms')) {
            $errs = Session::get('_errors', []);
            $errs['terms'] = 'Sozlesmeleri kabul etmelisiniz.';
            Session::set('_errors', $errs);
            $ok = false;
        }

        if (!$ok) {
            if (empty(Session::get('_errors'))) {
                Session::set('_errors', $v->firstErrors());
            } else {
                Session::set('_errors', array_merge($v->firstErrors(), Session::get('_errors')));
            }
            Session::flashInput($data);
            $this->withError('Lutfen formdaki hatalari duzeltin.');
            $this->back('/kayit');
        }

        RateLimiter::hit($key, 1800);

        $id = $userModel->create([
            'name'          => $data['name'],
            'email'         => strtolower($data['email']),
            'password_hash' => Auth::hash($data['password']),
            'phone'         => $data['phone'] ?? null,
            'status'        => 'active',
        ]);

        Mailer::send(strtolower($data['email']), 'Punch Yazilim - Hosgeldiniz', 'welcome', ['name' => $data['name']], $data['name']);

        Auth::login(Auth::GUARD_USER, $id);
        Session::clearOld();
        $this->withSuccess('Hesabiniz olusturuldu, hosgeldiniz!');
        $this->redirect('/panel');
    }

    public function logout(Request $request): never
    {
        Auth::logout(Auth::GUARD_USER);
        Session::destroy();
        $this->redirect('/');
    }

    public function showForgot(Request $request): never
    {
        $this->render('auth/forgot', [
            'seo' => Seo::build(['title' => 'Sifremi Unuttum | Punch Yazilim', 'robots' => 'noindex, nofollow']),
        ], 'layouts/auth');
    }

    public function forgot(Request $request): never
    {
        $email = strtolower($request->string('email'));
        $key = 'forgot:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5, 900)) {
            $this->withError('Cok fazla talep. Lutfen sonra tekrar deneyin.');
            $this->back('/sifremi-unuttum');
        }
        RateLimiter::hit($key, 900);

        $user = (new User())->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            \App\Core\App::get()->db()->insert('password_resets', [
                'email'      => $email,
                'token_hash' => hash('sha256', $token),
                'guard'      => 'user',
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
            ]);
            $link = base_url('sifre-sifirla/' . $token) . '?email=' . urlencode($email);
            Mailer::send($email, 'Sifre Sifirlama - Punch Yazilim', 'password-reset', ['name' => $user['name'], 'link' => $link], $user['name']);
        }
        // Kullanici sayimini onlemek icin her durumda ayni mesaj
        $this->withSuccess('Eger bu e-posta kayitliysa, sifirlama baglantisi gonderildi.');
        $this->redirect('/sifremi-unuttum');
    }

    public function showReset(Request $request, array $params): never
    {
        $this->render('auth/reset', [
            'token' => $params['token'],
            'email' => $request->string('email'),
            'seo' => Seo::build(['title' => 'Sifre Sifirla | Punch Yazilim', 'robots' => 'noindex, nofollow']),
        ], 'layouts/auth');
    }

    public function reset(Request $request): never
    {
        $email = strtolower($request->string('email'));
        $token = $request->string('token');
        $password = (string) $request->input('password', '');

        $v = new Validator(['password' => $password], ['password' => 'Sifre']);
        if (!$v->validate(['password' => 'required|min:8|max:72|confirmed'])) {
            Session::set('_errors', $v->firstErrors());
            $this->back('/sifre-sifirla/' . urlencode($token) . '?email=' . urlencode($email));
        }

        $db = \App\Core\App::get()->db();
        $row = $db->fetch(
            'SELECT * FROM password_resets WHERE email = :e AND token_hash = :t AND expires_at > NOW() ORDER BY id DESC LIMIT 1',
            ['e' => $email, 't' => hash('sha256', $token)]
        );
        if (!$row) {
            $this->withError('Sifirlama baglantisi gecersiz veya suresi dolmus.');
            $this->redirect('/sifremi-unuttum');
        }

        $user = (new User())->findByEmail($email);
        if ($user) {
            (new User())->update((int) $user['id'], ['password_hash' => Auth::hash($password)]);
            $db->delete('password_resets', 'email = :e', ['e' => $email]);
        }
        $this->withSuccess('Sifreniz guncellendi. Simdi giris yapabilirsiniz.');
        $this->redirect('/giris');
    }
}
