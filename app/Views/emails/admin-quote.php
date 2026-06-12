<h2 style="color:#fff;margin:0 0 16px">Yeni Teklif Talebi</h2>
<table role="presentation" width="100%" style="border-collapse:collapse">
    <tr><td style="padding:6px 0;color:#9a91b8">Ad</td><td style="padding:6px 0;color:#fff;text-align:right"><?= e($data['name'] ?? '') ?></td></tr>
    <tr><td style="padding:6px 0;color:#9a91b8">E-posta</td><td style="padding:6px 0;color:#fff;text-align:right"><?= e($data['email'] ?? '') ?></td></tr>
    <tr><td style="padding:6px 0;color:#9a91b8">Telefon</td><td style="padding:6px 0;color:#fff;text-align:right"><?= e($data['phone'] ?? '-') ?></td></tr>
</table>
<p style="margin-top:16px;line-height:1.6;color:#cfc7e6"><?= nl2br(e($data['message'] ?? '')) ?></p>
