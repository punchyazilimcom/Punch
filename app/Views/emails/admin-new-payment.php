<h2 style="color:#fff;margin:0 0 16px">Yeni Odeme Alindi</h2>
<table role="presentation" width="100%" style="border-collapse:collapse">
    <tr><td style="padding:8px 0;color:#9a91b8">Musteri</td><td style="padding:8px 0;color:#fff;text-align:right"><?= e($user['name']) ?> (<?= e($user['email']) ?>)</td></tr>
    <tr><td style="padding:8px 0;color:#9a91b8">Fatura</td><td style="padding:8px 0;color:#fff;text-align:right"><?= e($invoice['invoice_no']) ?></td></tr>
    <tr><td style="padding:8px 0;color:#9a91b8">Tutar</td><td style="padding:8px 0;color:#fff;text-align:right"><?= price_format($order['amount'], $order['currency']) ?></td></tr>
    <tr><td style="padding:8px 0;color:#9a91b8">Siparis</td><td style="padding:8px 0;color:#fff;text-align:right"><?= e($order['merchant_oid']) ?></td></tr>
</table>
