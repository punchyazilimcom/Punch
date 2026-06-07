<?php
$v = $__view;
$maxTrend = 0;
foreach ($trend as $t) { $maxTrend = max($maxTrend, (float) $t['total']); }
$maxTrend = $maxTrend ?: 1;
?>
<div class="kpi-grid">
    <div class="card kpi">
        <span class="kpi-icon"><?= $v->partial('partials/icon', ['name' => 'bar-chart', 'size' => 22]) ?></span>
        <div class="kpi-label">Toplam Ciro</div>
        <div class="kpi-value"><?= price_format($kpis['revenue_total']) ?></div>
    </div>
    <div class="card kpi">
        <span class="kpi-icon"><?= $v->partial('partials/icon', ['name' => 'credit-card', 'size' => 22]) ?></span>
        <div class="kpi-label">Bu Ay</div>
        <div class="kpi-value"><?= price_format($kpis['revenue_month']) ?></div>
    </div>
    <div class="card kpi">
        <span class="kpi-icon"><?= $v->partial('partials/icon', ['name' => 'users', 'size' => 22]) ?></span>
        <div class="kpi-label">Musteri</div>
        <div class="kpi-value"><?= (int)$kpis['customers'] ?></div>
    </div>
    <div class="card kpi">
        <span class="kpi-icon"><?= $v->partial('partials/icon', ['name' => 'message-square', 'size' => 22]) ?></span>
        <div class="kpi-label">Acik Talep</div>
        <div class="kpi-value"><?= (int)$kpis['open_tickets'] ?></div>
    </div>
</div>

<div class="grid-2" style="align-items:start">
    <div class="card">
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Gelir Trendi (6 ay)</h2>
        <?php if (empty($trend)): ?>
            <p class="text-muted text-sm">Henuz odeme verisi yok.</p>
        <?php else: ?>
        <div class="bars">
            <?php foreach ($trend as $t): $h = (int) round(((float)$t['total'] / $maxTrend) * 100); ?>
            <div class="bar" style="height:<?= max(4, $h) ?>%" title="<?= price_format($t['total']) ?>">
                <span><?= e(substr($t['ym'], 5)) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Paket Dagilimi</h2>
        <?php if (empty($distribution)): ?>
            <p class="text-muted text-sm">Henuz satis yok.</p>
        <?php else: ?>
            <?php foreach ($distribution as $d): ?>
            <div class="flex justify-between items-center mb-3">
                <span class="text-soft text-sm"><?= e($d['title']) ?></span>
                <span class="badge badge-info"><?= (int)$d['cnt'] ?> satis</span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-5">
    <div class="flex justify-between items-center mb-4">
        <h2 style="font-size:var(--fs-lg)">Son Siparisler</h2>
        <a href="/admin/siparisler" class="text-accent text-sm">Tumu</a>
    </div>
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Musteri</th><th>Paket</th><th>Tutar</th><th>Durum</th><th>Tarih</th></tr></thead>
            <tbody>
            <?php foreach ($recentOrders as $o): ?>
                <tr>
                    <td><?= e($o['user_name'] ?? '—') ?><div class="text-muted text-xs"><?= e($o['user_email'] ?? '') ?></div></td>
                    <td><?= e($o['package_title'] ?? '—') ?></td>
                    <td><?= price_format($o['amount'], $o['currency']) ?></td>
                    <td><?= status_badge($o['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($o['created_at'], 'd.m.Y H:i') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($recentOrders)): ?><tr><td colspan="5" class="text-muted text-center">Henuz siparis yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
