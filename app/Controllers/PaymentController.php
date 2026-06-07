<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\RateLimiter;
use App\Models\Package;
use App\Models\Order;
use App\Services\PaytrService;
use App\Services\Seo;

class PaymentController extends Controller
{
    public function start(Request $request, array $params): never
    {
        $user = Auth::user(Auth::GUARD_USER);
        $package = (new Package())->bySlug($params['slug']);

        if (!$package || !$package['is_active']) {
            $this->abort(404);
        }
        if (!empty($package['is_quote_only']) || (float) $package['price'] <= 0) {
            $this->withError('Bu paket teklife ozeldir, dogrudan satin alinamaz.');
            $this->redirect('/paketler/' . $package['slug']);
        }

        // Rate limit
        $key = 'paytr:' . $user['id'];
        if (RateLimiter::tooManyAttempts($key, 8, 600)) {
            $this->withError('Cok fazla odeme denemesi. Lutfen birazdan tekrar deneyin.');
            $this->redirect('/paketler/' . $package['slug']);
        }
        RateLimiter::hit($key, 600);

        $paytr = new PaytrService();
        if (!$paytr->isConfigured()) {
            $this->withError('Odeme altyapisi henuz yapilandirilmamis. Lutfen bizimle iletisime gecin.');
            $this->redirect('/paketler/' . $package['slug']);
        }

        // Siparis olustur (pending)
        $orderModel = new Order();
        $merchantOid = PaytrService::generateMerchantOid((int) $user['id']);
        $amount = (float) $package['price'];

        $orderId = $orderModel->create([
            'user_id'      => $user['id'],
            'package_id'   => $package['id'],
            'merchant_oid' => $merchantOid,
            'amount'       => $amount,
            'currency'     => $package['currency'] ?: 'TRY',
            'status'       => 'pending',
        ]);

        $amountKurus = (int) round($amount * 100);
        $basket = [[$package['title'], number_format($amount, 2, '.', ''), 1]];

        $result = $paytr->getToken([
            'user_ip'      => $request->ip(),
            'merchant_oid' => $merchantOid,
            'email'        => $user['email'],
            'amount'       => $amountKurus,
            'user_name'    => $user['name'],
            'user_address' => $user['address'] ?: ($user['city'] ?: 'Turkiye'),
            'user_phone'   => $user['phone'] ?: '-',
            'basket'       => $basket,
            'currency'     => 'TL',
        ]);

        if (!$result['success']) {
            $orderModel->update($orderId, ['status' => 'failed']);
            $this->withError('Odeme baslatilamadi: ' . ($result['reason'] ?? 'bilinmeyen hata'));
            $this->redirect('/paketler/' . $package['slug']);
        }

        $this->render('pages/checkout', [
            'seo'     => Seo::build(['title' => 'Guvenli Odeme | Punch Yazilim', 'robots' => 'noindex, nofollow']),
            'token'   => $result['token'],
            'package' => $package,
            'amount'  => $amount,
        ]);
    }

    public function success(Request $request): never
    {
        $this->render('pages/payment-result', [
            'seo'     => Seo::build(['title' => 'Odeme Basarili | Punch Yazilim', 'robots' => 'noindex, nofollow']),
            'success' => true,
        ]);
    }

    public function fail(Request $request): never
    {
        $this->render('pages/payment-result', [
            'seo'     => Seo::build(['title' => 'Odeme Basarisiz | Punch Yazilim', 'robots' => 'noindex, nofollow']),
            'success' => false,
        ]);
    }
}
