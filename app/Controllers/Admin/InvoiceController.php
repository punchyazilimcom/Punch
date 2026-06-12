<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Validator;
use App\Core\Session;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceService;

class InvoiceController extends AdminController
{
    public function index(Request $request): never
    {
        $invoices = $this->db()->fetchAll(
            'SELECT i.*, u.name AS user_name, u.email AS user_email
             FROM invoices i LEFT JOIN users u ON u.id = i.user_id
             ORDER BY i.issued_at DESC LIMIT 200'
        );
        $this->view('admin/invoices', ['pageTitle' => 'Faturalar', 'invoices' => $invoices]);
    }

    public function create(Request $request): never
    {
        $users = $this->db()->fetchAll('SELECT id, name, email FROM users ORDER BY name ASC LIMIT 500');
        $this->view('admin/invoice-form', ['pageTitle' => 'Yeni Fatura', 'users' => $users]);
    }

    public function store(Request $request): never
    {
        $input = $request->only(['user_id', 'title', 'amount', 'tax_rate', 'status']);
        $v = new Validator($input, ['user_id' => 'Musteri', 'amount' => 'Tutar']);
        if (!$v->validate(['user_id' => 'required|numeric', 'amount' => 'required|numeric'])) {
            Session::flashInput($input);
            Session::set('_errors', $v->firstErrors());
            $this->back('/admin/faturalar/yeni');
        }

        $user = (new User())->find((int) $input['user_id']);
        if (!$user) {
            $this->withError('Musteri bulunamadi.');
            $this->back('/admin/faturalar/yeni');
        }

        $invoiceModel = new Invoice();
        $gross = (float) str_replace(',', '.', (string) $input['amount']);
        $taxRate = (float) ($input['tax_rate'] ?: 20);
        $net = round($gross / (1 + $taxRate / 100), 2);
        $tax = round($gross - $net, 2);

        $id = $invoiceModel->create([
            'user_id'    => (int) $user['id'],
            'invoice_no' => $invoiceModel->nextNumber(),
            'title'      => $input['title'] ?: 'Hizmet Bedeli',
            'amount'     => $gross,
            'tax_rate'   => $taxRate,
            'tax_amount' => $tax,
            'currency'   => 'TRY',
            'status'     => in_array($input['status'], ['unpaid', 'paid'], true) ? $input['status'] : 'unpaid',
            'issued_at'  => date('Y-m-d H:i:s'),
        ]);

        // PDF uret
        $invoice = $invoiceModel->find($id);
        $path = (new InvoiceService())->generatePdf($invoice, $user, $net, $tax);
        if ($path) {
            $invoiceModel->update($id, ['pdf_path' => $path]);
        }

        $this->audit('invoice.create', ['id' => $id]);
        $this->withSuccess('Fatura olusturuldu.');
        $this->redirect('/admin/faturalar');
    }

    public function pdf(Request $request, array $params): never
    {
        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find((int) $params['id']);
        if (!$invoice) {
            $this->abort(404);
        }
        $service = new InvoiceService();
        $relative = $invoice['pdf_path'];
        if (!$relative || !is_file($service->absolutePath($relative))) {
            $user = (new User())->find((int) $invoice['user_id']);
            $relative = $service->generatePdf($invoice, $user);
            if ($relative) {
                $invoiceModel->update((int) $invoice['id'], ['pdf_path' => $relative]);
            }
        }
        $path = $relative ? $service->absolutePath($relative) : null;
        if (!$path || !is_file($path)) {
            $this->withError('PDF olusturulamadi.');
            $this->redirect('/admin/faturalar');
        }
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }
}
