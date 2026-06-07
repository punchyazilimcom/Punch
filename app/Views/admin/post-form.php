<?php
$v = $__view;
$p = $post;
$action = $p ? '/admin/blog/' . (int)$p['id'] : '/admin/blog/yeni';
?>
<a href="/admin/blog" class="btn btn-ghost btn-sm mb-5">← Blog</a>
<form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="card" style="max-width:860px" data-guard>
    <?= csrf_field() ?>
    <div class="form-group">
        <label class="form-label">Baslik *</label>
        <input class="form-control<?= has_error('title') ?>" name="title" value="<?= e($p['title'] ?? old('title')) ?>" required>
        <?= field_error('title') ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Slug (bos = otomatik)</label>
            <input class="form-control" name="slug" value="<?= e($p['slug'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select class="form-control" name="category_id">
                <option value="">— Sec —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>"<?= ($p['category_id'] ?? '')==$c['id']?' selected':'' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Ozet</label>
        <textarea class="form-control" name="excerpt" style="min-height:70px"><?= e($p['excerpt'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Icerik (HTML destekli) *</label>
        <textarea class="form-control<?= has_error('body') ?>" name="body" style="min-height:300px"><?= e($p['body'] ?? old('body')) ?></textarea>
        <span class="form-hint">Basit HTML kullanabilirsiniz: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;&lt;li&gt;, &lt;a&gt;, &lt;strong&gt;.</span>
        <?= field_error('body') ?>
    </div>
    <div class="form-group">
        <label class="form-label">Kapak Gorseli</label>
        <input class="form-control" type="file" name="cover_image" accept="image/*">
        <?php if (!empty($p['cover_image'])): ?><img src="<?= e($p['cover_image']) ?>" alt="" style="max-height:80px;margin-top:8px;border-radius:8px"><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Durum</label>
            <select class="form-control" name="status">
                <option value="draft"<?= ($p['status'] ?? 'draft')==='draft'?' selected':'' ?>>Taslak</option>
                <option value="published"<?= ($p['status'] ?? '')==='published'?' selected':'' ?>>Yayinla</option>
                <option value="scheduled"<?= ($p['status'] ?? '')==='scheduled'?' selected':'' ?>>Zamanla</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Yayin Tarihi (zamanlama)</label>
            <input class="form-control" type="datetime-local" name="published_at" value="<?= $p && $p['published_at'] ? date('Y-m-d\TH:i', strtotime($p['published_at'])) : '' ?>">
        </div>
    </div>
    <hr class="divider">
    <div class="form-group"><label class="form-label">Meta Baslik</label><input class="form-control" name="meta_title" value="<?= e($p['meta_title'] ?? '') ?>"></div>
    <div class="form-group"><label class="form-label">Meta Aciklama</label><textarea class="form-control" name="meta_description" style="min-height:70px"><?= e($p['meta_description'] ?? '') ?></textarea></div>
    <button class="btn btn-lg"><?= $p ? 'Guncelle' : 'Olustur' ?></button>
</form>
