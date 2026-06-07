<?php $v = $__view; ?>
<div class="flex justify-between items-center flex-wrap gap-3 mb-5">
    <div class="flex items-center gap-3">
        <a href="/panel/destek" class="btn btn-ghost btn-sm">← Geri</a>
        <h2 style="font-size:var(--fs-lg)"><?= e($ticket['subject']) ?></h2>
        <?= status_badge($ticket['status']) ?>
    </div>
    <span class="text-muted text-sm">Oncelik: <?= status_badge($ticket['priority']) ?></span>
</div>

<div class="card">
    <div class="thread">
        <?php foreach ($messages as $m): ?>
        <div class="msg <?= $m['sender_type'] === 'user' ? 'user' : 'admin' ?>">
            <div class="msg-meta"><?= $m['sender_type'] === 'user' ? 'Siz' : 'Destek Ekibi' ?> · <?= format_date($m['created_at'], 'd.m.Y H:i') ?></div>
            <div><?= nl2br(e($m['message'])) ?></div>
            <?php if (!empty($m['attachment_path'])): ?>
            <a href="/panel/destek/ek/<?= (int)$m['id'] ?>" class="text-accent text-sm mt-2" style="display:inline-block">📎 Eki indir</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($ticket['status'] !== 'closed'): ?>
    <hr class="divider">
    <form action="/panel/destek/<?= (int)$ticket['id'] ?>/yanit" method="post" enctype="multipart/form-data" data-guard>
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="message">Yanitiniz</label>
            <textarea class="form-control" id="message" name="message" required placeholder="Mesajinizi yazin…"></textarea>
        </div>
        <div class="form-group">
            <input class="form-control" type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.zip,.doc,.docx">
        </div>
        <button type="submit" class="btn">Yaniti Gonder</button>
    </form>
    <?php else: ?>
    <div class="alert alert-info mt-5">Bu talep kapatilmistir. Yeni bir konu icin <a href="/panel/destek/yeni" class="text-accent">yeni talep olusturun</a>.</div>
    <?php endif; ?>
</div>
