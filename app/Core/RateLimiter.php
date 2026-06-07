<?php

namespace App\Core;

/**
 * DB tabanli rate limiter (brute-force korumasi).
 * rate_limits tablosunu kullanir.
 */
class RateLimiter
{
    public static function tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        $db = App::get()->db();
        $now = time();
        $row = $db->fetch('SELECT attempts, reset_at FROM rate_limits WHERE rl_key = :k', ['k' => $key]);

        if ($row === null) {
            return false;
        }
        if ((int) $row['reset_at'] < $now) {
            $db->delete('rate_limits', 'rl_key = :k', ['k' => $key]);
            return false;
        }
        return (int) $row['attempts'] >= $maxAttempts;
    }

    public static function hit(string $key, int $decaySeconds): int
    {
        $db = App::get()->db();
        $now = time();
        $row = $db->fetch('SELECT attempts, reset_at FROM rate_limits WHERE rl_key = :k', ['k' => $key]);

        if ($row === null || (int) $row['reset_at'] < $now) {
            $db->query(
                'REPLACE INTO rate_limits (rl_key, attempts, reset_at) VALUES (:k, 1, :r)',
                ['k' => $key, 'r' => $now + $decaySeconds]
            );
            return 1;
        }

        $attempts = (int) $row['attempts'] + 1;
        $db->update('rate_limits', ['attempts' => $attempts], 'rl_key = :k', ['k' => $key]);
        return $attempts;
    }

    public static function clear(string $key): void
    {
        App::get()->db()->delete('rate_limits', 'rl_key = :k', ['k' => $key]);
    }

    public static function availableIn(string $key): int
    {
        $row = App::get()->db()->fetch('SELECT reset_at FROM rate_limits WHERE rl_key = :k', ['k' => $key]);
        return $row ? max(0, (int) $row['reset_at'] - time()) : 0;
    }
}
