<?php
use App\Core\Auth;
use App\Services\Seo;
use App\Models\Ticket;
use App\Models\Quote;
use App\Models\ContactMessage;
$me = Auth::user(Auth::GUARD_ADMIN);
$seo = $seo ?? Seo::build(['robots' => 'noindex, nofollow']);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

try {
    $openTickets = (new Ticket())->openCount();
    $newQuotes   = (new Quote())->newCount();
    $unreadMsgs  = (new ContactMessage())->count('is_read = 0');
} catch (\Throwable $e) {
    $openTickets = $newQuotes = $unreadMsgs = 0;
}

$nav = [
    ['Genel', [
        ['/admin', 'bar-chart', 'Dashboard', 0],
    ]],
    ['Satis', [
        ['/admin/siparisler', 'credit-card', 'Siparisler', 0],
        ['/admin/faturalar', 'file-text', 'Faturalar', 0],
        ['/admin/teklifler', 'package', 'Teklifler', $newQuotes],
    ]],
    ['Icerik', [
        ['/admin/paketler', 'package', 'Paketler', 0],
        ['/admin/blog', 'pen-tool', 'Blog', 0],
        ['/admin/portfolyo', 'layout', 'Portfolyo', 0],
        ['/admin/icerik', 'home', 'Sayfa Icerikleri', 0],
    ]],
    ['Iliski', [
        ['/admin/musteriler', 'users', 'Musteriler', 0],
        ['/admin/destek', 'message-square', 'Destek', $openTickets],
        ['/admin/mesajlar', 'mail', 'Mesajlar', $unreadMsgs],
    ]],
    ['Sistem', [
        ['/admin/ayarlar', 'settings', 'Ayarlar', 0],
        ['/admin/loglar', 'shield', 'Loglar', 0],
    ]],
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Admin') ?> — Punch Yonetim</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/favicon.svg') ?>">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@600,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/utilities.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/corporate.css') ?>">
</head>
<body>
<div class="dash">
    <aside class="dash-sidebar">
        <a href="/admin" class="brand"><?= $__view->partial('partials/logo') ?><span>Punch<span style="color:var(--punch-violet-400)">.</span> <span class="text-muted text-xs">admin</span></span></a>
        <nav class="dash-nav" aria-label="Admin menu">
            <?php foreach ($nav as [$group, $items]): ?>
                <div class="nav-sep"><?= e($group) ?></div>
                <?php foreach ($items as [$href, $icon, $label, $count]):
                    $active = $href === '/admin' ? ($path === '/admin') : str_starts_with($path, $href); ?>
                <a href="<?= $href ?>"<?= $active ? ' aria-current="page"' : '' ?>>
                    <?= $__view->partial('partials/icon', ['name' => $icon, 'size' => 18]) ?>
                    <span style="flex:1"><?= e($label) ?></span>
                    <?php if ($count > 0): ?><span class="badge badge-danger" style="padding:1px 7px"><?= (int)$count ?></span><?php endif; ?>
                </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </nav>
        <form action="/admin/cikis" method="post" style="margin-top:var(--sp-4)">
            <?= csrf_field() ?>
            <button type="submit" style="background:none;border:0;cursor:pointer;width:100%;display:flex;align-items:center;gap:var(--sp-3);padding:0.7rem 0.9rem;color:var(--text-2)">
                <?= $__view->partial('partials/icon', ['name' => 'log-out', 'size' => 18]) ?> Cikis
            </button>
        </form>
    </aside>
    <div class="dash-backdrop" data-dash-close></div>

    <main class="dash-main">
        <div class="dash-topbar">
            <div class="flex items-center gap-3">
                <button class="btn btn-ghost btn-sm dash-toggle" data-dash-toggle aria-label="Menu"><?= $__view->partial('partials/icon', ['name' => 'menu', 'size' => 18]) ?></button>
                <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="/" target="_blank" class="btn btn-ghost btn-sm">Siteyi Gor</a>
                <span class="text-soft text-sm"><?= e($me['name'] ?? '') ?></span>
            </div>
        </div>
        <?= $__view->partial('partials/flash') ?>
        <?= $content ?>
    </main>
</div>
<script nonce="<?= csp_nonce() ?>">
(function(){var t=document.querySelector('[data-dash-toggle]'),c=document.querySelector('[data-dash-close]');
if(t)t.addEventListener('click',function(){document.body.classList.toggle('dash-open');});
if(c)c.addEventListener('click',function(){document.body.classList.remove('dash-open');});})();
</script>
</body>
</html>
