<?php $v = $__view; ?>
<div class="flex justify-between items-center flex-wrap gap-3 mb-5">
    <form method="get" class="flex gap-2" style="flex:1;max-width:420px">
        <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Isim, e-posta veya firma ara…">
        <button class="btn">Ara</button>
    </form>
    <a href="/admin/disa-aktar/musteriler" class="btn btn-ghost">CSV Disa Aktar</a>
</div>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Ad</th><th>E-posta</th><th>Telefon</th><th>Firma</th><th>Durum</th><th>Kayit</th></tr></thead>
            <tbody>
            <?php foreach ($customers as $c): ?>
                <tr>
                    <td><a href="/admin/musteriler/<?= (int)$c['id'] ?>" class="text-bright"><?= e($c['name']) ?></a></td>
                    <td class="text-muted"><?= e($c['email']) ?></td>
                    <td class="text-muted"><?= e($c['phone'] ?? '—') ?></td>
                    <td class="text-muted"><?= e($c['company'] ?? '—') ?></td>
                    <td><?= status_badge($c['status']) ?></td>
                    <td class="text-muted text-xs"><?= format_date($c['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($customers)): ?><tr><td colspan="6" class="text-center text-muted">Sonuc bulunamadi.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
