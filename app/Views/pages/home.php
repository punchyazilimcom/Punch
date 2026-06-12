<?php
use App\Models\Package;
$v = $__view;
?>
<!-- ============ HERO ============ -->
<section class="hero">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="container">
        <div class="hero-inner">
            <span class="eyebrow" data-hero-fade><?= e(setting('hero_eyebrow', 'Ankara · Dijital Yazilim & Tasarim Ajansi')) ?></span>
            <h1 data-split>
                <?php
                $title = setting('hero_title', 'Markanizi dijitalde fark yaratan deneyimlere donusturuyoruz');
                $words = preg_split('/\s+/', $title);
                $mid = (int) floor(count($words) / 2);
                foreach ($words as $i => $word):
                    // Son iki kelimeyi holografik vurgu ile one cikar
                    $cls = ($i >= count($words) - 2) ? 'holo' : '';
                ?>
                <span class="reveal-word"><span class="<?= $cls ?>"><?= e($word) ?></span></span>
                <?php endforeach; ?>
            </h1>
            <p class="hero-lead" data-hero-fade><?= e(setting('hero_subtitle', 'Sablon degil, size ozel. Hizli yuklenen, Google\'da one cikan ve satisa donusen web siteleri, e-ticaret altyapilari ve sosyal medya yonetimi. Stratejiden yayina kadar tek catida.')) ?></p>
            <div class="hero-cta" data-hero-fade>
                <a href="/paketler" class="btn btn-lg btn-glow" data-magnetic="0.3" data-cursor="Kesfet">Paketleri Kesfet <?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 18]) ?></a>
                <a href="/iletisim" class="btn btn-ghost btn-lg" data-magnetic="0.2">Ucretsiz Strateji Gorusmesi</a>
            </div>
            <div class="hero-cta" data-hero-fade style="margin-top:var(--sp-6);gap:var(--sp-3)">
                <span class="tech-pill"><?= $v->partial('partials/icon', ['name' => 'shield', 'size' => 16]) ?> SSL & KVKK uyumlu</span>
                <span class="tech-pill"><?= $v->partial('partials/icon', ['name' => 'zap', 'size' => 16]) ?> 90+ Lighthouse hedefi</span>
                <span class="tech-pill"><?= $v->partial('partials/icon', ['name' => 'credit-card', 'size' => 16]) ?> Guvenli PayTR odeme</span>
            </div>
        </div>
    </div>
</section>

<!-- ============ GUVEN BANDI (marquee) ============ -->
<div class="section-sm marquee" aria-hidden="true">
    <div class="marquee-track">
        <span>Web Tasarim</span><span>•</span><span>E-Ticaret</span><span>•</span><span>Sosyal Medya</span><span>•</span><span>SEO</span><span>•</span><span>Ozel Yazilim</span><span>•</span><span>UI / UX</span><span>•</span>
        <span>Web Tasarim</span><span>•</span><span>E-Ticaret</span><span>•</span><span>Sosyal Medya</span><span>•</span><span>SEO</span><span>•</span><span>Ozel Yazilim</span><span>•</span><span>UI / UX</span><span>•</span>
    </div>
</div>

<!-- ============ ISTATISTIK ============ -->
<?php if (!empty($stats)): ?>
<section class="section-sm">
    <div class="container">
        <div class="stat-grid">
            <?php foreach ($stats as $s): ?>
            <div class="stat" data-reveal>
                <div class="stat-value" data-countup="<?= e($s['value']) ?>"><?= e($s['value']) ?></div>
                <div class="stat-label"><?= e($s['label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ HIZMETLER ============ -->
<section class="section" id="hizmetler">
    <div class="container">
        <header class="mb-8" data-reveal>
            <span class="eyebrow">Hizmetlerimiz</span>
            <h2 class="section-title" data-chars>Ucu uca dijital <span class="text-gradient">cozum ortaginiz</span></h2>
            <p class="section-lead">Stratejiden tasarima, yazilimdan buyumeye markanizi her asamada destekliyoruz.</p>
        </header>
        <div class="feature-grid">
            <?php foreach ($services as $service): ?>
            <article class="card feature-card" data-reveal data-tilt>
                <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => $service['icon'] ?: 'zap']) ?></div>
                <h3><?= e($service['title']) ?></h3>
                <p class="text-soft text-sm"><?= e($service['short_desc']) ?></p>
                <a href="/hizmetler/<?= e($service['slug']) ?>" class="text-accent text-sm mt-4 inline-flex items-center gap-2" style="display:inline-flex">
                    Detaylar <?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 16]) ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ PAKETLER ============ -->
