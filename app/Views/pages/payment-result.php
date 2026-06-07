<?php $v = $__view; ?>
<section class="section" style="padding-top:calc(var(--header-h) + 4rem);min-height:70svh;display:grid;place-items:center;text-align:center">
    <div class="hero-orb hero-orb-1" style="opacity:0.3"></div>
    <div class="container" style="position:relative;z-index:1;max-width:600px">
        <?php if ($success): ?>
            <div class="feature-icon mx-auto" style="width:80px;height:80px;background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.4);color:#4ade80">
                <?= $v->partial('partials/icon', ['name' => 'check', 'size' => 40]) ?>
            </div>
            <h1 class="mt-5">Odemeniz alindi!</h1>
            <p class="text-soft mt-4 mx-auto">Tesekkur ederiz. Odemeniz onaylandiginda faturaniz olusturulacak ve e-posta ile bilgilendirileceksiniz. Detaylari panelinizden takip edebilirsiniz.</p>
            <div class="flex justify-center gap-4 mt-8 flex-wrap">
                <a href="/panel" class="btn btn-lg">Panele Git</a>
                <a href="/panel/faturalar" class="btn btn-ghost btn-lg">Faturalarim</a>
            </div>
        <?php else: ?>
            <div class="feature-icon mx-auto" style="width:80px;height:80px;background:rgba(239,68,68,0.15);border-color:rgba(239,68,68,0.4);color:#f87171">
                <?= $v->partial('partials/icon', ['name' => 'plus', 'size' => 40]) ?>
            </div>
            <h1 class="mt-5">Odeme tamamlanamadi</h1>
            <p class="text-soft mt-4 mx-auto">Odeme isleminiz basarisiz oldu veya iptal edildi. Hesabinizdan herhangi bir cekim yapilmadi. Dilerseniz tekrar deneyebilirsiniz.</p>
            <div class="flex justify-center gap-4 mt-8 flex-wrap">
                <a href="/paketler" class="btn btn-lg">Paketlere Don</a>
                <a href="/iletisim" class="btn btn-ghost btn-lg">Yardim Al</a>
            </div>
        <?php endif; ?>
    </div>
</section>
