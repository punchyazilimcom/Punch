<?php
/**
 * Merkezi yapilandirma. Tum degerler .env'den okunur (config/.env).
 * Hicbir gizli anahtar bu dosyada saklanmaz.
 */

use App\Core\Env;

$root = dirname(__DIR__);

// .env yukle (composer phpdotenv varsa onu, yoksa basit fallback parser)
Env::load($root . '/.env');

return [
    'root'      => $root,
    'app' => [
        'name'     => Env::get('APP_NAME', 'Punch Yazilim'),
        'env'      => Env::get('APP_ENV', 'production'),
        'debug'    => Env::bool('APP_DEBUG', false),
        'url'      => rtrim(Env::get('APP_URL', 'http://localhost'), '/'),
        'timezone' => Env::get('APP_TIMEZONE', 'Europe/Istanbul'),
        'locale'   => Env::get('APP_LOCALE', 'tr'),
        'key'      => Env::get('APP_KEY', ''),
        'asset_version' => Env::get('ASSET_VERSION', '1.0.0'),
    ],
    'db' => [
        'host'    => Env::get('DB_HOST', 'localhost'),
        'port'    => Env::get('DB_PORT', '3306'),
        'name'    => Env::get('DB_NAME', ''),
        'user'    => Env::get('DB_USER', ''),
        'pass'    => Env::get('DB_PASS', ''),
        'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
    ],
    'session' => [
        'name'     => Env::get('SESSION_NAME', 'punch_session'),
        'lifetime' => (int) Env::get('SESSION_LIFETIME', 120),
        'secure'   => Env::bool('COOKIE_SECURE', true),
        'samesite' => Env::get('COOKIE_SAMESITE', 'Lax'),
    ],
    'admin' => [
        'ip_allowlist' => array_filter(array_map('trim', explode(',', (string) Env::get('ADMIN_IP_ALLOWLIST', '')))),
    ],
    'mail' => [
        'host'       => Env::get('MAIL_HOST', ''),
        'port'       => (int) Env::get('MAIL_PORT', 465),
        'encryption' => Env::get('MAIL_ENCRYPTION', 'ssl'),
        'username'   => Env::get('MAIL_USERNAME', ''),
        'password'   => Env::get('MAIL_PASSWORD', ''),
        'from_addr'  => Env::get('MAIL_FROM_ADDRESS', 'destek@punchyazilim.com'),
        'from_name'  => Env::get('MAIL_FROM_NAME', 'Punch Yazilim'),
        'admin_addr' => Env::get('MAIL_ADMIN_ADDRESS', 'destek@punchyazilim.com'),
    ],
    'paytr' => [
        'merchant_id'   => Env::get('PAYTR_MERCHANT_ID', ''),
        'merchant_key'  => Env::get('PAYTR_MERCHANT_KEY', ''),
        'merchant_salt' => Env::get('PAYTR_MERCHANT_SALT', ''),
        'test_mode'     => (int) Env::get('PAYTR_TEST_MODE', 1),
        'notify_url'    => Env::get('PAYTR_NOTIFY_URL', ''),
        'ok_url'        => Env::get('PAYTR_OK_URL', ''),
        'fail_url'      => Env::get('PAYTR_FAIL_URL', ''),
    ],
    'analytics' => [
        'ga4'                => Env::get('GA4_MEASUREMENT_ID', ''),
        'site_verification'  => Env::get('GOOGLE_SITE_VERIFICATION', ''),
    ],
    'company' => [
        'name'     => Env::get('COMPANY_NAME', 'Punch Yazilim'),
        'phone'    => Env::get('COMPANY_PHONE', ''),
        'whatsapp' => Env::get('COMPANY_WHATSAPP', ''),
        'email'    => Env::get('COMPANY_EMAIL', 'destek@punchyazilim.com'),
        'address'  => Env::get('COMPANY_ADDRESS', 'Yenimahalle, Ankara'),
        'city'     => Env::get('COMPANY_CITY', 'Ankara'),
    ],
];
