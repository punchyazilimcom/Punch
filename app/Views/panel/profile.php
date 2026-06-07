<div class="grid-2" style="align-items:start">
    <div class="card">
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Profil Bilgileri</h2>
        <form action="/panel/profil" method="post" data-guard>
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="name">Ad Soyad</label>
                <input class="form-control<?= has_error('name') ?>" id="name" name="name" value="<?= e($me['name']) ?>" required>
                <?= field_error('name') ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Telefon</label>
                    <input class="form-control" id="phone" name="phone" value="<?= e($me['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="company">Firma</label>
                    <input class="form-control" id="company" name="company" value="<?= e($me['company'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="tax_no">Vergi No</label>
                    <input class="form-control" id="tax_no" name="tax_no" value="<?= e($me['tax_no'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="tc_no">TC Kimlik No</label>
                    <input class="form-control" id="tc_no" name="tc_no" value="<?= e($me['tc_no'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Adres</label>
                <textarea class="form-control" id="address" name="address" style="min-height:90px"><?= e($me['address'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="city">Sehir</label>
                <input class="form-control" id="city" name="city" value="<?= e($me['city'] ?? '') ?>">
            </div>
            <label class="flex items-center gap-3 mb-4 text-sm text-soft" style="cursor:pointer">
                <input type="checkbox" name="notify_email" value="1"<?= !empty($me['notify_email']) ? ' checked' : '' ?>>
                E-posta bildirimleri almak istiyorum
            </label>
            <button type="submit" class="btn">Bilgileri Kaydet</button>
        </form>
    </div>

    <div class="card">
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Sifre Degistir</h2>
        <form action="/panel/profil/sifre" method="post" data-guard>
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="current_password">Mevcut Sifre</label>
                <input class="form-control" id="current_password" type="password" name="current_password" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Yeni Sifre</label>
                <input class="form-control<?= has_error('password') ?>" id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                <?= field_error('password') ?>
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Yeni Sifre (tekrar)</label>
                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn">Sifreyi Guncelle</button>
        </form>
        <hr class="divider">
        <div class="text-muted text-sm">
            <p>Hesap olusturma: <?= format_date($me['created_at']) ?></p>
            <p class="mt-2">E-posta: <?= e($me['email']) ?></p>
        </div>
    </div>
</div>
