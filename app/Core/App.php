<?php

namespace App\Core;

/**
 * Cok hafif servis konteyneri / uygulama cekirdegi.
 * Global durumu (config, DB, mevcut request) tutar.
 */
class App
{
    private static ?App $instance = null;

    public array $config = [];
    private ?Database $db = null;
    private array $shared = [];

    public static function boot(array $config): App
    {
        $app = new self();
        $app->config = $config;
        self::$instance = $app;

        date_default_timezone_set($config['app']['timezone'] ?? 'Europe/Istanbul');

        // Hata gosterimi ortama gore
        if (!empty($config['app']['debug'])) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
            ini_set('display_errors', '0');
        }

        return $app;
    }

    public static function get(): App
    {
        if (self::$instance === null) {
            throw new \RuntimeException('App henuz boot edilmedi.');
        }
        return self::$instance;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::get()->config;
        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
        return $value;
    }

    public function db(): Database
    {
        if ($this->db === null) {
            $this->db = new Database($this->config['db']);
        }
        return $this->db;
    }

    public function set(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    public function shared(string $key, mixed $default = null): mixed
    {
        return $this->shared[$key] ?? $default;
    }
}
