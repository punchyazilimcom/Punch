<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => $work['category'] ?? 'Proje',
    'title' => e($work['title']),
    'subtitle' => $work['summary'] ?? '',
    'crumbs' => ['Ana Sayfa' => '/', 'Portfolyo' => '/portfolyo', $work['title'] => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="card overflow-hidden" data-reveal style="aspect-ratio:16/8;background:var(--grad-mesh),var(--bg-3);margin-bottom:var(--sp-8)"></div>
        <div class="split" style="align-items:start">
            <div class="prose" data-reveal><?= $work['body'] ?: '<p>' . e($work['summary'] ?? '') . '</p>' ?></div>
            <aside class="card" data-reveal>
                <h4 class="text-muted uppercase text-xs mb-4">Proje Detaylari</h4>
                <?php if (!empty($work['client'])): ?>
                <div class="mb-4"><div class="text-muted text-xs">Musteri</div><div class="text-bright"><?= e($work['client']) ?></div></div>
                <?php endif; ?>
                <?php if (!empty($work['category'])): ?>
                <div class="mb-4"><div class="text-muted text-xs">Kategori</div><div class="text-bright"><?= e($work['category']) ?></div></div>
                <?php endif; ?>
                <?php if (!empty($work['url'])): ?>
                <a href="<?= e($work['url']) ?>" target="_blank" rel="noopener" class="btn btn-block mt-4">Siteyi Ziyaret Et</a>
                <?php endif; ?>
                <a href="/iletisim" class="btn btn-ghost btn-block mt-3">Benzer Proje Istiyorum</a>
            </aside>
        </div>

        <?php if (!empty($gallery)): ?>
        <div class="grid-3 mt-8">
            <?php foreach ($gallery as $img): ?>
            <img src="<?= e($img) ?>" alt="<?= e($work['title']) ?> gorseli" loading="lazy" class="rounded-lg">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
