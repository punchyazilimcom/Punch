<?php

namespace App\Services;

use App\Core\App;
use App\Core\Logger;
use App\Core\Mailer;
use App\Models\Order;
use App\Models\User;
use App\Models\Package;
use App\Models\AuditLog;

/**
 * Siparis yasam dongusu: odeme basarili/basarisiz isleme.
 */
class OrderService
{
    public function markPaid(array $order, array $paytrPost): void
    {
        $db = App::get()->db();
        $db->beginTransaction();
        try {
            (new Order())->update($order['id'], [
                'status'              => 'paid',
                'paid_at'             => date('Y-m-d H:i:s'),
                'paytr_response_json' => json_encode($paytrPost, JSON_UNESCAPED_UNICODE),
            ]);

            $user = (new User())->find((int) $order['user_id']);
            $invoice = (new InvoiceService())->createForOrder($order, $user);

            $db->commit();

            (new AuditLog())->record('system', null, 'order.paid', [
                'order_id' => $order['id'], 'merchant_oid' => $order['merchant_oid'],
            ]);

            // Mailler (transaction disinda)
            $package = $order['package_id'] ? (new Package())->find((int) $order['package_id']) : null;
            $this->sendPaidEmails($user, $order, $invoice, $package);
        } catch (\Throwable $e) {
            $db->rollBack();
            Logger::error('markPaid hatasi: ' . $e->getMessage(), ['order_id' => $order['id']]);
            throw $e;
        }
    }

    public function markFailed(array $order, array $paytrPost): void
    {
        (new Order())->update($order['id'], [
            'status'              => 'failed',
            'paytr_response_json' => json_encode($paytrPost, JSON_UNESCAPED_UNICODE),
        ]);
        (new AuditLog())->record('system', null, 'order.failed', [
            'order_id' => $order['id'],
            'reason'   => $paytrPost['failed_reason_msg'] ?? '',
        ]);
    }

    private function sendPaidEmails(array $user, array $order, array $invoice, ?array $package): void
    {
        $data = [
            'user'    => $user,
            'order'   => $order,
            'invoice' => $invoice,
            'package' => $package,
        ];
        Mailer::send($user['email'], 'Odemeniz Alindi — ' . $invoice['invoice_no'], 'payment-success', $data, $user['name']);
        Mailer::toAdmin('Yeni Odeme: ' . price_format($order['amount']) . ' — ' . $user['email'], 'admin-new-payment', $data);
    }
}
