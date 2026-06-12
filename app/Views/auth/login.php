<div class="card">
    <h1 style="font-size:var(--fs-xl)">Tekrar hosgeldiniz</h1>
    <p class="text-soft text-sm mt-2 mb-5">Panelinize erismek icin giris yapin.</p>
    <form action="/giris" method="post" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control<?= has_error('email') ?>" id="email" type="email" name="email" value="<?= old('email') ?>" required autofocus autocomplete="email">
            <?= field_error('email') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Sifre</label>
            <input class="form-control<?= has_error('password') ?>" id="password" type="password" name="password" required autocomplete="current-password">
            <?= field_error('password') ?>
        </div>
        <div class="flex justify-between items-center mb-5">
            <a href="/sifremi-unuttum" class="text-accent text-sm">Sifremi unuttum</a>
        </div>
        <button type="submit" class="btn btn-block btn-lg">Giris Yap</button>
    </form>
    <p class="text-center text-sm mt-5 text-soft">Hesabiniz yok mu? <a href="/kayit" class="text-accent">Kayit olun</a></p>
</div>
