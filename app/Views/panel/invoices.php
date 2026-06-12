<?php $v = $__view; ?>
<div class="card">
    <?php if (empty($invoices)): ?>
        <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'file-text', 'size' => 48]) ?><p>Henuz faturaniz bulunmuyor.</p></div>
    <?php else: ?>
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Fatura No</th><th>Tutar</th><th>KDV</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td class="text-bright"><?= e($inv['invoice_no']) ?></td>
                    <td><?= price_format($inv['amount'], $inv['currency']) ?></td>
                    <td class="text-muted"><?= price_format($inv['tax_amount'], $inv['currency']) ?></td>
                    <td><?= status_badge($inv['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($inv['issued_at']) ?></td>
                    <td><a href="/panel/faturalar/<?= (int)$inv['id'] ?>/pdf" target="_blank" class="btn btn-ghost btn-sm">PDF Indir</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
