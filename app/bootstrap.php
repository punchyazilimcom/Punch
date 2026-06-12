<?php
/**
 * Uygulama bootstrap. Front controller (public_html/index.php) ve
 * paytr-notify.php tarafindan dahil edilir.
 *
 * Composer kuruluysa vendor/autoload kullanilir; degilse dahili
 * PSR-4 fallback autoloader devreye girer (yalniz App\ namespace'i).
 */

define('PUNCH_ROOT', dirname(__DIR__));

// --- Autoload ---
$composer = PUNCH_ROOT . '/vendor/autoload.php';
if (is_file($composer)) {
    require $composer;
} else {
    // Fallback: App\ -> app/ (sadece dahili sinifllar; harici paketler composer ister)
    spl_autoload_register(function (string $class): void {
        if (!str_starts_with($class, 'App\\')) {
            return;
        }
        $relative = str_replace('\\', '/', substr($class, strlen('App\\')));
        $file = PUNCH_ROOT . '/app/' . $relative . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
    // Helper fonksiyonlari composer files autoload yerine elle yukle
    require PUNCH_ROOT . '/app/Core/helpers.php';
}

// --- Config + App boot ---
$config = require PUNCH_ROOT . '/config/config.php';
$app = \App\Core\App::boot($config);

return $app;
