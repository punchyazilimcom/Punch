<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Mailer;
use App\Models\Quote;

class QuoteController extends AdminController
{
    public function index(Request $request): never
    {
        $quotes = $this->db()->fetchAll(
            'SELECT q.*, p.title AS package_title FROM quotes q
             LEFT JOIN packages p ON p.id = q.package_id
             ORDER BY (q.status = "new") DESC, q.created_at DESC LIMIT 200'
        );
        $this->view('admin/quotes', ['pageTitle' => 'Teklif Talepleri', 'quotes' => $quotes]);
    }

    public function update(Request $request, array $params): never
    {
        $quoteModel = new Quote();
        $quote = $quoteModel->find((int) $params['id']);
        if (!$quote) {
            $this->abort(404);
        }

        $price = $request->string('quoted_price');
        $note = $request->string('admin_note');
        $status = $request->string('status');

        $update = [
            'quoted_price' => $price !== '' ? (float) str_replace(',', '.', $price) : null,
            'admin_note'   => $note ?: null,
        ];
        if (in_array($status, ['new', 'quoted', 'accepted', 'rejected'], true)) {
            $update['status'] = $status;
        } elseif ($price !== '') {
            $update['status'] = 'quoted';
        }

        $quoteModel->update((int) $quote['id'], $update);
        $this->audit('quote.update', ['quote_id' => $quote['id'], 'status' => $update['status'] ?? $quote['status']]);

        // Musteriye teklif maili
        if (($update['status'] ?? '') === 'quoted' && !empty($quote['email'])) {
            Mailer::send($quote['email'], 'Size Ozel Teklifiniz Hazir — Punch Yazilim', 'ticket-update', [
                'heading' => 'Teklifiniz Hazir',
                'body'    => 'Talebinize ozel teklifimiz: ' . ($update['quoted_price'] ? price_format($update['quoted_price']) : 'detaylar icin panelinizi ziyaret edin') . '. ' . $note,
                'link'    => base_url('panel/teklifler'),
            ], $quote['name']);
        }

        $this->withSuccess('Teklif guncellendi.');
        $this->redirect('/admin/teklifler');
    }
}
