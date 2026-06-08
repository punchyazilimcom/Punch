<?php
/**
 * Uygulama rotalari. $router (App\Core\Router) burada doldurulur.
 */

use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\GuestMiddleware;

/** @var Router $router */

// ===================== PUBLIC (KURUMSAL) =====================
$router->get('/', 'App\Controllers\HomeController@index');

$router->get('/hizmetler', 'App\Controllers\ServiceController@index');
$router->get('/hizmetler/{slug}', 'App\Controllers\ServiceController@show');

$router->get('/paketler', 'App\Controllers\PackageController@index');
$router->get('/paketler/{slug}', 'App\Controllers\PackageController@show');

$router->get('/portfolyo', 'App\Controllers\PortfolioController@index');
$router->get('/portfolyo/{slug}', 'App\Controllers\PortfolioController@show');

$router->get('/hakkimizda', 'App\Controllers\PageController@about');

$router->get('/blog', 'App\Controllers\BlogController@index');
$router->get('/blog/kategori/{slug}', 'App\Controllers\BlogController@category');
$router->get('/blog/{slug}', 'App\Controllers\BlogController@show');

$router->get('/iletisim', 'App\Controllers\ContactController@index');
$router->post('/iletisim', 'App\Controllers\ContactController@store');

// Teklif iste (sosyal medya vb.)
$router->post('/teklif-iste', 'App\Controllers\QuoteRequestController@store');

// Hukuki sayfalar & sozlesmeler
$router->get('/on-bilgilendirme-formu', 'App\Controllers\PageController@legal');
$router->get('/mesafeli-satis-sozlesmesi', 'App\Controllers\PageController@legal');
$router->get('/teslimat-ve-iade', 'App\Controllers\PageController@legal');
$router->get('/kvkk', 'App\Controllers\PageController@legal');
$router->get('/gizlilik-politikasi', 'App\Controllers\PageController@legal');
$router->get('/cerez-politikasi', 'App\Controllers\PageController@legal');

// SEO
$router->get('/sitemap.xml', 'App\Controllers\SitemapController@xml');
$router->get('/feed.xml', 'App\Controllers\FeedController@rss');

// ===================== AUTH (MUSTERI) =====================
$router->group('', [], function (Router $r) {
    $r->get('/giris', 'App\Controllers\AuthController@showLogin', [GuestMiddleware::class]);
    $r->post('/giris', 'App\Controllers\AuthController@login', [GuestMiddleware::class]);
    $r->get('/kayit', 'App\Controllers\AuthController@showRegister', [GuestMiddleware::class]);
    $r->post('/kayit', 'App\Controllers\AuthController@register', [GuestMiddleware::class]);
    $r->post('/cikis', 'App\Controllers\AuthController@logout');
    $r->get('/sifremi-unuttum', 'App\Controllers\AuthController@showForgot', [GuestMiddleware::class]);
    $r->post('/sifremi-unuttum', 'App\Controllers\AuthController@forgot', [GuestMiddleware::class]);
    $r->get('/sifre-sifirla/{token}', 'App\Controllers\AuthController@showReset', [GuestMiddleware::class]);
    $r->post('/sifre-sifirla', 'App\Controllers\AuthController@reset', [GuestMiddleware::class]);
});

// ===================== ODEME (PayTR) =====================
$router->post('/odeme/baslat/{slug}', 'App\Controllers\PaymentController@start', [AuthMiddleware::class]);
$router->get('/odeme/basarili', 'App\Controllers\PaymentController@success');
$router->get('/odeme/basarisiz', 'App\Controllers\PaymentController@fail');

// ===================== MUSTERI PANELI =====================
$router->group('/panel', [AuthMiddleware::class], function (Router $r) {
    $r->get('', 'App\Controllers\Panel\DashboardController@index');
    $r->get('/', 'App\Controllers\Panel\DashboardController@index');
    $r->get('/paketlerim', 'App\Controllers\Panel\DashboardController@packages');

    $r->get('/faturalar', 'App\Controllers\Panel\InvoiceController@index');
    $r->get('/faturalar/{id}/pdf', 'App\Controllers\Panel\InvoiceController@pdf');

    $r->get('/destek', 'App\Controllers\Panel\TicketController@index');
    $r->get('/destek/yeni', 'App\Controllers\Panel\TicketController@create');
    $r->post('/destek/yeni', 'App\Controllers\Panel\TicketController@store');
    $r->get('/destek/ek/{id}', 'App\Controllers\Panel\TicketController@attachment');
    $r->get('/destek/{id}', 'App\Controllers\Panel\TicketController@show');
    $r->post('/destek/{id}/yanit', 'App\Controllers\Panel\TicketController@reply');

    $r->get('/teklifler', 'App\Controllers\Panel\QuoteController@index');
    $r->post('/teklifler/{id}/durum', 'App\Controllers\Panel\QuoteController@updateStatus');

    $r->get('/profil', 'App\Controllers\Panel\ProfileController@index');
    $r->post('/profil', 'App\Controllers\Panel\ProfileController@update');
    $r->post('/profil/sifre', 'App\Controllers\Panel\ProfileController@password');
});

