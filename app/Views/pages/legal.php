<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'title' => e($pageTitle),
    'crumbs' => ['Ana Sayfa' => '/', $pageTitle => ''],
]) ?>
<section class="section" style="padding-top:0">
    <div class="container" style="max-width:820px">
        <div class="card prose" data-reveal>
            <?= $body /* Yonetici tarafindan girilen guvenilir HTML icerik */ ?>
        </div>
        <p class="text-muted text-xs mt-5">Son guncelleme: <?= date('d.m.Y') ?></p>
    </div>
</section>
