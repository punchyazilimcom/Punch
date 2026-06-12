<?php $v = $__view; ?>
<div class="flex justify-between items-center mb-5">
    <p class="text-soft text-sm">Portfolyo projelerini yonetin.</p>
    <a href="/admin/portfolyo/yeni" class="btn">Yeni Proje</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Sira</th><th>Baslik</th><th>Kategori</th><th>Musteri</th><th>Durum</th><th>Islem</th></tr></thead>
            <tbody>
            <?php foreach ($works as $w): ?>
                <tr>
                    <td class="text-muted"><?= (int)$w['sort_order'] ?></td>
                    <td class="text-bright"><?= e($w['title']) ?></td>
                    <td class="text-muted"><?= e($w['category'] ?? '—') ?></td>
                    <td class="text-muted"><?= e($w['client'] ?? '—') ?></td>
                    <td><?= $w['is_active'] ? status_badge('active') : status_badge('passive') ?></td>
                    <td class="flex gap-2">
                        <a href="/admin/portfolyo/<?= (int)$w['id'] ?>" class="btn btn-ghost btn-sm">Duzenle</a>
                        <form action="/admin/portfolyo/<?= (int)$w['id'] ?>/sil" method="post" onsubmit="return confirm('Silinsin mi?')">
                            <?= csrf_field() ?><button class="btn btn-ghost btn-sm" style="color:var(--danger)">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($works)): ?><tr><td colspan="6" class="text-center text-muted">Proje yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
