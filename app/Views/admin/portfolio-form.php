<?php
$v = $__view;
$w = $work;
$action = $w ? '/admin/portfolyo/' . (int)$w['id'] : '/admin/portfolyo/yeni';
?>
<a href="/admin/portfolyo" class="btn btn-ghost btn-sm mb-5">← Portfolyo</a>
<form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="card" style="max-width:820px" data-guard>
    <?= csrf_field() ?>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Baslik *</label>
            <input class="form-control<?= has_error('title') ?>" name="title" value="<?= e($w['title'] ?? old('title')) ?>" required>
            <?= field_error('title') ?>
        </div>
        <div class="form-group">
            <label class="form-label">Slug (bos = otomatik)</label>
            <input class="form-control" name="slug" value="<?= e($w['slug'] ?? '') ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <input class="form-control" name="category" value="<?= e($w['category'] ?? '') ?>" placeholder="Web Tasarim, E-Ticaret…">
        </div>
        <div class="form-group">
            <label class="form-label">Musteri</label>
            <input class="form-control" name="client" value="<?= e($w['client'] ?? '') ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Ozet</label>
        <input class="form-control" name="summary" value="<?= e($w['summary'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Detay (HTML)</label>
        <textarea class="form-control" name="body" style="min-height:160px"><?= e($w['body'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Proje URL</label>
        <input class="form-control<?= has_error('url') ?>" name="url" value="<?= e($w['url'] ?? '') ?>" placeholder="https://…">
        <?= field_error('url') ?>
    </div>
    <div class="form-group">
        <label class="form-label">Kapak Gorseli</label>
        <input class="form-control" type="file" name="cover_image" accept="image/*">
        <?php if (!empty($w['cover_image'])): ?><img src="<?= e($w['cover_image']) ?>" alt="" style="max-height:80px;margin-top:8px;border-radius:8px"><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Sira</label>
            <input class="form-control" type="number" name="sort_order" value="<?= (int)($w['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group flex items-center" style="padding-top:24px">
            <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1"<?= ($w['is_active'] ?? 1) ? ' checked' : '' ?>> Aktif</label>
        </div>
    </div>
    <button class="btn btn-lg"><?= $w ? 'Guncelle' : 'Olustur' ?></button>
</form>
