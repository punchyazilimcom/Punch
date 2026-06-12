<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Portfolyo',
    'title' => 'Hayata gecirdigimiz <span class="text-gradient">projeler</span>',
    'subtitle' => 'Markalarin dijital donusumune nasil katki sagladigimizi kesfedin.',
    'crumbs' => ['Ana Sayfa' => '/', 'Portfolyo' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="filter-bar" data-reveal>
            <button class="filter-chip active" data-filter="all">Tumu</button>
            <?php foreach ($categories as $cat): if (empty($cat['category'])) continue; ?>
            <button class="filter-chip" data-filter="<?= e($cat['category']) ?>"><?= e($cat['category']) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="work-grid">
            <?php foreach ($works as $work): ?>
            <a href="/portfolyo/<?= e($work['slug']) ?>" class="work-card tilt-shine" data-reveal data-cat="<?= e($work['category']) ?>">
                <div class="work-media" data-reveal-mask></div>
                <div class="work-meta">
                    <span class="work-cat"><?= e($work['category']) ?></span>
                    <h3><?= e($work['title']) ?></h3>
                    <p class="text-soft text-sm mt-2"><?= e(str_excerpt($work['summary'] ?? '', 90)) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php if (empty($works)): ?>
        <p class="text-muted text-center">Henuz proje eklenmemis.</p>
        <?php endif; ?>
    </div>
</section>
<?= $v->partial('partials/cta-section') ?>
