<?php $v = $__view; ?>
<a href="/admin/destek" class="btn btn-ghost btn-sm mb-5">← Destek</a>
<div class="grid-2" style="grid-template-columns:1fr 300px;align-items:start">
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)"><?= e($ticket['subject']) ?></h2>
            <?= status_badge($ticket['status']) ?>
        </div>
        <div class="thread">
            <?php foreach ($messages as $m): ?>
            <div class="msg <?= $m['is_internal_note'] ? 'note' : ($m['sender_type'] === 'admin' ? 'admin' : 'user') ?>">
                <div class="msg-meta">
                    <?= $m['is_internal_note'] ? '🔒 Dahili Not' : ($m['sender_type'] === 'admin' ? 'Siz (Admin)' : 'Musteri') ?>
                    · <?= format_date($m['created_at'], 'd.m.Y H:i') ?>
                </div>
                <div><?= nl2br(e($m['message'])) ?></div>
                <?php if (!empty($m['attachment_path'])): ?>
                <a href="/admin/destek/ek/<?= (int)$m['id'] ?>" class="text-accent text-sm mt-2" style="display:inline-block">📎 Eki indir</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <hr class="divider">
        <form action="/admin/destek/<?= (int)$ticket['id'] ?>/yanit" method="post" enctype="multipart/form-data" data-guard>
            <?= csrf_field() ?>
            <div class="form-group">
                <textarea class="form-control" name="message" required placeholder="Yanitiniz veya dahili not…"></textarea>
            </div>
            <div class="form-group"><input class="form-control" type="file" name="attachment"></div>
            <div class="flex items-center justify-between flex-wrap gap-3">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="internal" value="1"> Dahili not (musteri gormez)</label>
                <button class="btn">Gonder</button>
            </div>
        </form>
    </div>

    <aside class="flex flex-col gap-4">
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Talep Bilgisi</h3>
            <p class="text-sm text-muted mb-2">Oncelik: <?= status_badge($ticket['priority']) ?></p>
            <?php if (!empty($customer)): ?>
            <p class="text-sm">Musteri: <a href="/admin/musteriler/<?= (int)$customer['id'] ?>" class="text-accent"><?= e($customer['name']) ?></a></p>
            <p class="text-sm text-muted"><?= e($customer['email']) ?></p>
            <?php endif; ?>
        </div>
        <div class="card">
            <h3 style="font-size:var(--fs-base)" class="mb-3">Durum Degistir</h3>
            <form action="/admin/destek/<?= (int)$ticket['id'] ?>/durum" method="post">
                <?= csrf_field() ?>
                <select class="form-control mb-3" name="status">
                    <option value="open"<?= $ticket['status']==='open'?' selected':'' ?>>Acik</option>
                    <option value="answered"<?= $ticket['status']==='answered'?' selected':'' ?>>Yanitlandi</option>
                    <option value="closed"<?= $ticket['status']==='closed'?' selected':'' ?>>Kapali</option>
                </select>
                <button class="btn btn-ghost btn-sm btn-block">Guncelle</button>
            </form>
        </div>
    </aside>
</div>