<?php if (!empty($packages)): ?>
<section class="section" id="paketler">
    <div class="container">
        <header class="mb-8" data-reveal>
            <span class="eyebrow">Paketler</span>
            <h2 class="section-title" data-chars>Ihtiyaciniza gore <span class="text-gradient">net fiyatlar</span></h2>
            <p class="section-lead">Sabit fiyatli paketleri online satin alin, ozel ihtiyaclar icin teklif isteyin.</p>
        </header>
        <div class="pricing-grid">
            <?php foreach ($packages as $pkg): $features = Package::features($pkg); ?>
            <article class="card pricing-card<?= !empty($pkg['is_popular']) ? ' is-popular glow-border' : '' ?>" data-reveal>
                <?php if (!empty($pkg['is_popular'])): ?><span class="pricing-badge">Populer</span><?php endif; ?>
                <div class="feature-icon"><?= $v->partial('partials/icon', ['name' => $pkg['icon'] ?: 'package']) ?></div>
                <h3><?= e($pkg['title']) ?></h3>
                <p class="text-soft text-sm"><?= e($pkg['short_desc']) ?></p>
                <?php if (!empty($pkg['is_quote_only'])): ?>
                    <div class="price quote">Teklife Ozel</div>
                <?php else: ?>
                    <div class="price"><?= price_format($pkg['price'], $pkg['currency']) ?> <small>+ KDV dahil</small></div>
                <?php endif; ?>
                <ul class="feature-list">
                    <?php foreach (array_slice($features, 0, 5) as $f): ?>
                    <li><?= $v->partial('partials/icon', ['name' => 'check', 'size' => 18]) ?> <span><?= e($f) ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/paketler/<?= e($pkg['slug']) ?>" class="btn btn-block">
                    <?= !empty($pkg['is_quote_only']) ? 'Teklif Iste' : 'Incele & Satin Al' ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ NASIL CALISIR (pinned storytelling) ============ -->
<?php
$steps = [
    ['Kesif & Strateji', 'Hedeflerinizi, hedef kitlenizi ve rakiplerinizi dinler; projeyi olculebilir is hedeflerine baglayan net bir yol haritasi cikaririz.'],
    ['Tasarim', 'Markanizin ruhunu yansitan, ziyaretciyi aksiyona yonlendiren ozel arayuzler tasarlar; onayinizi adim adim aliriz.'],
    ['Gelistirme', 'Hizli, guvenli ve SEO uyumlu kodlama ile tasarimi hayata geciririz; her cihazda ve her tarayicida titizlikle test ederiz.'],
    ['Yayin & Buyume', 'Siteyi yayina alir, analitigi kurar; verilerle olcup surekli iyilestirerek markanizi buyutmeye devam ederiz.'],
];
?>
<section class="section story" data-story>
    <div class="container">
        <div class="story-grid">
            <div class="story-sticky" data-story-sticky>
                <span class="eyebrow">Surec</span>
                <div class="story-num" data-story-num>01</div>
                <div class="story-title" data-story-title><?= e($steps[0][0]) ?></div>
                <p class="section-lead">Projeyi dort net adimda, seffaf bir sekilde hayata geciriyoruz. Asagi kaydirin, sureci birlikte gorelim.</p>
                <div class="story-progress"><div class="story-fill" data-story-fill></div></div>
            </div>
            <div class="story-steps">
                <?php foreach ($steps as $i => $step): ?>
                <div class="story-step<?= $i === 0 ? ' active' : '' ?>" data-story-step data-step-title="<?= e($step[0]) ?>">
                    <div class="section-index"><?= sprintf('%02d', $i + 1) ?> / <?= sprintf('%02d', count($steps)) ?></div>
                    <h3 class="mt-3"><?= e($step[0]) ?></h3>
                    <p class="text-soft"><?= e($step[1]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============ ÖLÇEKLENEBİLİR ALTYAPI ============ -->
<section class="section cosmic-planet">
    <div class="container split">
        <div class="planet-wrap" data-reveal></div>
        <div data-reveal>
            <span class="section-index">Ölçeklenebilir altyapı</span>
            <h2 class="section-title mt-3">Bugüne değil, <span class="text-gradient">yarına</span> göre kuruyoruz</h2>
            <p class="section-lead">Kurduğumuz dijital altyapılar; bugünkü ihtiyacınıza değil, yarınki büyümenize göre tasarlanır. Trafik artsa da, ürün sayısı katlansa da sisteminiz sarsılmadan ölçeklenir.</p>
            <ul class="feature-list mt-5" style="max-width:46ch">
                <li><?= $v->partial('partials/icon', ['name' => 'zap', 'size' => 18]) ?> <span>Saniyenin altında açılan, CDN destekli hızlı altyapı</span></li>
                <li><?= $v->partial('partials/icon', ['name' => 'shield', 'size' => 18]) ?> <span>Kurumsal seviye güvenlik ve KVKK uyumu</span></li>
                <li><?= $v->partial('partials/icon', ['name' => 'rocket', 'size' => 18]) ?> <span>Büyümeye hazır, modülleri eklenebilir mimari</span></li>
            </ul>
            <a href="/iletisim" class="btn mt-6" data-magnetic="0.25">Hemen Başlayın <?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 18]) ?></a>
        </div>
    </div>
</section>

