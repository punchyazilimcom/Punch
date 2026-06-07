<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => $post['category_name'] ?? 'Blog',
    'title' => e($post['title']),
    'crumbs' => ['Ana Sayfa' => '/', 'Blog' => '/blog', $post['title'] => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container" style="max-width:780px">
        <div class="flex gap-4 items-center text-muted text-sm mb-6" data-reveal>
            <span><?= format_date($post['published_at']) ?></span>
            <span>·</span>
            <span><?= (int)$post['reading_time'] ?> dk okuma</span>
            <?php if (!empty($post['author_name'])): ?><span>·</span><span><?= e($post['author_name']) ?></span><?php endif; ?>
        </div>
        <div style="aspect-ratio:16/8;border-radius:var(--r-lg);background:var(--grad-mesh),var(--bg-3);margin-bottom:var(--sp-8)" data-reveal></div>
        <article class="prose" data-reveal>
            <?= $post['body'] /* Yonetici girisi guvenilir HTML */ ?>
        </article>

        <div class="card mt-8 flex justify-between items-center flex-wrap gap-4" data-reveal>
            <div>
                <strong class="text-bright">Bu konuda yardima mi ihtiyaciniz var?</strong>
                <p class="text-soft text-sm mt-2">Ucretsiz gorusme icin bizimle iletisime gecin.</p>
            </div>
            <a href="/iletisim" class="btn">Iletisime Gec</a>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section" style="padding-top:0">
    <div class="container">
        <h2 class="section-title mb-6" data-reveal>Ilgili <span class="text-gradient">yazilar</span></h2>
        <div class="grid-3">
            <?php foreach ($related as $r): ?>
            <article class="card" data-reveal>
                <h3 style="font-size:var(--fs-lg)"><a href="/blog/<?= e($r['slug']) ?>"><?= e($r['title']) ?></a></h3>
                <p class="text-soft text-sm mt-3"><?= e(str_excerpt($r['excerpt'] ?? '', 100)) ?></p>
                <div class="text-muted text-xs mt-4"><?= format_date($r['published_at']) ?></div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
