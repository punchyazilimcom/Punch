<?php

namespace App\Core;

/**
 * CSRF token uretimi ve dogrulama. Tum POST formlarinda zorunlu.
 */
class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function field(): string
    {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_csrf" value="' . $token . '">';
    }

    public static function verify(?string $token): bool
    {
        $stored = $_SESSION[self::KEY] ?? '';
        return is_string($token) && $stored !== '' && hash_equals($stored, $token);
    }

    public static function check(Request $request): void
    {
        if (in_array($request->method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $token = $request->input('_csrf') ?? ($request->server['HTTP_X_CSRF_TOKEN'] ?? null);
            if (!self::verify($token)) {
                Logger::warning('CSRF dogrulama basarisiz', ['ip' => $request->ip(), 'path' => $request->path]);
                Response::text('Gecersiz veya suresi dolmus oturum (CSRF). Lutfen sayfayi yenileyip tekrar deneyin.', 419);
            }
        }
    }
}
