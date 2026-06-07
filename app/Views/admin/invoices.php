<?php $v = $__view; ?>
<div class="flex justify-between items-center mb-5">
    <a href="/admin/disa-aktar/faturalar" class="btn btn-ghost">CSV Disa Aktar</a>
    <a href="/admin/faturalar/yeni" class="btn">Yeni Fatura</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Fatura No</th><th>Musteri</th><th>Tutar</th><th>KDV</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($invoices as $i): ?>
                <tr>
                    <td class="text-bright"><?= e($i['invoice_no']) ?></td>
                    <td><?= e($i['user_name'] ?? '—') ?><div class="text-muted text-xs"><?= e($i['user_email'] ?? '') ?></div></td>
                    <td><?= price_format($i['amount'], $i['currency']) ?></td>
                    <td class="text-muted"><?= price_format($i['tax_amount'], $i['currency']) ?></td>
                    <td><?= status_badge($i['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($i['issued_at']) ?></td>
                    <td><a href="/admin/faturalar/<?= (int)$i['id'] ?>/pdf" target="_blank" class="btn btn-ghost btn-sm">PDF</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?><tr><td colspan="7" class="text-center text-muted">Fatura yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
