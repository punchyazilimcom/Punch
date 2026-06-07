<?php $v = $__view; ?>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Tarih</th><th>Aktor</th><th>Islem</th><th>Detay</th><th>IP</th></tr></thead>
            <tbody>
            <?php foreach ($logs as $l): ?>
                <tr>
                    <td class="text-muted text-xs"><?= format_date($l['created_at'], 'd.m.Y H:i:s') ?></td>
                    <td><?= e($l['actor_type']) ?> #<?= (int)($l['actor_id'] ?? 0) ?></td>
                    <td><span class="badge badge-info"><?= e($l['action']) ?></span></td>
                    <td class="text-muted text-xs"><?= e(str_excerpt($l['meta_json'] ?? '', 80)) ?></td>
                    <td class="text-muted text-xs"><?= e($l['ip'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($logs)): ?><tr><td colspan="5" class="text-center text-muted">Kayit yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
