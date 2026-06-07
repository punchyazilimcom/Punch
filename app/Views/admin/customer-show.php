<?php $v = $__view; ?>
<a href="/admin/musteriler" class="btn btn-ghost btn-sm mb-5">← Musteriler</a>
<div class="grid-2" style="align-items:start">
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)"><?= e($customer['name']) ?></h2>
            <?= status_badge($customer['status']) ?>
        </div>
        <table class="data" style="width:100%">
            <tr><td class="text-muted">E-posta</td><td><?= e($customer['email']) ?></td></tr>
            <tr><td class="text-muted">Telefon</td><td><?= e($customer['phone'] ?? '—') ?></td></tr>
            <tr><td class="text-muted">Firma</td><td><?= e($customer['company'] ?? '—') ?></td></tr>
            <tr><td class="text-muted">Vergi/TC</td><td><?= e($customer['tax_no'] ?: $customer['tc_no'] ?: '—') ?></td></tr>
            <tr><td class="text-muted">Adres</td><td><?= e($customer['address'] ?? '—') ?> <?= e($customer['city'] ?? '') ?></td></tr>
            <tr><td class="text-muted">Kayit</td><td><?= format_date($customer['created_at'], 'd.m.Y H:i') ?></td></tr>
        </table>
        <form action="/admin/musteriler/<?= (int)$customer['id'] ?>/durum" method="post" class="mt-4">
            <?= csrf_field() ?>
            <button class="btn btn-ghost btn-sm"><?= $customer['status'] === 'active' ? 'Pasife Al' : 'Aktif Et' ?></button>
        </form>
    </div>

    <div class="flex flex-col gap-4">
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Siparisler (<?= count($orders) ?>)</h3>
            <?php foreach (array_slice($orders, 0, 6) as $o): ?>
            <div class="flex justify-between items-center mb-2 text-sm">
                <span><?= e($o['package_title'] ?? 'Paket') ?></span>
                <span><?= price_format($o['amount'], $o['currency']) ?> <?= status_badge($o['status']) ?></span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?><p class="text-muted text-sm">Yok</p><?php endif; ?>
        </div>
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Faturalar (<?= count($invoices) ?>)</h3>
            <?php foreach (array_slice($invoices, 0, 6) as $i): ?>
            <div class="flex justify-between items-center mb-2 text-sm">
                <a href="/admin/faturalar/<?= (int)$i['id'] ?>/pdf" target="_blank" class="text-accent"><?= e($i['invoice_no']) ?></a>
                <span><?= price_format($i['amount']) ?> <?= status_badge($i['status']) ?></span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?><p class="text-muted text-sm">Yok</p><?php endif; ?>
        </div>
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Destek Talepleri (<?= count($tickets) ?>)</h3>
            <?php foreach (array_slice($tickets, 0, 6) as $t): ?>
            <div class="flex justify-between items-center mb-2 text-sm">
                <a href="/admin/destek/<?= (int)$t['id'] ?>" class="text-accent"><?= e($t['subject']) ?></a>
                <?= status_badge($t['status']) ?>
            </div>
            <?php endforeach; ?>
            <?php if (empty($tickets)): ?><p class="text-muted text-sm">Yok</p><?php endif; ?>
        </div>
    </div>
</div>
