<?php $v = $__view; ?>
<div class="flex justify-between items-center flex-wrap gap-3 mb-5">
    <div class="filter-bar" style="margin:0">
        <a class="filter-chip<?= !$status ? ' active' : '' ?>" href="/admin/siparisler">Tumu</a>
        <a class="filter-chip<?= $status==='paid'?' active':'' ?>" href="/admin/siparisler?status=paid">Odenen</a>
        <a class="filter-chip<?= $status==='pending'?' active':'' ?>" href="/admin/siparisler?status=pending">Bekleyen</a>
        <a class="filter-chip<?= $status==='failed'?' active':'' ?>" href="/admin/siparisler?status=failed">Basarisiz</a>
    </div>
    <a href="/admin/disa-aktar/siparisler" class="btn btn-ghost">CSV Disa Aktar</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Siparis No</th><th>Musteri</th><th>Paket</th><th>Tutar</th><th>Durum</th><th>Tarih</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td class="text-muted text-xs"><?= e($o['merchant_oid']) ?></td>
                    <td><?= e($o['user_name'] ?? '—') ?><div class="text-muted text-xs"><?= e($o['user_email'] ?? '') ?></div></td>
                    <td><?= e($o['package_title'] ?? '—') ?></td>
                    <td><?= price_format($o['amount'], $o['currency']) ?></td>
                    <td><?= status_badge($o['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($o['created_at'], 'd.m.Y H:i') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?><tr><td colspan="6" class="text-center text-muted">Siparis yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
