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
        if (!Auth::check(Auth::GUARD_USER)) {
            Session::set('_intended', $request->path);
            Session::flash('error', 'Bu sayfayi gormek icin giris yapmalisiniz.');
            Response::redirect('/giris');
        }
    }
}
