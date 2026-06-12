<?php
$v = $__view;
$p = $package;
$action = $p ? '/admin/paketler/' . (int)$p['id'] : '/admin/paketler/yeni';
$features = $p ? implode("\n", json_decode($p['features_json'] ?? '[]', true) ?: []) : '';
?>
<a href="/admin/paketler" class="btn btn-ghost btn-sm mb-5">← Paketler</a>
<form action="<?= $action ?>" method="post" class="card" style="max-width:820px" data-guard>
    <?= csrf_field() ?>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Baslik *</label>
            <input class="form-control<?= has_error('title') ?>" name="title" value="<?= e($p['title'] ?? old('title')) ?>" required>
            <?= field_error('title') ?>
        </div>
        <div class="form-group">
            <label class="form-label">Slug (bos = otomatik)</label>
            <input class="form-control" name="slug" value="<?= e($p['slug'] ?? '') ?>" placeholder="kurumsal-web-site">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Kisa Aciklama</label>
        <input class="form-control" name="short_desc" value="<?= e($p['short_desc'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Aciklama</label>
        <textarea class="form-control" name="description"><?= e($p['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Ozellikler (her satira bir madde)</label>
        <textarea class="form-control" name="features" style="min-height:140px"><?= e($features) ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Fiyat</label>
            <input class="form-control<?= has_error('price') ?>" name="price" value="<?= e($p['price'] ?? '0') ?>">
            <?= field_error('price') ?>
        </div>
        <div class="form-group">
            <label class="form-label">Para Birimi</label>
            <select class="form-control" name="currency">
                <?php foreach (['TRY','USD','EUR'] as $c): ?>
                <option value="<?= $c ?>"<?= ($p['currency'] ?? 'TRY')===$c?' selected':'' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Ikon (lucide adi)</label>
            <input class="form-control" name="icon" value="<?= e($p['icon'] ?? 'package') ?>" placeholder="package, layout, shopping-cart, megaphone">
        </div>
        <div class="form-group">
            <label class="form-label">Sira</label>
            <input class="form-control" name="sort_order" type="number" value="<?= (int)($p['sort_order'] ?? 0) ?>">
        </div>
    </div>
    <div class="flex gap-5 flex-wrap mb-5">
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1"<?= ($p['is_active'] ?? 1) ? ' checked' : '' ?>> Aktif</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_popular" value="1"<?= !empty($p['is_popular']) ? ' checked' : '' ?>> Populer</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_quote_only" value="1"<?= !empty($p['is_quote_only']) ? ' checked' : '' ?>> Sadece teklif (fiyat gosterme)</label>
    </div>
    <hr class="divider">
    <h3 style="font-size:var(--fs-base)" class="mb-3">SEO</h3>
    <div class="form-group"><label class="form-label">Meta Baslik</label><input class="form-control" name="meta_title" value="<?= e($p['meta_title'] ?? '') ?>"></div>
    <div class="form-group"><label class="form-label">Meta Aciklama</label><textarea class="form-control" name="meta_description" style="min-height:70px"><?= e($p['meta_description'] ?? '') ?></textarea></div>
    <button class="btn btn-lg"><?= $p ? 'Guncelle' : 'Olustur' ?></button>
</form>
