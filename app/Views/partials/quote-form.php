<?php
/** @var string|null $packageSlug */
$packageSlug = $packageSlug ?? '';
use App\Core\Auth;
$me = Auth::user(Auth::GUARD_USER);
?>
<form action="/teklif-iste" method="post" class="card" data-guard>
    <?= csrf_field() ?>
    <input type="hidden" name="package_slug" value="<?= e($packageSlug) ?>">
    <!-- bal kupu -->
    <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
    <h3 class="mb-4">Size ozel teklif alin</h3>
    <div class="form-group">
        <label class="form-label" for="q_name">Ad Soyad *</label>
        <input class="form-control<?= has_error('name') ?>" id="q_name" name="name" value="<?= old('name', $me['name'] ?? '') ?>" required>
        <?= field_error('name') ?>
    </div>
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label" for="q_email">E-posta *</label>
            <input class="form-control<?= has_error('email') ?>" id="q_email" type="email" name="email" value="<?= old('email', $me['email'] ?? '') ?>" required>
            <?= field_error('email') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="q_phone">Telefon</label>
            <input class="form-control" id="q_phone" name="phone" value="<?= old('phone', $me['phone'] ?? '') ?>" placeholder="05xx xxx xx xx">
            <?= field_error('phone') ?>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label" for="q_message">Mesajiniz *</label>
        <textarea class="form-control<?= has_error('message') ?>" id="q_message" name="message" required placeholder="Ihtiyaciniz hakkinda kisaca bilgi verin…"><?= old('message') ?></textarea>
        <?= field_error('message') ?>
    </div>
    <button type="submit" class="btn btn-block">Teklif Iste</button>
    <p class="form-hint mt-3">Gonderdiginizde KVKK Aydinlatma Metni'ni kabul etmis sayilirsiniz.</p>
</form>
