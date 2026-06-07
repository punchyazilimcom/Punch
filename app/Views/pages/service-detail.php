<?php
$v = $__view;
$features = json_decode($service['features_json'] ?? '[]', true) ?: [];
?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Hizmet',
    'title' => e($service['title']),
    'subtitle' => $service['short_desc'] ?? '',
    'crumbs' => ['Ana Sayfa' => '/', 'Hizmetler' => '/hizmetler', $service['title'] => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container split" style="align-items:start">
        <div class="prose" data-reveal>
            <?= $service['description'] ? '<p>' . e($service['description']) . '</p>' : '' ?>
            <?php if ($features): ?>
            <h2>Neler dahil?</h2>
            <ul class="feature-list">
                <?php foreach ($features as $f): ?>
                <li><?= $v->partial('partials/icon', ['name' => 'check', 'size' => 18]) ?> <span><?= e($f) ?></span></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <aside class="card" data-reveal style="position:sticky;top:100px">
            <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => $service['icon'] ?: 'zap']) ?></div>
            <h3>Bu hizmetle ilgileniyor musunuz?</h3>
            <p class="text-soft text-sm mt-3">Size ozel bir teklif icin hemen iletisime gecin veya hazir paketlerimizi inceleyin.</p>
            <div class="flex flex-col gap-3 mt-5">
                <a href="/iletisim" class="btn btn-block">Teklif Al</a>
                <a href="/paketler" class="btn btn-ghost btn-block">Paketleri Gor</a>
            </div>
        </aside>
    </div>
</section>

<?php if (!empty($packages)): ?>
<section class="section" style="padding-top:0">
    <div class="container">
        <h2 class="section-title mb-6" data-reveal>Ilgili <span class="text-gradient">paketler</span></h2>
        <div class="pricing-grid">
            <?php foreach ($packages as $pkg): ?>
            <article class="card pricing-card" data-reveal>
                <h3><?= e($pkg['title']) ?></h3>
                <p class="text-soft text-sm"><?= e($pkg['short_desc']) ?></p>
                <?php if (!empty($pkg['is_quote_only'])): ?>
                    <div class="price quote">Teklife Ozel</div>
                <?php else: ?>
                    <div class="price"><?= price_format($pkg['price'], $pkg['currency']) ?></div>
                <?php endif; ?>
                <a href="/paketler/<?= e($pkg['slug']) ?>" class="btn btn-block">Incele</a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