<!-- ============ PORTFOLYO ============ -->
<?php if (!empty($works)): ?>
<section class="section">
    <div class="container">
        <header class="flex justify-between items-center flex-wrap gap-4 mb-8" data-reveal>
            <div>
                <span class="eyebrow">Secilmis Isler</span>
                <h2 class="section-title">Gercek sonuclar, <span class="text-gradient">gercek projeler</span></h2>
            </div>
            <a href="/portfolyo" class="btn btn-ghost">Tum Projeler <?= $v->partial('partials/icon', ['name' => 'arrow-up-right', 'size' => 16]) ?></a>
        </header>
        <div class="work-grid">
            <?php foreach ($works as $work): ?>
            <a href="/portfolyo/<?= e($work['slug']) ?>" class="work-card tilt-shine" data-reveal>
                <div class="work-media" data-reveal-mask></div>
                <div class="work-meta">
                    <span class="work-cat"><?= e($work['category']) ?></span>
                    <h3><?= e($work['title']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ YORUMLAR (sinematik slider) ============ -->
<?php if (!empty($testimonials)): ?>
<section class="section">
    <div class="container" style="max-width:900px">
        <header class="text-center mb-8 mx-auto" data-reveal>
            <span class="eyebrow mx-auto" style="justify-content:center">Musteri Yorumlari</span>
            <h2 class="section-title mx-auto" data-chars style="max-width:none">Bizimle calisanlar <span class="text-gradient">ne diyor?</span></h2>
        </header>
        <div class="tslider" data-slider data-reveal>
            <div class="tslider-track" data-slider-track>
                <?php foreach ($testimonials as $t): ?>
                <div class="tslide">
                    <blockquote class="card cosmic text-center">
                        <div class="stars" style="justify-content:center;display:flex"><?= str_repeat('★', (int)($t['rating'] ?? 5)) ?></div>
                        <p class="text-bright mt-4">“<?= e($t['text']) ?>”</p>
                        <div class="who mt-5" style="align-items:center">
                            <strong class="text-accent"><?= e($t['name']) ?></strong>
                            <span><?= e($t['company'] ?? '') ?></span>
                        </div>
                    </blockquote>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="slider-nav">
                <button class="slider-btn" data-slider-prev aria-label="Onceki" data-cursor="Geri"><?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 18]) ?><span class="sr-only">Onceki</span></button>
                <div class="slider-dots" data-slider-dots></div>
                <button class="slider-btn" data-slider-next aria-label="Sonraki" data-cursor="Ileri"><?= $v->partial('partials/icon', ['name' => 'arrow-right', 'size' => 18]) ?></button>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ BLOG ============ -->
<?php if (!empty($posts)): ?>
<section class="section">
    <div class="container">
        <header class="flex justify-between items-center flex-wrap gap-4 mb-8" data-reveal>
            <div>
                <span class="eyebrow">Blog</span>
                <h2 class="section-title">Dijital dunyadan <span class="text-gradient">notlar</span></h2>
            </div>
            <a href="/blog" class="btn btn-ghost">Tum Yazilar</a>
        </header>
        <div class="grid-3">
            <?php foreach ($posts as $post): ?>
            <article class="card" data-reveal>
                <span class="badge badge-info"><?= e($post['category_name'] ?? 'Blog') ?></span>
                <h3 style="font-size:var(--fs-lg);margin-top:var(--sp-4)"><a href="/blog/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
                <p class="text-soft text-sm mt-3"><?= e(str_excerpt($post['excerpt'] ?? '', 110)) ?></p>
                <div class="text-muted text-xs mt-4"><?= format_date($post['published_at']) ?> · <?= (int)$post['reading_time'] ?> dk okuma</div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ SSS ============ -->
<?php if (!empty($faqs)): ?>
<section class="section" id="sss">
    <div class="container split" style="align-items:start">
        <div data-reveal>
            <span class="eyebrow">SSS</span>
            <h2 class="section-title">Sik sorulan <span class="text-gradient">sorular</span></h2>
            <p class="section-lead">Aklinizda baska soru varsa bize ulasin, hizlica yanitlayalim.</p>
            <a href="/iletisim" class="btn mt-5">Bize Ulasin</a>
        </div>
        <div data-reveal>
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="faq-item<?= $i === 0 ? ' open' : '' ?>">
                <button class="faq-q" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                    <span><?= e($faq['q']) ?></span>
                    <span class="faq-icon"><?= $v->partial('partials/icon', ['name' => 'plus', 'size' => 22]) ?></span>
                </button>
                <div class="faq-a"<?= $i === 0 ? ' style="max-height:400px"' : '' ?>>
                    <div class="faq-a-inner"><?= e($faq['a']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ CTA ============ -->
<section class="section">
    <div class="container">
        <div class="cta-banner" data-reveal>
            <span class="eyebrow mx-auto" style="justify-content:center">Hazir misiniz?</span>
            <h2 class="mt-4">Projenizi birlikte <span class="text-gradient">hayata gecirelim</span></h2>
            <div class="flex justify-center gap-4 mt-8 flex-wrap">
                <a href="/paketler" class="btn btn-lg" data-magnetic="0.3">Paketleri Gor</a>
                <a href="/iletisim" class="btn btn-ghost btn-lg">Ucretsiz Gorusme</a>
            </div>
        </div>
    </div>
</section>
