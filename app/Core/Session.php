<?php

namespace App\Core;

/**
 * Guvenli oturum yonetimi + flash mesajlar.
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $cfg = App::get()->config['session'];

        session_name($cfg['name']);
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => (bool) $cfg['secure'],
            'httponly' => true,
            'samesite' => $cfg['samesite'],
        ]);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', (string) ($cfg['lifetime'] * 60));
        session_start();

        // Mutlak zaman asimi
        $now = time();
        if (isset($_SESSION['_created']) && ($now - $_SESSION['_created']) > $cfg['lifetime'] * 60) {
            self::destroy();
            session_start();
        }
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = $now;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    // --- Flash ---
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type][] = $message;
    }

    public static function getFlashes(): array
    {
        $flashes = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flashes;
    }

    // --- Eski form girdisi (validasyon icin) ---
    public static function flashInput(array $input): void
    {
        $_SESSION['_old'] = $input;
    }

    public static function old(string $key, string $default = ''): string
    {
        return (string) ($_SESSION['_old'][$key] ?? $default);
    }

    public static function clearOld(): void
    {
        unset($_SESSION['_old']);
    }
}
