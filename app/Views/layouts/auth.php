<?php
use App\Services\Seo;
$seo = $seo ?? Seo::build();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0b0716">
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/favicon.svg') ?>">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@600,500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/utilities.css') ?>">
</head>
<body>
    <div style="min-height:100svh;display:grid;place-items:center;padding:var(--sp-6);position:relative">
        <div class="hero-orb hero-orb-1" style="opacity:0.35"></div>
        <div class="hero-orb hero-orb-2" style="opacity:0.35"></div>
        <div style="width:100%;max-width:440px;position:relative;z-index:1">
            <a href="/" class="brand mx-auto" style="justify-content:center;margin-bottom:var(--sp-6)">
                <?= $__view->partial('partials/logo') ?>
                <span>Punch<span style="color:var(--punch-violet-400)">.</span></span>
            </a>
            <?= $__view->partial('partials/flash') ?>
            <?= $content ?>
            <p class="text-center text-muted text-sm mt-5"><a href="/">← Ana sayfaya don</a></p>
        </div>
    </div>
    <script defer src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
