<?php $v = $__view; ?>
<div class="card">
    <?php if (empty($quotes)): ?>
        <div class="empty-state"><?= $v->partial('partials/icon', ['name' => 'credit-card', 'size' => 48]) ?><p>Henuz teklif talebiniz yok.</p><a href="/paketler" class="btn mt-4">Teklif Iste</a></div>
    <?php else: ?>
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Tarih</th><th>Mesaj</th><th>Teklif</th><th>Durum</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($quotes as $q): ?>
                <tr>
                    <td class="text-muted text-xs"><?= format_date($q['created_at']) ?></td>
                    <td><?= e(str_excerpt($q['message'] ?? '', 60)) ?></td>
                    <td><?= $q['quoted_price'] !== null ? price_format($q['quoted_price']) : '<span class="text-muted">—</span>' ?></td>
                    <td><?= status_badge($q['status']) ?></td>
                    <td>
                        <?php if ($q['status'] === 'quoted'): ?>
                        <form action="/panel/teklifler/<?= (int)$q['id'] ?>/durum" method="post" style="display:inline-flex;gap:6px">
                            <?= csrf_field() ?>
                            <button name="action" value="accept" class="btn btn-sm">Kabul</button>
                            <button name="action" value="reject" class="btn btn-ghost btn-sm">Reddet</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
