<header class="site-header">
    <div class="container">
        <a href="/" class="brand" aria-label="Punch Yazilim ana sayfa">
            <?= $__view->partial('partials/logo') ?>
            <span>Punch<span style="color:var(--punch-violet-400)">.</span></span>
        </a>

        <nav class="nav" aria-label="Ana menu">
            <a href="/hizmetler"<?= is_active_path('/hizmetler') ?>>Hizmetler</a>
            <a href="/paketler"<?= is_active_path('/paketler') ?>>Paketler</a>
            <a href="/portfolyo"<?= is_active_path('/portfolyo') ?>>Portfolyo</a>
            <a href="/blog"<?= is_active_path('/blog') ?>>Blog</a>
            <a href="/hakkimizda"<?= is_active_path('/hakkimizda') ?>>Hakkimizda</a>
            <a href="/iletisim"<?= is_active_path('/iletisim') ?>>Iletisim</a>
        </nav>

        <div class="header-actions">
            <?php if (\App\Core\Auth::check(\App\Core\Auth::GUARD_USER)): ?>
                <a href="/panel" class="btn btn-sm" data-magnetic="0.25">Panelim</a>
            <?php else: ?>
                <a href="/giris" class="btn btn-ghost btn-sm">Giris</a>
                <a href="/paketler" class="btn btn-sm" data-magnetic="0.25">Hemen Basla</a>
            <?php endif; ?>
            <button class="nav-toggle" aria-label="Menuyu ac/kapat" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
