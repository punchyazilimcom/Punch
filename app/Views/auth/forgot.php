<div class="card">
    <h1 style="font-size:var(--fs-xl)">Sifrenizi mi unuttunuz?</h1>
    <p class="text-soft text-sm mt-2 mb-5">E-postanizi girin, size sifirlama baglantisi gonderelim.</p>
    <form action="/sifremi-unuttum" method="post" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="email">E-posta</label>
            <input class="form-control" id="email" type="email" name="email" value="<?= old('email') ?>" required autofocus>
        </div>
        <button type="submit" class="btn btn-block btn-lg">Baglanti Gonder</button>
    </form>
    <p class="text-center text-sm mt-5 text-soft"><a href="/giris" class="text-accent">Girise don</a></p>
</div>
