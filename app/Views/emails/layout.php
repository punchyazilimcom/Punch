<?php /** @var string $content @var string $subject */ ?>
<!DOCTYPE html>
<html lang="tr">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#0b0716;font-family:Arial,Helvetica,sans-serif;color:#cfc7e6">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0b0716;padding:32px 16px">
        <tr><td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#110b20;border-radius:18px;overflow:hidden;border:1px solid rgba(255,255,255,0.08)">
                <tr><td style="padding:28px 32px;background:linear-gradient(135deg,#7c3aed,#c026d3)">
                    <span style="font-size:22px;font-weight:bold;color:#fff;letter-spacing:-0.5px">Punch<span style="opacity:.7">.</span> Yazilim</span>
                </td></tr>
                <tr><td style="padding:32px">
                    <?= $content ?>
                </td></tr>
                <tr><td style="padding:24px 32px;border-top:1px solid rgba(255,255,255,0.08);color:#9a91b8;font-size:12px">
                    © <?= date('Y') ?> Punch Yazilim · Ankara, Yenimahalle<br>
                    <a href="<?= e(config('app.url')) ?>" style="color:#ad84f7;text-decoration:none">punchyazilim.com</a>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
