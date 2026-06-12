<?php $v = $__view; ?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Hakkimizda',
    'title' => 'Biz <span class="text-gradient">Punch Yazilim</span>',
    'subtitle' => 'Ankara merkezli, deneyim odakli bir dijital yazilim ajansiyiz. Markalari dijitalde one cikaracak cozumler uretiyoruz.',
    'crumbs' => ['Ana Sayfa' => '/', 'Hakkimizda' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container split" style="align-items:start">
        <div class="prose" data-reveal>
            <h2>Hikayemiz</h2>
            <p>Punch Yazilim, dijital dunyada fark yaratmak isteyen markalar icin kuruldu. Sablon cozumlerin otesine gecerek, her projeyi markaya ozel tasarliyor ve gelistiriyoruz.</p>
            <p>Tasarim, yazilim ve pazarlamayi bir araya getirerek; hizli, guvenli ve donusum odakli dijital deneyimler insa ediyoruz.</p>
            <h2>Degerlerimiz</h2>
            <ul>
                <li><strong>Kalite:</strong> Her detay kasitli, her piksel anlamli.</li>
                <li><strong>Seffaflik:</strong> Sureci acik ve net yurutuyoruz.</li>
                <li><strong>Hiz:</strong> Performanstan asla odun vermiyoruz.</li>
                <li><strong>Sureklilik:</strong> Teslimat sonu degil, basi.</li>
            </ul>
        </div>
        <div class="card" data-reveal data-tilt>
            <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => 'rocket']) ?></div>
            <h3>Misyonumuz</h3>
            <p class="text-soft text-sm mt-3">Markalarin dijital potansiyelini en ust seviyeye tasimak; teknoloji ve tasarimi bir araya getirerek olculebilir is sonuclari uretmek.</p>
            <hr class="divider">
            <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => 'star']) ?></div>
            <h3>Vizyonumuz</h3>
            <p class="text-soft text-sm mt-3">Turkiye'nin en cok tercih edilen, "dunyada tek" deneyimler ureten dijital yazilim ajansi olmak.</p>
        </div>
    </div>
</section>

<?php if (!empty($stats)): ?>
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="stat-grid">
            <?php foreach ($stats as $s): ?>
            <div class="stat card text-center" data-reveal>
                <div class="stat-value mx-auto"><?= e($s['value']) ?></div>
                <div class="stat-label"><?= e($s['label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?= $v->partial('partials/cta-section') ?>
