<?php $v = $__view; ?>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>#</th><th>Konu</th><th>Musteri</th><th>Oncelik</th><th>Durum</th><th>Guncelleme</th></tr></thead>
            <tbody>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td class="text-muted">#<?= (int)$t['id'] ?></td>
                    <td><a href="/admin/destek/<?= (int)$t['id'] ?>" class="text-bright"><?= e($t['subject']) ?></a></td>
                    <td class="text-muted"><?= e($t['user_name'] ?? '—') ?></td>
                    <td><?= status_badge($t['priority']) ?></td>
                    <td><?= status_badge($t['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($t['updated_at'], 'd.m.Y H:i') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($tickets)): ?><tr><td colspan="6" class="text-center text-muted">Talep yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
