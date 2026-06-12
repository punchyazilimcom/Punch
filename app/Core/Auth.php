<?php

namespace App\Core;

use App\Models\User;
use App\Models\Admin;

/**
 * Iki ayri guard: 'user' (musteri) ve 'admin'.
 * Session tabanli; Argon2id parola dogrulama.
 */
class Auth
{
    public const GUARD_USER  = 'user';
    public const GUARD_ADMIN = 'admin';

    private static array $cache = [];

    public static function attempt(string $guard, string $email, string $password): bool
    {
        $record = $guard === self::GUARD_ADMIN
            ? (new Admin())->findByEmail($email)
            : (new User())->findByEmail($email);

        if (!$record || empty($record['password_hash'])) {
            // Zamanlama saldirisina karsi sahte hash dogrula
            password_verify($password, '$argon2id$v=19$m=65536,t=4,p=1$' . base64_encode(random_bytes(16)) . '$' . base64_encode(random_bytes(32)));
            return false;
        }

        if ($guard === self::GUARD_USER && ($record['status'] ?? 'active') !== 'active') {
            return false;
        }

        if (!password_verify($password, $record['password_hash'])) {
            return false;
        }

        // Rehash gerekiyorsa guncelle
        if (password_needs_rehash($record['password_hash'], PASSWORD_ARGON2ID)) {
            $newHash = password_hash($password, PASSWORD_ARGON2ID);
            $table = $guard === self::GUARD_ADMIN ? 'admins' : 'users';
            App::get()->db()->update($table, ['password_hash' => $newHash], 'id = :id', ['id' => $record['id']]);
        }

        self::login($guard, (int) $record['id']);
        return true;
    }

    public static function login(string $guard, int $id): void
    {
        Session::regenerate();
        $_SESSION['auth'][$guard] = $id;
        self::$cache[$guard] = null;
    }

    public static function logout(string $guard): void
    {
        unset($_SESSION['auth'][$guard]);
        self::$cache[$guard] = null;
    }

    public static function id(string $guard): ?int
    {
        return isset($_SESSION['auth'][$guard]) ? (int) $_SESSION['auth'][$guard] : null;
    }

    public static function check(string $guard): bool
    {
        return self::id($guard) !== null;
    }

    public static function user(string $guard = self::GUARD_USER): ?array
    {
        $id = self::id($guard);
        if ($id === null) {
            return null;
        }
        if (!array_key_exists($guard, self::$cache) || self::$cache[$guard] === null) {
            self::$cache[$guard] = $guard === self::GUARD_ADMIN
                ? (new Admin())->find($id)
                : (new User())->find($id);
        }
        return self::$cache[$guard];
    }

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }
}
