<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\AuditLog;

class AuditController extends AdminController
{
    public function index(Request $request): never
    {
        $logs = (new AuditLog())->recent(200);
        $this->view('admin/audit', ['pageTitle' => 'Denetim Kayitlari', 'logs' => $logs]);
    }
}
