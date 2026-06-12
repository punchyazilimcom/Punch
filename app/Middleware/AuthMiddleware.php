<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

/** Musteri girisi zorunlu. */
class AuthMiddleware
{
    public function handle(Request $request): void
    {
        // Ozel alan: indekslenmesin ve onbellekte tutulmasin
        if (!headers_sent()) {
            header('X-Robots-Tag: noindex, nofollow');
            header('Cache-Control: no-store, max-age=0');
        }
        if (!Auth::check(Auth::GUARD_USER)) {
            Session::set('_intended', $request->path);
            Session::flash('error', 'Bu sayfayi gormek icin giris yapmalisiniz.');
            Response::redirect('/giris');
        }
    }
}
