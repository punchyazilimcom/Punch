<?php

namespace App\Services;

use App\Core\App;
use App\Core\Logger;
use App\Core\View;
use App\Models\Invoice;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Fatura olusturma + PDF uretimi (dompdf).
 */
class InvoiceService
{
    public function createForOrder(array $order, array $user): array
    {
        $invoiceModel = new Invoice();
        $no = $invoiceModel->nextNumber();

        $gross = (float) $order['amount'];          // KDV dahil tutar
        $taxRate = 20.0;
        $net = round($gross / (1 + $taxRate / 100), 2);
        $tax = round($gross - $net, 2);

        $id = $invoiceModel->create([
            'order_id'   => $order['id'],
            'user_id'    => $user['id'],
            'invoice_no' => $no,
            'title'      => 'Hizmet Bedeli',
            'amount'     => $gross,
            'tax_rate'   => $taxRate,
            'tax_amount' => $tax,
            'currency'   => $order['currency'] ?? 'TRY',
            'status'     => 'paid',
            'issued_at'  => date('Y-m-d H:i:s'),
        ]);

        $invoice = $invoiceModel->find($id);

        // PDF olustur
        try {
            $path = $this->generatePdf($invoice, $user, $net, $tax);
            if ($path) {
                $invoiceModel->update($id, ['pdf_path' => $path]);
                $invoice['pdf_path'] = $path;
            }
        } catch (\Throwable $e) {
            Logger::error('Fatura PDF uretilemedi: ' . $e->getMessage());
        }

        return $invoice;
    }

    /**
     * Faturayi PDF olarak uretir ve storage/invoices altina kaydeder. Yolu dondurur.
     */
    public function generatePdf(array $invoice, array $user, ?float $net = null, ?float $tax = null): ?string
    {
        if (!class_exists(Dompdf::class)) {
            Logger::warning('dompdf yok; PDF uretilemedi (composer install gerekli).');
            return null;
        }

        $tax = $tax ?? (float) $invoice['tax_amount'];
        $net = $net ?? ((float) $invoice['amount'] - $tax);

        $view = new View();
        $html = $view->render('pdf/invoice', [
            'invoice' => $invoice,
            'user'    => $user,
            'net'     => $net,
            'tax'     => $tax,
            'company' => App::config('company'),
        ], null);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans'); // Turkce karakter destegi
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dir = PUNCH_ROOT . '/storage/invoices';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $filename = $invoice['invoice_no'] . '.pdf';
        $fullPath = $dir . '/' . $filename;
        file_put_contents($fullPath, $dompdf->output());

        return 'invoices/' . $filename; // storage'a gore goreli
    }

    public function absolutePath(string $relative): string
    {
        return PUNCH_ROOT . '/storage/' . ltrim($relative, '/');
    }
}
