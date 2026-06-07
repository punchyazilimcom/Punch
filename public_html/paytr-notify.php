<?php
/**
 * PayTR Bildirim (Callback) URL'i.
 * PayTR odeme sonucunu buraya POST eder.
 *
 * KURALLAR:
 *  - hash mutlaka dogrulanir (sahte bildirim reddedilir).
 *  - islem idempotent: ayni merchant_oid icin tekrar islenmez.
 *  - YANIT olarak duz metin "OK" donmek ZORUNLUDUR (yoksa PayTR tekrar dener).
 *
 * PayTR panelinde Bildirim URL'i olarak bu dosya tam adresi girilmelidir:
 *   https://punchyazilim.com/paytr-notify.php
 */

declare(strict_types=1);

use App\Core\Logger;
use App\Services\PaytrService;
use App\Services\OrderService;
use App\Models\Order;

require dirname(__DIR__) . '/app/bootstrap.php';

// Sadece POST
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || empty($_POST['merchant_oid'])) {
    http_response_code(400);
    echo 'PAYTR notification expected';
    exit;
}

$post = $_POST;
$paytr = new PaytrService();

// 1) Hash dogrulama
if (!$paytr->verifyCallback($post)) {
    Logger::error('PayTR callback hash dogrulanamadi', ['oid' => $post['merchant_oid'] ?? '']);
    // PayTR'nin tekrar denememesi icin yine de OK donmuyoruz; 401 ile reddet.
    http_response_code(401);
    echo 'PAYTR notification failed: bad hash';
    exit;
}

$merchantOid = $post['merchant_oid'];
$status      = $post['status'] ?? '';

try {
    $orderModel = new Order();
    $order = $orderModel->byMerchantOid($merchantOid);

    if (!$order) {
        Logger::warning('PayTR callback: bilinmeyen merchant_oid', ['oid' => $merchantOid]);
        // Siparis yoksa da OK don (aksi halde surekli tekrar gelir)
        echo 'OK';
        exit;
    }

    // 2) Idempotency — zaten islenmis siparis
    if (in_array($order['status'], ['paid', 'failed', 'refunded'], true)) {
        echo 'OK';
        exit;
    }

    $service = new OrderService();

    if ($status === 'success') {
        $service->markPaid($order, $post);
    } else {
        $service->markFailed($order, $post);
    }
} catch (\Throwable $e) {
    Logger::error('PayTR callback isleme hatasi: ' . $e->getMessage(), ['oid' => $merchantOid]);
    // Hata olsa bile PayTR'ye OK don; tekrar deneme yerine logdan takip edilir.
}

// 3) ZORUNLU yanit
echo 'OK';
exit;
