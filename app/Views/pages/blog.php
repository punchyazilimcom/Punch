<?php
$v = $__view;
$totalPages = (int) ceil(($total ?? 0) / ($perPage ?? 9));
$baseUrl = $activeCategory ? '/blog/kategori/' . $activeCategory['slug'] : '/blog';
?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Blog',
    'title' => $activeCategory ? e($activeCategory['name']) : 'Dijital dunyadan <span class="text-gradient">notlar</span>',
    'subtitle' => 'Web, e-ticaret, SEO ve sosyal medya uzerine rehberler.',
    'crumbs' => $activeCategory
        ? ['Ana Sayfa' => '/', 'Blog' => '/blog', $activeCategory['name'] => '']
        : ['Ana Sayfa' => '/', 'Blog' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container">
        <?php if (!empty($categories)): ?>
        <div class="filter-bar" data-reveal>
            <a class="filter-chip<?= !$activeCategory ? ' active' : '' ?>" href="/blog">Tumu</a>
            <?php foreach ($categories as $cat): ?>
            <a class="filter-chip<?= $activeCategory && $activeCategory['id'] == $cat['id'] ? ' active' : '' ?>" href="/blog/kategori/<?= e($cat['slug']) ?>">
                <?= e($cat['name']) ?> <span class="text-muted">(<?= (int)($cat['post_count'] ?? 0) ?>)</span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p class="text-muted text-center section">Bu kategoride henuz yazi yok.</p>
        <?php else: ?>
        <div class="grid-3">
            <?php foreach ($posts as $post): ?>
            <article class="card" data-reveal>
                <div style="aspect-ratio:16/9;border-radius:var(--r-md);background:var(--grad-mesh),var(--bg-3);margin-bottom:var(--sp-4)"></div>
                <span class="badge badge-info"><?= e($post['category_name'] ?? 'Blog') ?></span>
                <h3 style="font-size:var(--fs-lg);margin-top:var(--sp-3)"><a href="/blog/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
                <p class="text-soft text-sm mt-3"><?= e(str_excerpt($post['excerpt'] ?? '', 120)) ?></p>
                <div class="text-muted text-xs mt-4"><?= format_date($post['published_at']) ?> · <?= (int)$post['reading_time'] ?> dk okuma</div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
        <nav class="flex justify-center gap-2 mt-8" aria-label="Sayfalama">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="filter-chip<?= $i === $page ? ' active' : '' ?>" href="<?= $baseUrl ?>?sayfa=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>
    </div>
</section>
