<?php $v = $__view; ?>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Tarih</th><th>Ad</th><th>Iletisim</th><th>Konu</th><th>Mesaj</th></tr></thead>
            <tbody>
            <?php foreach ($messages as $m): ?>
                <tr>
                    <td class="text-muted text-xs"><?= format_date($m['created_at'], 'd.m.Y H:i') ?></td>
                    <td><?= e($m['name']) ?></td>
                    <td class="text-muted text-xs"><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a><br><?= e($m['phone'] ?? '') ?></td>
                    <td><?= e($m['subject'] ?? '—') ?></td>
                    <td class="text-muted"><?= nl2br(e(str_excerpt($m['message'], 200))) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($messages)): ?><tr><td colspan="5" class="text-center text-muted">Mesaj yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
