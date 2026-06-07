<?php
/** @var array $seo */
/** @var string $content */
use App\Services\Seo;

$seo = $seo ?? Seo::build();
$jsonld = $jsonld ?? [];
$bodyClass = $bodyClass ?? '';
$ga4 = setting('ga4_id', $analytics['ga4'] ?? '');
$verification = setting('google_site_verification', $analytics['site_verification'] ?? '');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']) ?></title>
    <meta name="description" content="<?= e($seo['description']) ?>">
    <meta name="robots" content="<?= e($seo['robots']) ?>">
    <link rel="canonical" href="<?= e($seo['canonical']) ?>">
    <?php if ($verification): ?><meta name="google-site-verification" content="<?= e($verification) ?>"><?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:type" content="<?= e($seo['og_type']) ?>">
    <meta property="og:title" content="<?= e($seo['title']) ?>">
    <meta property="og:description" content="<?= e($seo['description']) ?>">
    <meta property="og:url" content="<?= e($seo['canonical']) ?>">
    <meta property="og:image" content="<?= e($seo['og_image']) ?>">
    <meta property="og:site_name" content="<?= e($app['name']) ?>">
    <meta property="og:locale" content="tr_TR">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seo['title']) ?>">
    <meta name="twitter:description" content="<?= e($seo['description']) ?>">
    <meta name="twitter:image" content="<?= e($seo['og_image']) ?>">

    <meta name="theme-color" content="#0b0716">
    <link rel="icon" type="image/svg+xml" href="<?= asset('img/favicon.svg') ?>">
    <link rel="apple-touch-icon" href="<?= asset('img/favicon.svg') ?>">

    <!-- Fontlar: display=swap + preconnect -->
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@600,500&f[]=satoshi@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tasarim sistemi -->
    <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/effects.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/cosmos.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/utilities.css') ?>">

    <!-- JS yoksa animasyon baslangic durumlari icerigi gizlemesin -->
    <noscript><style>[data-reveal],.hero [data-hero-fade]{opacity:1!important;transform:none!important}</style></noscript>

    <?= Seo::renderJsonLd($jsonld) ?>

    <?php if ($ga4): ?>
    <!-- Google Analytics 4 (consent mode) -->
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('consent', 'default', { 'analytics_storage': 'denied' });
      document.addEventListener('punch:consent', function(){ gtag('consent','update',{'analytics_storage':'granted'}); });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga4) ?>"></script>
    <script>gtag('js', new Date()); gtag('config', '<?= e($ga4) ?>');</script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?> loading">
    <div class="preloader" aria-hidden="true">
        <div class="text-center">
            <div class="preloader-mark">Punch<span style="opacity:.6">.</span></div>
            <div class="preloader-bar mx-auto"></div>
        </div>
    </div>
    <div class="scroll-progress" aria-hidden="true"></div>
    <canvas class="warp-streaks" aria-hidden="true"></canvas>
    <a class="skip-link" href="#main">Icerige gec</a>
    <?= $__view->partial('partials/header') ?>

    <main id="main">
        <?= $content ?>
    </main>

    <?= $__view->partial('partials/footer') ?>
    <?= $__view->partial('partials/cookie') ?>

    <!-- GSAP + ScrollTrigger + Lenis (defer, CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/lenis@1.1.13/dist/lenis.min.js"></script>
    <script defer src="<?= asset('js/app.js') ?>"></script>
    <?php if (!empty($heroCanvas)): ?>
    <script defer src="<?= asset('js/hero.js') ?>"></script>
    <?php endif; ?>
    <?= $pageScripts ?? '' ?>
    <?= $__view->section('scripts') ?>
</body>
</html>
