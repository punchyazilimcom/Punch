<?php
use App\Models\Package;
$v = $__view;
?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Paketler & Fiyatlar',
    'title' => 'Net fiyatlar, <span class="text-gradient">net sonuclar</span>',
    'subtitle' => 'Sabit fiyatli paketleri online satin alin; ozel ihtiyaclar icin teklif isteyin.',
    'crumbs' => ['Ana Sayfa' => '/', 'Paketler' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container">
        <?= $v->partial('partials/flash') ?>
        <div class="pricing-grid">
            <?php foreach ($packages as $pkg): $features = Package::features($pkg); ?>
            <article class="card pricing-card<?= !empty($pkg['is_popular']) ? ' is-popular' : '' ?>" data-reveal>
                <?php if (!empty($pkg['is_popular'])): ?><span class="pricing-badge">Populer</span><?php endif; ?>
                <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => $pkg['icon'] ?: 'package']) ?></div>
                <h3><?= e($pkg['title']) ?></h3>
                <p class="text-soft text-sm"><?= e($pkg['short_desc']) ?></p>
                <?php if (!empty($pkg['is_quote_only'])): ?>
                    <div class="price quote">Teklife Ozel</div>
                <?php else: ?>
                    <div class="price"><?= price_format($pkg['price'], $pkg['currency']) ?> <small>KDV dahil</small></div>
                <?php endif; ?>
                <ul class="feature-list">
                    <?php foreach ($features as $f): ?>
                    <li><?= $v->partial('partials/icon', ['name' => 'check', 'size' => 18]) ?> <span><?= e($f) ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/paketler/<?= e($pkg['slug']) ?>" class="btn btn-block">
                    <?= !empty($pkg['is_quote_only']) ? 'Teklif Iste' : 'Incele & Satin Al' ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($faqs)): ?>
<section class="section" style="padding-top:0">
    <div class="container" style="max-width:760px">
        <h2 class="section-title text-center mx-auto mb-6" data-reveal>Sik sorulan <span class="text-gradient">sorular</span></h2>
        <?php foreach ($faqs as $i => $faq): ?>
        <div class="faq-item<?= $i === 0 ? ' open' : '' ?>" data-reveal>
            <button class="faq-q" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                <span><?= e($faq['q']) ?></span>
                <span class="faq-icon"><?= $v->partial('partials/icon', ['name' => 'plus', 'size' => 22]) ?></span>
            </button>
            <div class="faq-a"<?= $i === 0 ? ' style="max-height:400px"' : '' ?>><div class="faq-a-inner"><?= e($faq['a']) ?></div></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
