<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Hizmetlerimiz',
    'title' => 'Markanizi buyuten <span class="text-gradient">dijital hizmetler</span>',
    'subtitle' => 'Stratejiden uygulamaya, ihtiyaciniz olan tum dijital cozumler tek catida.',
    'crumbs' => ['Ana Sayfa' => '/', 'Hizmetler' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="feature-grid">
            <?php foreach ($services as $service): ?>
            <article class="card feature-card" data-reveal data-tilt>
                <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => $service['icon'] ?: 'zap']) ?></div>
                <h3><?= e($service['title']) ?></h3>
                <p class="text-soft text-sm mb-4"><?= e($service['short_desc']) ?></p>
                <?php
                $features = json_decode($service['features_json'] ?? '[]', true) ?: [];
                if ($features): ?>
                <ul class="feature-list" style="margin:var(--sp-4) 0">
                    <?php foreach (array_slice($features, 0, 4) as $f): ?>
                    <li><?= $v->partial('partials/icon', ['name' => 'check', 'size' => 16]) ?> <span><?= e($f) ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <a href="/hizmetler/<?= e($service['slug']) ?>" class="text-accent text-sm inline-flex items-center gap-2" style="display:inline-flex">
                    Detaylari Gor <?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 16]) ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= $v->partial('partials/cta-section') ?>
