<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\App;
use App\Core\Auth;
use App\Core\Database;
use App\Models\AuditLog;

/**
 * Admin controller'lari icin ortak taban.
 */
abstract class AdminController extends Controller
{
    protected function view(string $template, array $data = []): never
    {
        $this->render($template, $data, 'layouts/admin');
    }

    protected function db(): Database
    {
        return App::get()->db();
    }

    protected function audit(string $action, array $meta = []): void
    {
        (new AuditLog())->record('admin', Auth::id(Auth::GUARD_ADMIN), $action, $meta, $_SERVER['REMOTE_ADDR'] ?? '');
    }
}
