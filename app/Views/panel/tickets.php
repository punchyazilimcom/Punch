<?php $v = $__view; ?>
<div class="flex justify-between items-center mb-5">
    <p class="text-soft text-sm">Tum destek taleplerinizi buradan takip edebilirsiniz.</p>
    <a href="/panel/destek/yeni" class="btn">Yeni Talep</a>
</div>
<div class="card">
    <?php if (empty($tickets)): ?>
        <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'message-square', 'size' => 48]) ?><p>Henuz destek talebiniz yok.</p><a href="/panel/destek/yeni" class="btn mt-4">Talep Olustur</a></div>
    <?php else: ?>
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>#</th><th>Konu</th><th>Oncelik</th><th>Durum</th><th>Guncelleme</th></tr></thead>
            <tbody>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td class="text-muted">#<?= (int)$t['id'] ?></td>
                    <td><a href="/panel/destek/<?= (int)$t['id'] ?>" class="text-bright"><?= e($t['subject']) ?></a></td>
                    <td><?= status_badge($t['priority']) ?></td>
                    <td><?= status_badge($t['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($t['updated_at'], 'd.m.Y H:i') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
