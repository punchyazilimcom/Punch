<?php

use App\Core\App;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Setting;

if (!function_exists('csp_nonce')) {
    /** Mevcut istegin CSP nonce'u (inline <script nonce="..."> icin). */
    function csp_nonce(): string
    {
        return \App\Core\Response::nonce();
    }
}

if (!function_exists('setting')) {
    /** Veritabani key/value ayarindan deger okur (cache'li). */
    function setting(string $key, ?string $default = null): ?string
    {
        static $model = null;
        if ($model === null) {
            $model = new Setting();
        }
        try {
            return $model->get($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('setting_json')) {
    function setting_json(string $key, array $default = []): array
    {
        static $model = null;
        if ($model === null) {
            $model = new Setting();
        }
        try {
            return $model->getJson($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('status_badge')) {
    /** Durum kodunu renkli rozet + Turkce etikete cevirir. */
    function status_badge(string $status): string
    {
        $map = [
            'paid'     => ['badge-success', 'Odendi'],
            'pending'  => ['badge-warning', 'Bekliyor'],
            'failed'   => ['badge-danger', 'Basarisiz'],
            'refunded' => ['badge-muted', 'Iade'],
            'unpaid'   => ['badge-warning', 'Odenmedi'],
            'open'     => ['badge-info', 'Acik'],
            'answered' => ['badge-success', 'Yanitlandi'],
            'closed'   => ['badge-muted', 'Kapali'],
            'new'      => ['badge-info', 'Yeni'],
            'quoted'   => ['badge-warning', 'Teklif Verildi'],
            'accepted' => ['badge-success', 'Kabul Edildi'],
            'rejected' => ['badge-danger', 'Reddedildi'],
            'active'   => ['badge-success', 'Aktif'],
            'passive'  => ['badge-muted', 'Pasif'],
            'draft'    => ['badge-muted', 'Taslak'],
            'published'=> ['badge-success', 'Yayinda'],
            'scheduled'=> ['badge-warning', 'Zamanlanmis'],
            'high'     => ['badge-danger', 'Yuksek'],
            'normal'   => ['badge-info', 'Normal'],
            'low'      => ['badge-muted', 'Dusuk'],
        ];
        [$class, $label] = $map[$status] ?? ['badge-muted', $status];
        return '<span class="badge ' . $class . '">' . e($label) . '</span>';
    }
}

if (!function_exists('field_error')) {
    /** Validasyon hatasini gosterir (ilk erisimde session'dan alip temizler). */
    function field_error(string $key): string
    {
        static $errors = null;
        if ($errors === null) {
            $errors = $_SESSION['_errors'] ?? [];
            unset($_SESSION['_errors']);
        }
        if (empty($errors[$key])) {
            return '';
        }
        return '<span class="form-error">' . e($errors[$key]) . '</span>';
    }
}

if (!function_exists('has_error')) {
    function has_error(string $key): string
    {
        $errors = $_SESSION['_errors'] ?? [];
        return isset($errors[$key]) ? ' input-invalid' : '';
    }
}

if (!function_exists('is_active_path')) {
    /** Mevcut yol verilen prefix ile basliyorsa aria-current dondurur. */
    function is_active_path(string $prefix): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if ($prefix === '/') {
            return $path === '/' ? ' aria-current="page"' : '';
        }
        return str_starts_with($path, $prefix) ? ' aria-current="page"' : '';
    }
}

if (!function_exists('e')) {
    /** HTML kacisi (XSS korumasi) */
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return App::config($key, $default);
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim((string) config('app.url'), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** Versiyonlu asset URL'i (cache-busting) */
    function asset(string $path): string
    {
        $v = config('app.asset_version', '1');
        $sep = str_contains($path, '?') ? '&' : '?';
        return '/assets/' . ltrim($path, '/') . $sep . 'v=' . $v;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        return e(Session::old($key, $default));
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
    }
}

if (!function_exists('price_format')) {
    /** Fiyati Turk Lirasi formatinda gosterir. */
    function price_format(float|int|string $amount, string $currency = 'TRY'): string
    {
        $symbols = ['TRY' => '₺', 'USD' => '$', 'EUR' => '€'];
        $sym = $symbols[$currency] ?? $currency . ' ';
        return number_format((float) $amount, 2, ',', '.') . ' ' . $sym;
    }
}

if (!function_exists('str_excerpt')) {
    function str_excerpt(string $text, int $length = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length - 1) . '…';
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $tr = ['ç','Ç','ğ','Ğ','ı','İ','ö','Ö','ş','Ş','ü','Ü'];
        $en = ['c','c','g','g','i','i','o','o','s','s','u','u'];
        $text = str_replace($tr, $en, $text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}

if (!function_exists('reading_time')) {
    function reading_time(string $content): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / 200));
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'd.m.Y'): string
    {
        if (!$date) {
            return '';
        }
        $ts = strtotime($date);
        return $ts ? date($format, $ts) : '';
    }
}

if (!function_exists('old_select')) {
    function old_select(string $key, string $value, string $default = ''): string
    {
        $current = Session::old($key, $default);
        return $current === $value ? ' selected' : '';
    }
}
