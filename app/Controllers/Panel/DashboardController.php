<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Quote;

class DashboardController extends Controller
{
    public function index(Request $request): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $orders = (new Order())->forUser($uid);
        $invoices = (new Invoice())->forUser($uid);
        $tickets = (new Ticket())->forUser($uid);
        $quotes = (new Quote())->forUser($uid);

        $activeOrders = array_filter($orders, fn($o) => $o['status'] === 'paid');
        $openTickets = array_filter($tickets, fn($t) => $t['status'] !== 'closed');

        $this->render('panel/dashboard', [
            'pageTitle'    => 'Genel Bakis',
            'orders'       => array_slice($orders, 0, 5),
            'invoices'     => array_slice($invoices, 0, 5),
            'tickets'      => array_slice($tickets, 0, 5),
            'kpis'         => [
                'active_packages' => count($activeOrders),
                'invoices'        => count($invoices),
                'open_tickets'    => count($openTickets),
                'quotes'          => count($quotes),
            ],
        ], 'layouts/panel');
    }

    public function packages(Request $request): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $orders = (new Order())->forUser($uid);
        $this->render('panel/packages', [
            'pageTitle' => 'Paketlerim',
            'orders'    => $orders,
        ], 'layouts/panel');
    }
}
