<div class="card">
    <h1 style="font-size:var(--fs-xl)">Yeni sifre belirleyin</h1>
    <p class="text-soft text-sm mt-2 mb-5">Hesabiniz icin guvenli bir sifre olusturun.</p>
    <form action="/sifre-sifirla" method="post" data-guard>
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <input type="hidden" name="email" value="<?= e($email) ?>">
        <div class="form-group">
            <label class="form-label" for="password">Yeni Sifre</label>
            <input class="form-control<?= has_error('password') ?>" id="password" type="password" name="password" required minlength="8" autofocus autocomplete="new-password">
            <?= field_error('password') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Yeni Sifre (tekrar)</label>
            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-block btn-lg">Sifreyi Guncelle</button>
    </form>
</div>
