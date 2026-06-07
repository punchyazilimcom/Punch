<?php
use App\Models\Package;
use App\Core\Auth;
$v = $__view;
$features = Package::features($package);
$isQuote = !empty($package['is_quote_only']);
$loggedIn = Auth::check(Auth::GUARD_USER);
?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Paket',
    'title' => e($package['title']),
    'subtitle' => $package['short_desc'] ?? '',
    'crumbs' => ['Ana Sayfa' => '/', 'Paketler' => '/paketler', $package['title'] => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container split" style="align-items:start">
        <div class="prose" data-reveal>
            <?php if (!empty($package['description'])): ?><p><?= e($package['description']) ?></p><?php endif; ?>
            <h2>Pakete dahil olanlar</h2>
            <ul class="feature-list">
                <?php foreach ($features as $f): ?>
                <li><?= $v->partial('partials/icon', ['name' => 'check', 'size' => 18]) ?> <span><?= e($f) ?></span></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <aside class="card pricing-card" data-reveal style="position:sticky;top:100px">
            <?= $v->partial('partials/flash') ?>
            <?php if ($isQuote): ?>
                <div class="price quote">Teklife Ozel</div>
                <p class="text-soft text-sm mb-4">Bu hizmet ihtiyaciniza gore fiyatlandirilir. Asagidaki formla size ozel teklif alin.</p>
            <?php else: ?>
                <div class="price"><?= price_format($package['price'], $package['currency']) ?></div>
                <p class="text-muted text-xs mb-4">KDV dahildir · Guvenli odeme PayTR ile</p>
                <?php if ($loggedIn): ?>
                    <form action="/odeme/baslat/<?= e($package['slug']) ?>" method="post" data-guard>
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-block btn-lg">
                            Guvenli Odeme ile Satin Al <?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 18]) ?>
                        </button>
                    </form>
                    <div class="flex items-center gap-2 mt-4 text-muted text-xs" style="justify-content:center">
                        <?= $v->partial('partials/icon', ['name' => 'shield', 'size' => 16]) ?> 256-bit SSL korumali odeme
                    </div>
                <?php else: ?>
                    <a href="/giris" class="btn btn-block btn-lg">Satin almak icin giris yapin</a>
                    <p class="form-hint text-center mt-3">Hesabiniz yok mu? <a href="/kayit" class="text-accent">Hemen kayit olun</a></p>
                <?php endif; ?>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php if ($isQuote): ?>
<section class="section" style="padding-top:0">
    <div class="container" style="max-width:680px">
        <?= $v->partial('partials/quote-form', ['packageSlug' => $package['slug']]) ?>
    </div>
</section>
<?php endif; ?>
