<?php

namespace App\Core;

/**
 * Basit dosya tabanli loglayici. storage/logs/app.log
 */
class Logger
{
    private static function path(): string
    {
        $dir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        return $dir . '/app.log';
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        $line = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
        );
        @file_put_contents(self::path(), $line, FILE_APPEND | LOCK_EX);
    }

    public static function error(string $message, array $context = []): void { self::log('error', $message, $context); }
    public static function warning(string $message, array $context = []): void { self::log('warning', $message, $context); }
    public static function info(string $message, array $context = []): void { self::log('info', $message, $context); }
}
