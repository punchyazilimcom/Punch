<?php
$v = $__view;
$phone = setting('contact_phone', $company['phone'] ?? '');
$email = setting('contact_email', 'destek@punchyazilim.com');
$address = setting('contact_address', 'Yenimahalle, Ankara');
$wa = setting('contact_whatsapp', '');
$maps = setting('contact_maps_embed', '');
?>
<?= $v->partial('partials/page-header', [
    'eyebrow' => 'Iletisim',
    'title' => 'Hadi <span class="text-gradient">konusalim</span>',
    'subtitle' => 'Projeniz, sorulariniz veya is birligi icin bize ulasin. En kisa surede donus yapariz.',
    'crumbs' => ['Ana Sayfa' => '/', 'Iletisim' => ''],
]) ?>

<section class="section" style="padding-top:0">
    <div class="container split" style="align-items:start">
        <div data-reveal>
            <?= $v->partial('partials/flash') ?>
            <form action="/iletisim" method="post" class="card" data-guard>
                <?= csrf_field() ?>
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
                <div class="form-group">
                    <label class="form-label" for="c_name">Ad Soyad *</label>
                    <input class="form-control<?= has_error('name') ?>" id="c_name" name="name" value="<?= old('name') ?>" required>
                    <?= field_error('name') ?>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="c_email">E-posta *</label>
                        <input class="form-control<?= has_error('email') ?>" id="c_email" type="email" name="email" value="<?= old('email') ?>" required>
                        <?= field_error('email') ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="c_phone">Telefon</label>
                        <input class="form-control" id="c_phone" name="phone" value="<?= old('phone') ?>" placeholder="05xx xxx xx xx">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="c_subject">Konu</label>
                    <input class="form-control" id="c_subject" name="subject" value="<?= old('subject') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="c_message">Mesajiniz *</label>
                    <textarea class="form-control<?= has_error('message') ?>" id="c_message" name="message" required><?= old('message') ?></textarea>
                    <?= field_error('message') ?>
                </div>
                <button type="submit" class="btn btn-block btn-lg">Mesaji Gonder</button>
            </form>
        </div>

        <aside class="flex flex-col gap-4" data-reveal>
            <div class="card flex items-center gap-4">
                <div class="feature-icon" style="margin:0"><?= $v->partial('partials/icon', ['name' => 'mail']) ?></div>
                <div><div class="text-muted text-xs">E-posta</div><a href="mailto:<?= e($email) ?>" class="text-bright"><?= e($email) ?></a></div>
            </div>
            <?php if ($phone): ?>
            <div class="card flex items-center gap-4">
                <div class="feature-icon" style="margin:0"><?= $v->partial('partials/icon', ['name' => 'phone']) ?></div>
                <div><div class="text-muted text-xs">Telefon</div><a href="tel:<?= e(preg_replace('/\s+/','',$phone)) ?>" class="text-bright"><?= e($phone) ?></a></div>
            </div>
            <?php endif; ?>
            <div class="card flex items-center gap-4">
                <div class="feature-icon" style="margin:0"><?= $v->partial('partials/icon', ['name' => 'map-pin']) ?></div>
                <div><div class="text-muted text-xs">Adres</div><div class="text-bright"><?= e($address) ?></div></div>
            </div>
            <?php if ($wa): ?>
            <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" rel="noopener" class="card flex items-center gap-4" style="text-decoration:none">
                <div class="feature-icon" style="margin:0"><?= $v->partial('partials/icon', ['name' => 'message-square']) ?></div>
                <div><div class="text-muted text-xs">WhatsApp</div><div class="text-bright">Hemen yazin</div></div>
            </a>
            <?php endif; ?>
            <?php if ($maps): ?>
            <div class="card overflow-hidden" style="padding:0"><?= $maps /* yonetici girisi embed */ ?></div>
            <?php endif; ?>
        </aside>
    </div>
</section>
