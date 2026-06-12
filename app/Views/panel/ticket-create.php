<div class="card" style="max-width:680px">
    <form action="/panel/destek/yeni" method="post" enctype="multipart/form-data" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="subject">Konu *</label>
            <input class="form-control<?= has_error('subject') ?>" id="subject" name="subject" value="<?= old('subject') ?>" required>
            <?= field_error('subject') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="priority">Oncelik</label>
            <select class="form-control" id="priority" name="priority">
                <option value="low"<?= old_select('priority', 'low') ?>>Dusuk</option>
                <option value="normal"<?= old_select('priority', 'normal', 'normal') ?>>Normal</option>
                <option value="high"<?= old_select('priority', 'high') ?>>Yuksek</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="message">Mesajiniz *</label>
            <textarea class="form-control<?= has_error('message') ?>" id="message" name="message" required><?= old('message') ?></textarea>
            <?= field_error('message') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="attachment">Dosya Eki (opsiyonel)</label>
            <input class="form-control" id="attachment" type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.zip,.doc,.docx">
            <span class="form-hint">Maksimum 8MB. Gorsel, PDF veya ofis dosyasi.</span>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn">Talebi Gonder</button>
            <a href="/panel/destek" class="btn btn-ghost">Vazgec</a>
        </div>
    </form>
</div>
