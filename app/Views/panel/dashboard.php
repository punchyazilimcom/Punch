<?php $v = $__view; ?>
<div class="kpi-grid">
    <?php
    $cards = [
        ['Aktif Paket', $kpis['active_packages'], 'package'],
        ['Fatura', $kpis['invoices'], 'file-text'],
        ['Acik Talep', $kpis['open_tickets'], 'message-square'],
        ['Teklif', $kpis['quotes'], 'credit-card'],
    ];
    foreach ($cards as [$label, $value, $icon]): ?>
    <div class="card kpi">
        <span class="kpi-icon"><?= $v->partial('partials/icon', ['name' => $icon, 'size' => 22]) ?></span>
        <div class="kpi-label"><?= e($label) ?></div>
        <div class="kpi-value"><?= (int)$value ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid-2" style="align-items:start">
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)">Son Siparisler</h2>
            <a href="/panel/paketlerim" class="text-accent text-sm">Tumu</a>
        </div>
        <?php if (empty($orders)): ?>
            <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'package', 'size' => 40]) ?><p>Henuz siparisiniz yok.</p><a href="/paketler" class="btn btn-sm mt-4">Paketleri Gor</a></div>
        <?php else: ?>
        <div class="table-wrap" style="border:0">
            <table class="data">
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?= e($o['package_title'] ?? 'Paket') ?></td>
                        <td><?= price_format($o['amount'], $o['currency']) ?></td>
                        <td><?= status_badge($o['status']) ?></td>
                        <td class="text-muted text-xs"><?= format_date($o['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)">Son Faturalar</h2>
            <a href="/panel/faturalar" class="text-accent text-sm">Tumu</a>
        </div>
        <?php if (empty($invoices)): ?>
            <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'file-text', 'size' => 40]) ?><p>Henuz faturaniz yok.</p></div>
        <?php else: ?>
        <div class="table-wrap" style="border:0">
            <table class="data">
                <tbody>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><a href="/panel/faturalar/<?= (int)$inv['id'] ?>/pdf" target="_blank"><?= e($inv['invoice_no']) ?></a></td>
                        <td><?= price_format($inv['amount'], $inv['currency']) ?></td>
                        <td><?= status_badge($inv['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-5 flex justify-between items-center flex-wrap gap-4">
    <div>
        <h3 style="font-size:var(--fs-lg)">Yardima mi ihtiyaciniz var?</h3>
        <p class="text-soft text-sm mt-2">Destek ekibimiz size yardimci olmak icin hazir.</p>
    </div>
    <a href="/panel/destek/yeni" class="btn">Destek Talebi Olustur</a>
</div>
