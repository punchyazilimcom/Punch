<?php

namespace App\Controllers\Admin;

use App\Core\Request;

class OrderController extends AdminController
{
    public function index(Request $request): never
    {
        $status = $request->string('status');
        $where = '1';
        $params = [];
        if (in_array($status, ['pending', 'paid', 'failed', 'refunded'], true)) {
            $where = 'o.status = :s';
            $params['s'] = $status;
        }
        $orders = $this->db()->fetchAll(
            "SELECT o.*, u.name AS user_name, u.email AS user_email, p.title AS package_title
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             LEFT JOIN packages p ON p.id = o.package_id
             WHERE {$where} ORDER BY o.created_at DESC LIMIT 200",
            $params
        );
        $this->view('admin/orders', [
            'pageTitle' => 'Siparisler & Odemeler',
            'orders'    => $orders,
            'status'    => $status,
        ]);
    }
}
