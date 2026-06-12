<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Ticket;

class CustomerController extends AdminController
{
    public function index(Request $request): never
    {
        $q = $request->string('q');
        $customers = (new User())->search($q, 100);
        $this->view('admin/customers', [
            'pageTitle' => 'Musteriler',
            'customers' => $customers,
            'q'         => $q,
        ]);
    }

    public function show(Request $request, array $params): never
    {
        $user = (new User())->find((int) $params['id']);
        if (!$user) {
            $this->abort(404);
        }
        $orders = (new Order())->forUser((int) $user['id']);
        $invoices = (new Invoice())->forUser((int) $user['id']);
        $tickets = (new Ticket())->forUser((int) $user['id']);

        $this->view('admin/customer-show', [
            'pageTitle' => $user['name'],
            'customer'  => $user,
            'orders'    => $orders,
            'invoices'  => $invoices,
            'tickets'   => $tickets,
        ]);
    }

    public function toggleStatus(Request $request, array $params): never
    {
        $userModel = new User();
        $user = $userModel->find((int) $params['id']);
        if (!$user) {
            $this->abort(404);
        }
        $new = $user['status'] === 'active' ? 'passive' : 'active';
        $userModel->update((int) $user['id'], ['status' => $new]);
        $this->audit('customer.status', ['user_id' => $user['id'], 'status' => $new]);
        $this->withSuccess('Musteri durumu guncellendi: ' . $new);
        $this->redirect('/admin/musteriler/' . $user['id']);
    }
}
