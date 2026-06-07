<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Invoice;

class DashboardController extends AdminController
{
    public function index(Request $request): never
    {
        $orderModel = new Order();
        $userModel = new User();

        $kpis = [
            'revenue_total' => $orderModel->totalRevenue(),
            'revenue_month' => $orderModel->revenueThisMonth(),
            'customers'     => $userModel->count(),
            'open_tickets'  => (new Ticket())->openCount(),
        ];

        // Son 6 ay gelir trendi
        $trend = $this->db()->fetchAll(
            "SELECT DATE_FORMAT(paid_at, '%Y-%m') AS ym, COALESCE(SUM(amount),0) AS total
             FROM orders WHERE status = 'paid' AND paid_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY ym ORDER BY ym ASC"
        );

        // Paket dagilimi
        $distribution = $this->db()->fetchAll(
            "SELECT p.title, COUNT(o.id) AS cnt
             FROM orders o JOIN packages p ON p.id = o.package_id
             WHERE o.status = 'paid' GROUP BY p.id ORDER BY cnt DESC LIMIT 5"
        );

        $recentOrders = $orderModel->recent(8);

        $this->view('admin/dashboard', [
            'pageTitle'    => 'Dashboard',
            'kpis'         => $kpis,
            'trend'        => $trend,
            'distribution' => $distribution,
            'recentOrders' => $recentOrders,
        ]);
    }
}
