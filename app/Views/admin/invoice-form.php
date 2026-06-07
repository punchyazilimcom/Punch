<?php $v = $__view; ?>
<a href="/admin/faturalar" class="btn btn-ghost btn-sm mb-5">← Faturalar</a>
<form action="/admin/faturalar/yeni" method="post" class="card" style="max-width:620px" data-guard>
    <?= csrf_field() ?>
    <div class="form-group">
        <label class="form-label">Musteri *</label>
        <select class="form-control<?= has_error('user_id') ?>" name="user_id" required>
            <option value="">— Sec —</option>
            <?php foreach ($users as $u): ?>
            <option value="<?= (int)$u['id'] ?>"><?= e($u['name']) ?> — <?= e($u['email']) ?></option>
            <?php endforeach; ?>
        </select>
        <?= field_error('user_id') ?>
    </div>
    <div class="form-group">
        <label class="form-label">Aciklama</label>
        <input class="form-control" name="title" value="<?= old('title', 'Hizmet Bedeli') ?>">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Tutar (KDV dahil) *</label>
            <input class="form-control<?= has_error('amount') ?>" name="amount" value="<?= old('amount') ?>" required>
            <?= field_error('amount') ?>
        </div>
        <div class="form-group">
            <label class="form-label">KDV Orani (%)</label>
            <input class="form-control" name="tax_rate" value="20">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Durum</label>
        <select class="form-control" name="status">
            <option value="paid">Odendi</option>
            <option value="unpaid">Odenmedi</option>
        </select>
    </div>
    <button class="btn btn-lg">Fatura Olustur & PDF Uret</button>
</form>
