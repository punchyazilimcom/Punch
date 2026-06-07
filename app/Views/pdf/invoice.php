<?php
/** @var array $invoice @var array $user @var float $net @var float $tax @var array $company */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { font-family: DejaVu Sans, sans-serif; }
    body { color: #1a1024; font-size: 12px; }
    .head { width: 100%; border-bottom: 3px solid #7c3aed; padding-bottom: 12px; margin-bottom: 24px; }
    .brand { font-size: 24px; font-weight: bold; color: #7c3aed; }
    .muted { color: #666; }
    table { width: 100%; border-collapse: collapse; }
    .meta td { padding: 3px 0; vertical-align: top; }
    .items { margin-top: 24px; }
    .items th { background: #f3effe; text-align: left; padding: 10px; border-bottom: 2px solid #7c3aed; font-size: 11px; }
    .items td { padding: 10px; border-bottom: 1px solid #eee; }
    .totals { margin-top: 16px; width: 45%; float: right; }
    .totals td { padding: 6px 10px; }
    .totals .grand { background: #7c3aed; color: #fff; font-weight: bold; font-size: 14px; }
    .text-right { text-align: right; }
    .footer { clear: both; margin-top: 60px; padding-top: 16px; border-top: 1px solid #eee; color: #888; font-size: 10px; text-align: center; }
</style>
</head>
<body>
    <table class="head"><tr>
        <td><div class="brand">Punch. Yazilim</div><div class="muted"><?= e($company['address']) ?></div><div class="muted"><?= e($company['email']) ?></div></td>
        <td class="text-right">
            <div style="font-size:20px;font-weight:bold">FATURA</div>
            <div class="muted">No: <?= e($invoice['invoice_no']) ?></div>
            <div class="muted">Tarih: <?= format_date($invoice['issued_at']) ?></div>
        </td>
    </tr></table>

    <table class="meta"><tr>
        <td width="50%">
            <strong>Fatura Edilen:</strong><br>
            <?= e($user['name']) ?><br>
            <?php if (!empty($user['company'])): ?><?= e($user['company']) ?><br><?php endif; ?>
            <?php if (!empty($user['tax_no'])): ?>Vergi No: <?= e($user['tax_no']) ?><br><?php endif; ?>
            <?php if (!empty($user['tc_no'])): ?>TC No: <?= e($user['tc_no']) ?><br><?php endif; ?>
            <?= e($user['email']) ?><br>
            <?= e($user['address'] ?? '') ?> <?= e($user['city'] ?? '') ?>
        </td>
        <td width="50%" class="text-right">
            <strong>Durum:</strong> <?= $invoice['status'] === 'paid' ? 'Odendi' : 'Odenmedi' ?><br>
        </td>
    </tr></table>

    <table class="items">
        <thead><tr><th>Aciklama</th><th class="text-right">Tutar</th></tr></thead>
        <tbody>
            <tr><td><?= e($invoice['title'] ?: 'Hizmet Bedeli') ?></td><td class="text-right"><?= number_format($net, 2, ',', '.') ?> TL</td></tr>
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Ara Toplam</td><td class="text-right"><?= number_format($net, 2, ',', '.') ?> TL</td></tr>
        <tr><td>KDV (%<?= (int)($invoice['tax_rate'] ?? 20) ?>)</td><td class="text-right"><?= number_format($tax, 2, ',', '.') ?> TL</td></tr>
        <tr class="grand"><td>Genel Toplam</td><td class="text-right"><?= number_format((float)$invoice['amount'], 2, ',', '.') ?> TL</td></tr>
    </table>

    <div class="footer">
        Bu belge Punch Yazilim tarafindan elektronik olarak olusturulmustur.<br>
        punchyazilim.com · <?= e($company['email']) ?>
    </div>
</body>
</html>
