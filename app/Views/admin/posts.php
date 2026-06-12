<?php $v = $__view; ?>
<div class="flex justify-between items-center mb-5">
    <p class="text-soft text-sm">Blog yazilarini yonetin.</p>
    <a href="/admin/blog/yeni" class="btn">Yeni Yazi</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Baslik</th><th>Kategori</th><th>Durum</th><th>Yayin</th><th>Goruntulenme</th><th>Islem</th></tr></thead>
            <tbody>
            <?php foreach ($posts as $p): ?>
                <tr>
                    <td class="text-bright"><?= e($p['title']) ?></td>
                    <td class="text-muted"><?= e($p['category_name'] ?? '—') ?></td>
                    <td><?= status_badge($p['status']) ?></td>
                    <td class="text-muted text-xs"><?= $p['published_at'] ? format_date($p['published_at']) : '—' ?></td>
                    <td class="text-muted"><?= (int)$p['views'] ?></td>
                    <td class="flex gap-2">
                        <a href="/admin/blog/<?= (int)$p['id'] ?>" class="btn btn-ghost btn-sm">Duzenle</a>
                        <form action="/admin/blog/<?= (int)$p['id'] ?>/sil" method="post" onsubmit="return confirm('Silinsin mi?')">
                            <?= csrf_field() ?><button class="btn btn-ghost btn-sm" style="color:var(--danger)">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($posts)): ?><tr><td colspan="6" class="text-center text-muted">Yazi yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
