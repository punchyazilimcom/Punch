<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Guvenli Odeme',
    'title' => 'Odemenizi <span class="text-gradient">tamamlayin</span>',
    'crumbs' => ['Ana Sayfa' => '/', 'Paketler' => '/paketler', $package['title'] => '/paketler/' . $package['slug'], 'Odeme' => ''],
]) ?>
<section class="section" style="padding-top:0">
    <div class="container" style="max-width:920px">
        <div class="card mb-5 flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="text-muted text-xs">Paket</div>
                <strong class="text-bright" style="font-size:var(--fs-lg)"><?= e($package['title']) ?></strong>
            </div>
            <div class="text-right">
                <div class="text-muted text-xs">Tutar (KDV dahil)</div>
                <strong class="text-bright" style="font-size:var(--fs-lg)"><?= price_format($amount, $package['currency']) ?></strong>
            </div>
        </div>

        <div class="card" style="padding:var(--sp-3)">
            <!-- PayTR iFrame -->
            <iframe src="https://www.paytr.com/odeme/guvenli/<?= e($token) ?>"
                    id="paytriframe" frameborder="0" scrolling="no"
                    style="width:100%;min-height:680px;border-radius:var(--r-md)"
                    title="PayTR Guvenli Odeme"></iframe>
        </div>
        <p class="text-muted text-xs text-center mt-4">
            <?= $v->partial('partials/icon', ['name' => 'shield', 'size' => 14]) ?>
            Odemeniz PayTR'nin 256-bit SSL korumali altyapisi uzerinden alinir. Kart bilgileriniz sunucularimizda saklanmaz.
        </p>
    </div>
</section>
<?php $__view->start('scripts'); ?>
<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
<script>try{iFrameResize({checkOrigin:false},'#paytriframe');}catch(e){}</script>
<?php $__view->stop(); ?>
