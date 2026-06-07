<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Session;
use App\Core\RateLimiter;
use App\Core\App;
use App\Models\Admin;
use App\Models\AuditLog;
use App\Services\Seo;

class AuthController extends Controller
{
    public function showLogin(Request $request): never
    {
        if (Auth::check(Auth::GUARD_ADMIN)) {
            $this->redirect('/admin');
        }
        // IP allowlist erken kontrol (bilgi amacli engelleme)
        $allow = App::config('admin.ip_allowlist', []);
        if (!empty($allow) && !in_array($request->ip(), $allow, true)) {
            $this->abort(403, 'Bu IP adresinden admin paneline erisim engellenmistir.');
        }
        $this->render('admin/login', [
            'seo' => Seo::build(['title' => 'Admin Giris', 'robots' => 'noindex, nofollow']),
        ], 'layouts/admin-auth');
    }

    public function login(Request $request): never
    {
        $key = 'admin_login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5, 900)) {
            $this->withError('Cok fazla basarisiz deneme. ' . RateLimiter::availableIn($key) . ' saniye bekleyin.');
            $this->back('/admin/giris');
        }

        $email = $request->string('email');
        $password = (string) $request->input('password', '');

        if (!Auth::attempt(Auth::GUARD_ADMIN, $email, $password)) {
            RateLimiter::hit($key, 900);
            (new AuditLog())->record('admin', null, 'admin.login_failed', ['email' => $email], $request->ip());
            Session::flashInput(['email' => $email]);
            $this->withError('E-posta veya sifre hatali.');
            $this->back('/admin/giris');
        }

        RateLimiter::clear($key);
        $admin = Auth::user(Auth::GUARD_ADMIN);
        (new Admin())->update((int) $admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
        (new AuditLog())->record('admin', (int) $admin['id'], 'admin.login', [], $request->ip());

        Session::clearOld();
        $intended = Session::get('_admin_intended', '/admin');
        Session::forget('_admin_intended');
        $this->redirect($intended);
    }

    public function logout(Request $request): never
    {
        Auth::logout(Auth::GUARD_ADMIN);
        $this->redirect('/admin/giris');
    }
}
