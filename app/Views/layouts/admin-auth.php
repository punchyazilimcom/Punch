<?php use App\Services\Seo; $seo = $seo ?? Seo::build(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']) ?> — Punch Yazilim</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/favicon.svg') ?>">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/effects.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/cosmos.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/utilities.css') ?>">
</head>
<body>
    <div style="min-height:100svh;display:grid;place-items:center;padding:var(--sp-6);position:relative">
        <div class="hero-orb hero-orb-1" style="opacity:0.3"></div>
        <div style="width:100%;max-width:400px;position:relative;z-index:1">
            <div class="text-center mb-6">
                <a href="/" class="brand mx-auto" style="justify-content:center"><?= $__view->partial('partials/logo') ?><span>Punch<span style="color:var(--punch-violet-400)">.</span></span></a>
                <p class="text-muted text-sm mt-3">Yonetim Paneli</p>
            </div>
            <?= $__view->partial('partials/flash') ?>
            <?= $content ?>
        </div>
    </div>
</body>
</html>
