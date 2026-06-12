<?php
/**
 * Front Controller — tum istekler buraya gelir (.htaccess yonlendirmesiyle).
 */

declare(strict_types=1);

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\View;

/** @var \App\Core\App $app */
$app = require dirname(__DIR__) . '/app/bootstrap.php';

// Global hata yakalama
set_exception_handler(function (\Throwable $e) use ($app): void {
    Logger::error('Yakalanmayan istisna: ' . $e->getMessage(), [
        'file' => $e->getFile(), 'line' => $e->getLine(),
    ]);
    Response::securityHeaders();
    $debug = (bool) ($app->config['app']['debug'] ?? false);
    if ($debug) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo "HATA: {$e->getMessage()}\n{$e->getFile()}:{$e->getLine()}\n\n{$e->getTraceAsString()}";
        exit;
    }
    try {
        $view = new View();
        Response::html($view->render('errors/500', [], 'layouts/error'), 500);
    } catch (\Throwable $inner) {
        Response::text('Beklenmeyen bir hata olustu. Lutfen daha sonra tekrar deneyin.', 500);
    }
});

Session::start();

$request = new Request();

// CSRF kontrolu (yazma istekleri). PayTR callback ayri dosyada, muaf.
Csrf::check($request);

$router = new Router();
require dirname(__DIR__) . '/app/routes.php';

$router->dispatch($request);
