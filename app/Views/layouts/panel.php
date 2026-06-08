<?php
use App\Core\Auth;
use App\Services\Seo;
$me = Auth::user(Auth::GUARD_USER);
$seo = $seo ?? Seo::build(['robots' => 'noindex, nofollow']);
$nav = [
    ['/panel', 'home', 'Genel Bakis'],
    ['/panel/paketlerim', 'package', 'Paketlerim'],
    ['/panel/faturalar', 'file-text', 'Faturalarim'],
    ['/panel/destek', 'message-square', 'Destek'],
    ['/panel/teklifler', 'credit-card', 'Tekliflerim'],
    ['/panel/profil', 'user', 'Profil & Guvenlik'],
];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title'] ?? 'Panel') ?> — Punch Yazilim</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/favicon.svg') ?>">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@600,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/utilities.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
</head>
<body>
<div class="dash">
    <aside class="dash-sidebar">
        <a href="/" class="brand"><?= $__view->partial('partials/logo') ?><span>Punch<span style="color:var(--punch-violet-400)">.</span></span></a>
        <nav class="dash-nav" aria-label="Panel menu">
            <?php foreach ($nav as [$href, $icon, $label]): $active = $href === '/panel' ? ($path === '/panel') : str_starts_with($path, $href); ?>
            <a href="<?= $href ?>"<?= $active ? ' aria-current="page"' : '' ?>>
                <?= $__view->partial('partials/icon', ['name' => $icon, 'size' => 18]) ?> <?= e($label) ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <form action="/cikis" method="post" style="margin-top:var(--sp-5)">
            <?= csrf_field() ?>
            <button class="dash-nav" type="submit" style="background:none;border:0;cursor:pointer;width:100%">
                <span style="display:flex;align-items:center;gap:var(--sp-3);padding:0.7rem 0.9rem;color:var(--text-2)">
                    <?= $__view->partial('partials/icon', ['name' => 'log-out', 'size' => 18]) ?> Cikis Yap
                </span>
            </button>
        </form>
    </aside>
    <div class="dash-backdrop" data-dash-close></div>

    <main class="dash-main">
        <div class="dash-topbar">
            <div class="flex items-center gap-3">
                <button class="btn btn-ghost btn-sm dash-toggle" data-dash-toggle aria-label="Menu"><?= $__view->partial('partials/icon', ['name' => 'menu', 'size' => 18]) ?></button>
                <h1><?= e($pageTitle ?? 'Panel') ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-soft text-sm"><?= e($me['name'] ?? '') ?></span>
                <div class="feature-icon" style="margin:0;width:40px;height:40px"><?= $__view->partial('partials/icon', ['name' => 'user', 'size' => 18]) ?></div>
            </div>
        </div>
        <?= $__view->partial('partials/flash') ?>
        <?= $content ?>
    </main>
</div>
<script nonce="<?= csp_nonce() ?>">
(function(){
  var t=document.querySelector('[data-dash-toggle]'), c=document.querySelector('[data-dash-close]');
  if(t)t.addEventListener('click',function(){document.body.classList.toggle('dash-open');});
  if(c)c.addEventListener('click',function(){document.body.classList.remove('dash-open');});
})();
</script>
</body>
</html>
