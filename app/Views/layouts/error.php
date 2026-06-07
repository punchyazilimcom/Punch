<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Hata') ?> — <?= e($app['name'] ?? 'Punch Yazilim') ?></title>
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
    <main id="main" style="min-height:100svh;display:grid;place-items:center;text-align:center;padding:var(--sp-6)">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="container" style="position:relative;z-index:1">
            <?= $content ?>
        </div>
    </main>
</body>
</html>