// ===================== ADMIN PANELI =====================
$router->get('/admin/giris', 'App\Controllers\Admin\AuthController@showLogin');
$router->post('/admin/giris', 'App\Controllers\Admin\AuthController@login');
$router->post('/admin/cikis', 'App\Controllers\Admin\AuthController@logout');

$router->group('/admin', [AdminMiddleware::class], function (Router $r) {
    $r->get('', 'App\Controllers\Admin\DashboardController@index');
    $r->get('/', 'App\Controllers\Admin\DashboardController@index');

    $r->get('/musteriler', 'App\Controllers\Admin\CustomerController@index');
    $r->get('/musteriler/{id}', 'App\Controllers\Admin\CustomerController@show');
    $r->post('/musteriler/{id}/durum', 'App\Controllers\Admin\CustomerController@toggleStatus');

    $r->get('/paketler', 'App\Controllers\Admin\PackageController@index');
    $r->get('/paketler/yeni', 'App\Controllers\Admin\PackageController@create');
    $r->post('/paketler/yeni', 'App\Controllers\Admin\PackageController@store');
    $r->get('/paketler/{id}', 'App\Controllers\Admin\PackageController@edit');
    $r->post('/paketler/{id}', 'App\Controllers\Admin\PackageController@update');
    $r->post('/paketler/{id}/sil', 'App\Controllers\Admin\PackageController@destroy');

    $r->get('/siparisler', 'App\Controllers\Admin\OrderController@index');

    $r->get('/faturalar', 'App\Controllers\Admin\InvoiceController@index');
    $r->get('/faturalar/yeni', 'App\Controllers\Admin\InvoiceController@create');
    $r->post('/faturalar/yeni', 'App\Controllers\Admin\InvoiceController@store');
    $r->get('/faturalar/{id}/pdf', 'App\Controllers\Admin\InvoiceController@pdf');

    $r->get('/teklifler', 'App\Controllers\Admin\QuoteController@index');
    $r->post('/teklifler/{id}', 'App\Controllers\Admin\QuoteController@update');

    $r->get('/destek', 'App\Controllers\Admin\TicketController@index');
    $r->get('/destek/ek/{id}', 'App\Controllers\Admin\TicketController@attachment');
    $r->get('/destek/{id}', 'App\Controllers\Admin\TicketController@show');
    $r->post('/destek/{id}/yanit', 'App\Controllers\Admin\TicketController@reply');
    $r->post('/destek/{id}/durum', 'App\Controllers\Admin\TicketController@updateStatus');

    $r->get('/blog', 'App\Controllers\Admin\PostController@index');
    $r->get('/blog/yeni', 'App\Controllers\Admin\PostController@create');
    $r->post('/blog/yeni', 'App\Controllers\Admin\PostController@store');
    $r->get('/blog/{id}', 'App\Controllers\Admin\PostController@edit');
    $r->post('/blog/{id}', 'App\Controllers\Admin\PostController@update');
    $r->post('/blog/{id}/sil', 'App\Controllers\Admin\PostController@destroy');

    $r->get('/portfolyo', 'App\Controllers\Admin\PortfolioController@index');
    $r->get('/portfolyo/yeni', 'App\Controllers\Admin\PortfolioController@create');
    $r->post('/portfolyo/yeni', 'App\Controllers\Admin\PortfolioController@store');
    $r->get('/portfolyo/{id}', 'App\Controllers\Admin\PortfolioController@edit');
    $r->post('/portfolyo/{id}', 'App\Controllers\Admin\PortfolioController@update');
    $r->post('/portfolyo/{id}/sil', 'App\Controllers\Admin\PortfolioController@destroy');

    $r->get('/icerik', 'App\Controllers\Admin\ContentController@index');
    $r->post('/icerik', 'App\Controllers\Admin\ContentController@update');

    $r->get('/mesajlar', 'App\Controllers\Admin\ContactController@index');

    $r->get('/ayarlar', 'App\Controllers\Admin\SettingController@index');
    $r->post('/ayarlar', 'App\Controllers\Admin\SettingController@update');

    $r->get('/loglar', 'App\Controllers\Admin\AuditController@index');
    $r->get('/disa-aktar/{type}', 'App\Controllers\Admin\ExportController@csv');
});
