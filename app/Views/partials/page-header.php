<?php
/** @var string $title  @var string $subtitle  @var array $crumbs (ad=>url) */
$crumbs = $crumbs ?? [];
$subtitle = $subtitle ?? '';
$eyebrow = $eyebrow ?? '';
?>
<section class="section-sm" style="padding-top:calc(var(--header-h) + 3rem);position:relative;overflow:hidden">
    <div class="hero-orb hero-orb-1" style="opacity:0.3"></div>
    <div class="container" style="position:relative;z-index:1">
        <?php if ($crumbs): ?>
        <nav class="breadcrumb" aria-label="Konum">
            <?php $i = 0; $n = count($crumbs); foreach ($crumbs as $label => $href): $i++; ?>
                <?php if ($i < $n && $href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><span>/</span>
                <?php else: ?><span style="color:var(--text-2);opacity:1"><?= e($label) ?></span><?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>
        <?php if ($eyebrow): ?><span class="eyebrow" data-reveal><?= e($eyebrow) ?></span><?php endif; ?>
        <h1 data-reveal style="margin-top:var(--sp-3);max-width:18ch"><?= $title ?></h1>
        <?php if ($subtitle): ?><p class="section-lead" data-reveal style="max-width:60ch"><?= e($subtitle) ?></p><?php endif; ?>
    </div>
</section>
