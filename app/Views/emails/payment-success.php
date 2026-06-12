<h2 style="color:#fff;margin:0 0 16px">Odemeniz Alindi</h2>
<p style="line-height:1.6">Merhaba <?= e($user['name']) ?>, odemeniz basariyla alindi. Tesekkur ederiz!</p>
<table role="presentation" width="100%" style="margin:20px 0;border-collapse:collapse">
    <tr><td style="padding:8px 0;color:#9a91b8">Fatura No</td><td style="padding:8px 0;color:#fff;text-align:right"><?= e($invoice['invoice_no']) ?></td></tr>
    <?php if (!empty($package)): ?><tr><td style="padding:8px 0;color:#9a91b8">Paket</td><td style="padding:8px 0;color:#fff;text-align:right"><?= e($package['title']) ?></td></tr><?php endif; ?>
    <tr><td style="padding:8px 0;color:#9a91b8">Tutar</td><td style="padding:8px 0;color:#fff;text-align:right"><?= price_format($order['amount'], $order['currency']) ?></td></tr>
</table>
<p style="margin:24px 0"><a href="<?= e(config('app.url')) ?>/panel/faturalar" style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#c026d3);color:#fff;text-decoration:none;padding:14px 28px;border-radius:999px;font-weight:bold">Faturami Goruntule</a></p>
