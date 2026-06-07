<?php

namespace App\Models;

use App\Core\App;

/**
 * key/value ayar deposu. Site metinleri, sosyal linkler, SEO dogrulama vb.
 * Istek basina cache'lenir.
 */
class Setting extends Model
{
    protected string $table = 'settings';
    private static ?array $cache = null;

    private function loadAll(): array
    {
        if (self::$cache === null) {
            self::$cache = [];
            try {
                $rows = $this->db()->fetchAll('SELECT setting_key, setting_value FROM settings');
                foreach ($rows as $r) {
                    self::$cache[$r['setting_key']] = $r['setting_value'];
                }
            } catch (\Throwable $e) {
                self::$cache = [];
            }
        }
        return self::$cache;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $all = $this->loadAll();
        return $all[$key] ?? $default;
    }

    public function getJson(string $key, array $default = []): array
    {
        $v = $this->get($key);
        if (!$v) {
            return $default;
        }
        $decoded = json_decode($v, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public function set(string $key, string $value): void
    {
        $this->db()->query(
            'INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = :v2',
            ['k' => $key, 'v' => $value, 'v2' => $value]
        );
        self::$cache = null;
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $k => $v) {
            $this->set($k, is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (string) $v);
        }
    }

    public function all(string $orderBy = 'setting_key ASC'): array
    {
        return $this->loadAll();
    }
}
