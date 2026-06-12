<?php

namespace App\Controllers\Admin;

use App\Core\Request;

class ExportController extends AdminController
{
    public function csv(Request $request, array $params): never
    {
        $type = $params['type'];
        [$filename, $headers, $rows] = match ($type) {
            'musteriler' => $this->customers(),
            'siparisler' => $this->orders(),
            'faturalar'  => $this->invoices(),
            default      => $this->abort(404),
        };

        $this->audit('export.csv', ['type' => $type]);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fprintf($out, "\xEF\xBB\xBF"); // UTF-8 BOM (Excel uyumu)
        fputcsv($out, $headers, ';');
        foreach ($rows as $row) {
            fputcsv($out, $row, ';');
        }
        fclose($out);
        exit;
    }

    private function customers(): array
    {
        $rows = $this->db()->fetchAll('SELECT name, email, phone, company, city, status, created_at FROM users ORDER BY created_at DESC');
        return ['musteriler-' . date('Y-m-d') . '.csv', ['Ad', 'E-posta', 'Telefon', 'Firma', 'Sehir', 'Durum', 'Kayit'], $rows];
    }

    private function orders(): array
    {
        $rows = $this->db()->fetchAll(
            "SELECT o.merchant_oid, u.email, p.title, o.amount, o.currency, o.status, o.created_at, o.paid_at
             FROM orders o LEFT JOIN users u ON u.id=o.user_id LEFT JOIN packages p ON p.id=o.package_id
             ORDER BY o.created_at DESC"
        );
        return ['siparisler-' . date('Y-m-d') . '.csv', ['Siparis No', 'Musteri', 'Paket', 'Tutar', 'Para', 'Durum', 'Tarih', 'Odeme'], $rows];
    }

    private function invoices(): array
    {
        $rows = $this->db()->fetchAll(
            'SELECT i.invoice_no, u.email, i.amount, i.tax_amount, i.status, i.issued_at
             FROM invoices i LEFT JOIN users u ON u.id=i.user_id ORDER BY i.issued_at DESC'
        );
        return ['faturalar-' . date('Y-m-d') . '.csv', ['Fatura No', 'Musteri', 'Tutar', 'KDV', 'Durum', 'Tarih'], $rows];
    }
}
