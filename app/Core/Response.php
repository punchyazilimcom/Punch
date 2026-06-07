<?php

namespace App\Core;

/**
 * HTTP yanit yardimcilari.
 */
class Response
{
    public static function html(string $body, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo $body;
        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function text(string $body, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: text/plain; charset=utf-8');
        echo $body;
        exit;
    }

    public static function redirect(string $to, int $status = 302): never
    {
        http_response_code($status);
        header('Location: ' . $to);
        exit;
    }

    public static function back(string $fallback = '/'): never
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? $fallback;
        self::redirect($ref);
    }

    /**
     * Guvenlik basliklarini gonderir. CSP, PayTR iFrame ve GSAP/Lenis CDN'ine izin verir.
     */
    public static function securityHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(self "https://www.paytr.com")');
        header('X-XSS-Protection: 0');

        $self = "'self'";
        $csp = [
            "default-src {$self}",
            "base-uri {$self}",
            "object-src 'none'",
            "frame-ancestors {$self}",
            // GSAP, Lenis, Splitting CDN'leri + inline init (nonce yerine pragmatik)
            "script-src {$self} 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://www.googletagmanager.com https://www.google-analytics.com",
            "style-src {$self} 'unsafe-inline' https://fonts.googleapis.com https://api.fontshare.com",
            "font-src {$self} https://fonts.gstatic.com https://api.fontshare.com data:",
            "img-src {$self} data: https: blob:",
            "connect-src {$self} https://www.google-analytics.com https://region1.google-analytics.com",
            // PayTR odeme iFrame'i
            "frame-src {$self} https://www.paytr.com",
            "form-action {$self} https://www.paytr.com",
        ];
        header('Content-Security-Policy: ' . implode('; ', $csp));
    }
}
