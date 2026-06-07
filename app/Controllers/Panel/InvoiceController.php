<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function index(Request $request): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $invoices = (new Invoice())->forUser($uid);
        $this->render('panel/invoices', [
            'pageTitle' => 'Faturalarim',
            'invoices'  => $invoices,
        ], 'layouts/panel');
    }

    public function pdf(Request $request, array $params): never
    {
        $uid = Auth::id(Auth::GUARD_USER);
        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find((int) $params['id']);

        // Yetki: yalniz kendi faturasi
        if (!$invoice || (int) $invoice['user_id'] !== $uid) {
            $this->abort(404);
        }

        $service = new InvoiceService();
        $relative = $invoice['pdf_path'];

        // PDF yoksa yeniden uret
        if (!$relative || !is_file($service->absolutePath($relative))) {
            $user = Auth::user(Auth::GUARD_USER);
            $relative = $service->generatePdf($invoice, $user);
            if ($relative) {
                $invoiceModel->update((int) $invoice['id'], ['pdf_path' => $relative]);
            }
        }

        $path = $relative ? $service->absolutePath($relative) : null;
        if (!$path || !is_file($path)) {
            $this->withError('Fatura PDF olusturulamadi. Lutfen destek ile iletisime gecin.');
            $this->redirect('/panel/faturalar');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
