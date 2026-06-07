<?php $v = $__view; ?>
<div class="card">
    <?php if (empty($orders)): ?>
        <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'package', 'size' => 48]) ?><p>Henuz bir paket satin almadiniz.</p><a href="/paketler" class="btn mt-4">Paketleri Kesfet</a></div>
    <?php else: ?>
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Paket</th><th>Tutar</th><th>Durum</th><th>Tarih</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td class="text-bright"><?= e($o['package_title'] ?? 'Paket') ?></td>
                    <td><?= price_format($o['amount'], $o['currency']) ?></td>
                    <td><?= status_badge($o['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($o['created_at'], 'd.m.Y H:i') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
