<?php
$v = $__view;
// En az birkac bos satir gosterelim
$stats = $stats ?: [];
$testimonials = $testimonials ?: [];
$faqs = $faqs ?: [];
?>
<form action="/admin/icerik" method="post" data-guard>
    <?= csrf_field() ?>

    <div class="card mb-5">
        <h2 style="font-size:var(--fs-lg)" class="mb-4">Hero (Ana Sayfa Ust Bolum)</h2>
        <div class="form-group"><label class="form-label">Ust etiket</label><input class="form-control" name="hero_eyebrow" value="<?= e($hero['eyebrow']) ?>"></div>
        <div class="form-group"><label class="form-label">Baslik</label><input class="form-control" name="hero_title" value="<?= e($hero['title']) ?>"></div>
        <div class="form-group"><label class="form-label">Alt baslik</label><textarea class="form-control" name="hero_subtitle"><?= e($hero['subtitle']) ?></textarea></div>
    </div>

    <div class="card mb-5">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)">Istatistikler</h2>
            <button type="button" class="btn btn-ghost btn-sm" data-add="stats">+ Ekle</button>
        </div>
        <div id="stats-rows">
            <?php foreach (array_pad($stats, max(4, count($stats)), ['value'=>'','label'=>'']) as $s): ?>
            <div class="form-row" data-row>
                <div class="form-group"><input class="form-control" name="stat_value[]" value="<?= e($s['value'] ?? '') ?>" placeholder="120+"></div>
                <div class="form-group"><input class="form-control" name="stat_label[]" value="<?= e($s['label'] ?? '') ?>" placeholder="Tamamlanan Proje"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card mb-5">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)">Musteri Yorumlari</h2>
            <button type="button" class="btn btn-ghost btn-sm" data-add="testi">+ Ekle</button>
        </div>
        <div id="testi-rows">
            <?php foreach (array_pad($testimonials, max(3, count($testimonials)), ['name'=>'','company'=>'','text'=>'']) as $t): ?>
            <div class="card mb-3" data-row style="background:rgba(255,255,255,0.02)">
                <div class="form-row">
                    <div class="form-group"><input class="form-control" name="t_name[]" value="<?= e($t['name'] ?? '') ?>" placeholder="Ad Soyad"></div>
                    <div class="form-group"><input class="form-control" name="t_company[]" value="<?= e($t['company'] ?? '') ?>" placeholder="Firma"></div>
                </div>
                <div class="form-group"><textarea class="form-control" name="t_text[]" placeholder="Yorum metni" style="min-height:60px"><?= e($t['text'] ?? '') ?></textarea></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card mb-5">
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-size:var(--fs-lg)">Sik Sorulan Sorular</h2>
            <button type="button" class="btn btn-ghost btn-sm" data-add="faq">+ Ekle</button>
        </div>
        <div id="faq-rows">
            <?php foreach (array_pad($faqs, max(4, count($faqs)), ['q'=>'','a'=>'']) as $f): ?>
            <div class="card mb-3" data-row style="background:rgba(255,255,255,0.02)">
                <div class="form-group"><input class="form-control" name="faq_q[]" value="<?= e($f['q'] ?? '') ?>" placeholder="Soru"></div>
                <div class="form-group"><textarea class="form-control" name="faq_a[]" placeholder="Cevap" style="min-height:60px"><?= e($f['a'] ?? '') ?></textarea></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card mb-5">
        <h2 style="font-size:var(--fs-lg)" class="mb-2">Sozlesmeler & Hukuki Metinler (HTML)</h2>
        <p class="text-muted text-sm mb-4">Asagidaki alanlar markaniza ozel hazir metinlerle dolu gelir. Diledigniz gibi duzenleyebilirsiniz; bos birakirsaniz hazir metin gosterilmeye devam eder.</p>
        <div class="form-group"><label class="form-label">On Bilgilendirme Formu</label><textarea class="form-control" name="legal_preinfo" style="min-height:140px"><?= e($legal['preinfo']) ?></textarea></div>
        <div class="form-group"><label class="form-label">Mesafeli Satis Sozlesmesi</label><textarea class="form-control" name="legal_sales" style="min-height:140px"><?= e($legal['sales']) ?></textarea></div>
        <div class="form-group"><label class="form-label">Teslimat & Iade Kosullari</label><textarea class="form-control" name="legal_delivery" style="min-height:120px"><?= e($legal['delivery']) ?></textarea></div>
        <div class="form-group"><label class="form-label">KVKK Aydinlatma Metni</label><textarea class="form-control" name="legal_kvkk" style="min-height:120px"><?= e($legal['kvkk']) ?></textarea></div>
        <div class="form-group"><label class="form-label">Gizlilik Politikasi</label><textarea class="form-control" name="legal_privacy" style="min-height:120px"><?= e($legal['privacy']) ?></textarea></div>
        <div class="form-group"><label class="form-label">Cerez Politikasi</label><textarea class="form-control" name="legal_cookie" style="min-height:120px"><?= e($legal['cookie']) ?></textarea></div>
    </div>

    <button class="btn btn-lg">Tum Icerikleri Kaydet</button>
</form>

<script nonce="<?= csp_nonce() ?>">
(function(){
  function add(container, html){ var d=document.createElement('div'); d.innerHTML=html; document.querySelector(container).appendChild(d.firstElementChild); }
  var tpl={
    stats:'<div class="form-row" data-row><div class="form-group"><input class="form-control" name="stat_value[]" placeholder="120+"></div><div class="form-group"><input class="form-control" name="stat_label[]" placeholder="Etiket"></div></div>',
    testi:'<div class="card mb-3" data-row style="background:rgba(255,255,255,0.02)"><div class="form-row"><div class="form-group"><input class="form-control" name="t_name[]" placeholder="Ad Soyad"></div><div class="form-group"><input class="form-control" name="t_company[]" placeholder="Firma"></div></div><div class="form-group"><textarea class="form-control" name="t_text[]" placeholder="Yorum" style="min-height:60px"></textarea></div></div>',
    faq:'<div class="card mb-3" data-row style="background:rgba(255,255,255,0.02)"><div class="form-group"><input class="form-control" name="faq_q[]" placeholder="Soru"></div><div class="form-group"><textarea class="form-control" name="faq_a[]" placeholder="Cevap" style="min-height:60px"></textarea></div></div>'
  };
  document.querySelectorAll('[data-add]').forEach(function(b){
    b.addEventListener('click',function(){ var k=b.getAttribute('data-add'); add('#'+(k==='testi'?'testi':k)+'-rows', tpl[k]); });
  });
})();
</script>
