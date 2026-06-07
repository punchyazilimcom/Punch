<?php

namespace App\Core;

/**
 * HTTP istek sarmalayici.
 */
class Request
{
    public string $method;
    public string $path;
    public array $query;
    public array $post;
    public array $files;
    public array $server;
    public array $cookies;

    public function __construct()
    {
        $this->method  = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->query   = $_GET;
        $this->post    = $_POST;
        $this->files   = $_FILES;
        $this->server  = $_SERVER;
        $this->cookies = $_COOKIE;

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rawurldecode($path);
        // trailing slash normalizasyonu (kok haric)
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        $this->path = $path === '' ? '/' : $path;

        // method override (form _method)
        if ($this->method === 'POST' && isset($this->post['_method'])) {
            $override = strtoupper($this->post['_method']);
            if (in_array($override, ['PUT', 'PATCH', 'DELETE'], true)) {
                $this->method = $override;
            }
        }
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $v = $this->input($key, $default);
        return is_string($v) ? trim($v) : $default;
    }

    public function int(string $key, int $default = 0): int
    {
        return (int) $this->input($key, $default);
    }

    public function bool(string $key): bool
    {
        $v = $this->input($key);
        return in_array($v, ['1', 'true', 'on', 'yes', true, 1], true);
    }

    public function only(array $keys): array
    {
        $out = [];
        foreach ($keys as $k) {
            $out[$k] = $this->input($k);
        }
        return $out;
    }

    public function file(string $key): ?array
    {
        $f = $this->files[$key] ?? null;
        if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        return $f;
    }

    public function isPost(): bool { return $this->method === 'POST'; }

    public function ip(): string
    {
        // Hostinger/proxy arkasinda dogru IP
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $h) {
            if (!empty($this->server[$h])) {
                $ip = explode(',', $this->server[$h])[0];
                return trim($ip);
            }
        }
        return '0.0.0.0';
    }

    public function userAgent(): string
    {
        return substr((string)($this->server['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    public function wantsJson(): bool
    {
        $accept = $this->server['HTTP_ACCEPT'] ?? '';
        $xhr = $this->server['HTTP_X_REQUESTED_WITH'] ?? '';
        return str_contains($accept, 'application/json') || strtolower($xhr) === 'xmlhttprequest';
    }
}
