<?php

namespace App\Middleware;

use App\Core\App;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

/** Admin girisi + opsiyonel IP allowlist zorunlu. */
class AdminMiddleware
{
    public function handle(Request $request): void
    {
        // IP allowlist (bos ise herkese acik)
        $allow = App::config('admin.ip_allowlist', []);
        if (!empty($allow) && !in_array($request->ip(), $allow, true)) {
            Response::text('Erisim reddedildi.', 403);
        }

        if (!Auth::check(Auth::GUARD_ADMIN)) {
            Session::set('_admin_intended', $request->path);
            Response::redirect('/admin/giris');
        }
    }
}
