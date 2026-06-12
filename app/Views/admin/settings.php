<?php $v = $__view; ?>
<div class="grid-2" style="align-items:start">
    <form action="/admin/ayarlar" method="post" class="card" data-guard>
        <?= csrf_field() ?>
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Site Bilgileri</h2>
        <div class="form-group"><label class="form-label">Site Basligi</label><input class="form-control" name="site_title" value="<?= e($s->get('site_title', '')) ?>"></div>
        <div class="form-group"><label class="form-label">Slogan</label><input class="form-control" name="site_tagline" value="<?= e($s->get('site_tagline', '')) ?>"></div>

        <h3 style="font-size:var(--fs-base)" class="mb-3 mt-5">Iletisim</h3>
        <div class="form-group"><label class="form-label">Telefon</label><input class="form-control" name="contact_phone" value="<?= e($s->get('contact_phone', '')) ?>"></div>
        <div class="form-group"><label class="form-label">WhatsApp (uluslararasi, +90…)</label><input class="form-control" name="contact_whatsapp" value="<?= e($s->get('contact_whatsapp', '')) ?>"></div>
        <div class="form-group"><label class="form-label">E-posta</label><input class="form-control" name="contact_email" value="<?= e($s->get('contact_email', '')) ?>"></div>
        <div class="form-group"><label class="form-label">Adres</label><input class="form-control" name="contact_address" value="<?= e($s->get('contact_address', '')) ?>"></div>
        <div class="form-group"><label class="form-label">Harita Embed (iframe HTML)</label><textarea class="form-control" name="contact_maps_embed" style="min-height:80px"><?= e($s->get('contact_maps_embed', '')) ?></textarea></div>

        <h3 style="font-size:var(--fs-base)" class="mb-3 mt-5">Sosyal Medya</h3>
        <div class="form-group"><label class="form-label">Instagram</label><input class="form-control" name="social_instagram" value="<?= e($s->get('social_instagram', '')) ?>"></div>
        <div class="form-group"><label class="form-label">LinkedIn</label><input class="form-control" name="social_linkedin" value="<?= e($s->get('social_linkedin', '')) ?>"></div>
        <div class="form-group"><label class="form-label">X (Twitter)</label><input class="form-control" name="social_x" value="<?= e($s->get('social_x', '')) ?>"></div>
        <div class="form-group"><label class="form-label">YouTube</label><input class="form-control" name="social_youtube" value="<?= e($s->get('social_youtube', '')) ?>"></div>

        <h3 style="font-size:var(--fs-base)" class="mb-3 mt-5">SEO & Analitik</h3>
        <div class="form-group"><label class="form-label">Varsayilan Meta Baslik</label><input class="form-control" name="seo_default_title" value="<?= e($s->get('seo_default_title', '')) ?>"></div>
        <div class="form-group"><label class="form-label">Varsayilan Meta Aciklama</label><textarea class="form-control" name="seo_default_description" style="min-height:70px"><?= e($s->get('seo_default_description', '')) ?></textarea></div>
        <div class="form-group"><label class="form-label">Google Analytics 4 ID</label><input class="form-control" name="ga4_id" value="<?= e($s->get('ga4_id', '')) ?>" placeholder="G-XXXXXXX"></div>
        <div class="form-group"><label class="form-label">Google Site Dogrulama</label><input class="form-control" name="google_site_verification" value="<?= e($s->get('google_site_verification', '')) ?>"></div>

        <button class="btn btn-lg">Ayarlari Kaydet</button>
    </form>

    <div class="flex flex-col gap-4">
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-4">Entegrasyon Durumu</h3>
            <p class="text-sm text-muted mb-2">Bu degerler guvenlik geregi yalniz <code>.env</code> dosyasindan okunur.</p>
            <div class="flex justify-between items-center mb-3"><span class="text-soft">SMTP / Mail</span><?= $envStatus['mail'] ? status_badge('active') : status_badge('passive') ?></div>
            <div class="flex justify-between items-center mb-3"><span class="text-soft">PayTR</span><?= $envStatus['paytr'] ? status_badge('active') : status_badge('passive') ?></div>
            <div class="flex justify-between items-center"><span class="text-soft">PayTR Test Modu</span><?= $envStatus['test_mode'] ? '<span class="badge badge-warning">Acik (test)</span>' : '<span class="badge badge-success">Kapali (canli)</span>' ?></div>
        </div>
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Hizli Baglantilar</h3>
            <div class="flex flex-col gap-2">
                <a href="/admin/icerik" class="btn btn-ghost btn-sm">Sayfa Iceriklerini Duzenle</a>
                <a href="/sitemap.xml" target="_blank" class="btn btn-ghost btn-sm">Sitemap'i Goruntule</a>
                <a href="/admin/loglar" class="btn btn-ghost btn-sm">Denetim Loglari</a>
            </div>
        </div>
    </div>
</div>
