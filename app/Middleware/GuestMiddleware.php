<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

/** Zaten giris yapmis musteriyi panele yonlendirir. */
class GuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::check(Auth::GUARD_USER)) {
            Response::redirect('/panel');
        }
    }
}
