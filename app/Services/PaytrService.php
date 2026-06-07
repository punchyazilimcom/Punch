<?php

namespace App\Services;

use App\Core\App;
use App\Core\Logger;

/**
 * PayTR iFrame API entegrasyonu (sunucu tarafli).
 * Dokuman: https://dev.paytr.com/iframe-api
 *
 * Anahtarlar daima .env'den okunur; frontend'e sizmaz.
 */
class PaytrService
{
    private array $cfg;

    public function __construct()
    {
        $this->cfg = App::config('paytr');
    }

    public function isConfigured(): bool
    {
        return $this->cfg['merchant_id'] && $this->cfg['merchant_key'] && $this->cfg['merchant_salt'];
    }

    /**
     * get-token isteminden iFrame token'i alir.
     *
     * @param array $params [user_ip, merchant_oid, email, amount(kurus), user_name, user_address, user_phone, basket(array), currency]
     * @return array{success:bool, token?:string, reason?:string}
     */
    public function getToken(array $params): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'reason' => 'PayTR anahtarlari yapilandirilmamis.'];
        }

        $merchantId   = $this->cfg['merchant_id'];
        $merchantKey  = $this->cfg['merchant_key'];
        $merchantSalt = $this->cfg['merchant_salt'];
        $testMode     = (string) $this->cfg['test_mode'];

        $userIp       = $params['user_ip'];
        $merchantOid  = $params['merchant_oid'];
        $email        = $params['email'];
        $paymentAmount= (int) $params['amount']; // kurus
        $currency     = $params['currency'] ?? 'TL';
        $noInstallment   = '0';
        $maxInstallment  = '0';

        // Sepet: [[urun_adi, fiyat(string), adet], ...] -> base64(json)
        $basket = $params['basket'] ?? [];
        $userBasket = base64_encode(json_encode($basket, JSON_UNESCAPED_UNICODE));

        // PayTR dokumanindaki birebir sira ile hash:
        // merchant_id + user_ip + merchant_oid + email + payment_amount +
        // user_basket + no_installment + max_installment + currency + test_mode
        $hashStr = $merchantId . $userIp . $merchantOid . $email . $paymentAmount
            . $userBasket . $noInstallment . $maxInstallment . $currency . $testMode;
        $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $merchantSalt, $merchantKey, true));

        $post = [
            'merchant_id'       => $merchantId,
            'user_ip'           => $userIp,
            'merchant_oid'      => $merchantOid,
            'email'             => $email,
            'payment_amount'    => $paymentAmount,
            'paytr_token'       => $paytrToken,
            'user_basket'       => $userBasket,
            'debug_on'          => App::config('app.debug') ? 1 : 0,
            'no_installment'    => $noInstallment,
            'max_installment'   => $maxInstallment,
            'user_name'         => $params['user_name'] ?? 'Musteri',
            'user_address'      => $params['user_address'] ?? '-',
            'user_phone'        => $params['user_phone'] ?? '-',
            'merchant_ok_url'   => $this->cfg['ok_url'],
            'merchant_fail_url' => $this->cfg['fail_url'],
            'timeout_limit'     => 30,
            'currency'          => $currency,
            'test_mode'         => $testMode,
            'lang'              => 'tr',
        ];

        $response = $this->httpPost('https://www.paytr.com/odeme/api/get-token', $post);

        if ($response === null) {
            return ['success' => false, 'reason' => 'PayTR sunucusuna ulasilamadi.'];
        }

        $result = json_decode($response, true);
        if (!is_array($result)) {
            Logger::error('PayTR get-token gecersiz yanit', ['raw' => substr((string) $response, 0, 500)]);
            return ['success' => false, 'reason' => 'PayTR yanitini cozumlenemedi.'];
        }

        if (($result['status'] ?? '') === 'success') {
            return ['success' => true, 'token' => $result['token']];
        }

        Logger::error('PayTR get-token basarisiz', ['reason' => $result['reason'] ?? 'bilinmiyor']);
        return ['success' => false, 'reason' => $result['reason'] ?? 'PayTR token alinamadi.'];
    }

    /**
     * Callback (bildirim) hash dogrulamasi.
     * hash = base64( hmac_sha256( merchant_oid + merchant_salt + status + total_amount, merchant_key ) )
     */
    public function verifyCallback(array $post): bool
    {
        $merchantKey  = $this->cfg['merchant_key'];
        $merchantSalt = $this->cfg['merchant_salt'];

        $merchantOid = $post['merchant_oid'] ?? '';
        $status      = $post['status'] ?? '';
        $totalAmount = $post['total_amount'] ?? '';
        $received    = $post['hash'] ?? '';

        $hashStr = $merchantOid . $merchantSalt . $status . $totalAmount;
        $calculated = base64_encode(hash_hmac('sha256', $hashStr, $merchantKey, true));

        return is_string($received) && $received !== '' && hash_equals($calculated, $received);
    }

    private function httpPost(string $url, array $data): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FRESH_CONNECT  => true,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            Logger::error('PayTR cURL hatasi: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return (string) $response;
    }

    public static function generateMerchantOid(int $userId): string
    {
        // Yalniz alfanumerik (PayTR kurali)
        return 'PNCH' . $userId . strtoupper(bin2hex(random_bytes(6)));
    }
}
