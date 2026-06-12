<div class="card">
    <h1 style="font-size:var(--fs-xl)">Hesap olusturun</h1>
    <p class="text-soft text-sm mt-2 mb-5">Paketleri satin almak ve destek almak icin uye olun.</p>
    <form action="/kayit" method="post" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="name">Ad Soyad</label>
            <input class="form-control<?= has_error('name') ?>" id="name" name="name" value="<?= old('name') ?>" required autofocus>
            <?= field_error('name') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control<?= has_error('email') ?>" id="email" type="email" name="email" value="<?= old('email') ?>" required autocomplete="email">
            <?= field_error('email') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="phone">Telefon</label>
            <input class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" placeholder="05xx xxx xx xx">
            <?= field_error('phone') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Sifre</label>
            <input class="form-control<?= has_error('password') ?>" id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
            <span class="form-hint">En az 8 karakter.</span>
            <?= field_error('password') ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Sifre (tekrar)</label>
            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <label class="flex items-start gap-3 mb-5 text-sm text-soft" style="cursor:pointer">
            <input type="checkbox" name="terms" value="1" style="margin-top:4px">
            <span><a href="/mesafeli-satis-sozlesmesi" class="text-accent" target="_blank">Mesafeli Satis Sozlesmesi</a> ve <a href="/kvkk" class="text-accent" target="_blank">KVKK</a> metnini okudum, kabul ediyorum.</span>
        </label>
        <?= field_error('terms') ?>
        <button type="submit" class="btn btn-block btn-lg">Kayit Ol</button>
    </form>
    <p class="text-center text-sm mt-5 text-soft">Zaten uye misiniz? <a href="/giris" class="text-accent">Giris yapin</a></p>
</div>
