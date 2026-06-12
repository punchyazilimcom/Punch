<?php $v = $__view; ?>
<div class="flex justify-between items-center mb-5">
    <p class="text-soft text-sm">Paketleri yonetin: fiyat, ozellik, sira ve gorunurluk.</p>
    <a href="/admin/paketler/yeni" class="btn">Yeni Paket</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Sira</th><th>Baslik</th><th>Fiyat</th><th>Tip</th><th>Durum</th><th>Islem</th></tr></thead>
            <tbody>
            <?php foreach ($packages as $p): ?>
                <tr>
                    <td class="text-muted"><?= (int)$p['sort_order'] ?></td>
                    <td class="text-bright"><?= e($p['title']) ?></td>
                    <td><?= !empty($p['is_quote_only']) ? '<span class="text-muted">Teklife ozel</span>' : price_format($p['price'], $p['currency']) ?></td>
                    <td><?= !empty($p['is_popular']) ? '<span class="badge badge-info">Populer</span>' : '' ?></td>
                    <td><?= $p['is_active'] ? status_badge('active') : status_badge('passive') ?></td>
                    <td class="flex gap-2">
                        <a href="/admin/paketler/<?= (int)$p['id'] ?>" class="btn btn-ghost btn-sm">Duzenle</a>
                        <form action="/admin/paketler/<?= (int)$p['id'] ?>/sil" method="post" onsubmit="return confirm('Bu paket silinsin mi?')">
                            <?= csrf_field() ?><button class="btn btn-ghost btn-sm" style="color:var(--danger)">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($packages)): ?><tr><td colspan="6" class="text-center text-muted">Paket yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
