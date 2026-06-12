<?php $v = $__view; ?>
<div class="card">
    <div class="table-wrap" style="border:0">
        <table class="data">
            <thead><tr><th>Tarih</th><th>Ad / Iletisim</th><th>Paket</th><th>Mesaj</th><th>Durum</th><th>Islem</th></tr></thead>
            <tbody>
            <?php foreach ($quotes as $q): ?>
                <tr>
                    <td class="text-muted text-xs"><?= format_date($q['created_at']) ?></td>
                    <td><?= e($q['name']) ?><div class="text-muted text-xs"><?= e($q['email']) ?> <?= e($q['phone'] ?? '') ?></div></td>
                    <td class="text-muted"><?= e($q['package_title'] ?? '—') ?></td>
                    <td class="text-muted text-xs"><?= e(str_excerpt($q['message'] ?? '', 70)) ?></td>
                    <td><?= status_badge($q['status']) ?></td>
                    <td>
                        <details>
                            <summary class="btn btn-ghost btn-sm" style="cursor:pointer">Yanitla</summary>
                            <form action="/admin/teklifler/<?= (int)$q['id'] ?>" method="post" class="card mt-3" style="min-width:280px">
                                <?= csrf_field() ?>
                                <div class="form-group"><label class="form-label">Teklif Tutari (TL)</label><input class="form-control" name="quoted_price" value="<?= e($q['quoted_price'] ?? '') ?>"></div>
                                <div class="form-group"><label class="form-label">Not</label><textarea class="form-control" name="admin_note" style="min-height:80px"><?= e($q['admin_note'] ?? '') ?></textarea></div>
                                <div class="form-group"><label class="form-label">Durum</label>
                                    <select class="form-control" name="status">
                                        <option value="quoted"<?= $q['status']==='quoted'?' selected':'' ?>>Teklif Verildi</option>
                                        <option value="new"<?= $q['status']==='new'?' selected':'' ?>>Yeni</option>
                                        <option value="accepted"<?= $q['status']==='accepted'?' selected':'' ?>>Kabul</option>
                                        <option value="rejected"<?= $q['status']==='rejected'?' selected':'' ?>>Red</option>
                                    </select>
                                </div>
                                <button class="btn btn-sm">Kaydet & Bildir</button>
                            </form>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($quotes)): ?><tr><td colspan="6" class="text-center text-muted">Teklif talebi yok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
