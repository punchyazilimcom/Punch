<div class="card">
    <h1 style="font-size:var(--fs-lg)" class="mb-4">Yonetici Girisi</h1>
    <form action="/admin/giris" method="post" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control" id="email" type="email" name="email" value="<?= old('email') ?>" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Sifre</label>
            <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-block btn-lg">Giris Yap</button>
    </form>
    <p class="text-center text-muted text-xs mt-5"><a href="/">← Siteye don</a></p>
</div>
