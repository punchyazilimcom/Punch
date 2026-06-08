<?php
$phone = setting('contact_phone', $company['phone'] ?? '');
$email = setting('contact_email', $company['email'] ?? 'destek@punchyazilim.com');
$address = setting('contact_address', $company['address'] ?? 'Yenimahalle, Ankara');
$wa = setting('contact_whatsapp', $company['whatsapp'] ?? '');
$ig = setting('social_instagram', '');
$li = setting('social_linkedin', '');
$x  = setting('social_x', '');
$yt = setting('social_youtube', '');
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="/" class="brand" style="margin-bottom:var(--sp-4)">
                    <?= $__view->partial('partials/logo') ?>
                    <span>Punch<span style="color:var(--punch-violet-400)">.</span></span>
                </a>
                <p class="text-sm text-muted" style="max-width:36ch;margin-top:var(--sp-4)">
                    <?= e(setting('site_tagline', 'Markanizi dijitalde one cikaran yazilim ajansi.')) ?>
                </p>
                <div class="social-links" style="margin-top:var(--sp-5)">
                    <?php if ($ig): ?><a href="<?= e($ig) ?>" aria-label="Instagram" rel="noopener" target="_blank">IG</a><?php endif; ?>
                    <?php if ($li): ?><a href="<?= e($li) ?>" aria-label="LinkedIn" rel="noopener" target="_blank">in</a><?php endif; ?>
                    <?php if ($x): ?><a href="<?= e($x) ?>" aria-label="X" rel="noopener" target="_blank">X</a><?php endif; ?>
                    <?php if ($yt): ?><a href="<?= e($yt) ?>" aria-label="YouTube" rel="noopener" target="_blank">YT</a><?php endif; ?>
                </div>
            </div>

            <div>
                <h4>Hizmetler</h4>
                <div class="footer-links">
                    <a href="/hizmetler/web-tasarim">Web Tasarim</a>
                    <a href="/hizmetler/e-ticaret-cozumleri">E-Ticaret</a>
                    <a href="/hizmetler/sosyal-medya">Sosyal Medya</a>
                    <a href="/hizmetler/yazilim-gelistirme">Yazilim Gelistirme</a>
                </div>
            </div>

            <div>
                <h4>Kurumsal</h4>
                <div class="footer-links">
                    <a href="/hakkimizda">Hakkimizda</a>
                    <a href="/portfolyo">Portfolyo</a>
                    <a href="/blog">Blog</a>
                    <a href="/iletisim">Iletisim</a>
                </div>
            </div>

            <div>
                <h4>Iletisim</h4>
                <div class="footer-links">
                    <?php if ($phone): ?><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a><?php endif; ?>
                    <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                    <span class="text-muted text-sm"><?= e($address) ?></span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© <?= date('Y') ?> <?= e($app['name']) ?>. Tum haklari saklidir.</span>
            <div class="footer-links" style="flex-direction:row;flex-wrap:wrap;gap:var(--sp-4)">
                <a href="/on-bilgilendirme-formu">On Bilgilendirme</a>
                <a href="/mesafeli-satis-sozlesmesi">Mesafeli Satis</a>
                <a href="/teslimat-ve-iade">Teslimat & Iade</a>
                <a href="/kvkk">KVKK</a>
                <a href="/gizlilik-politikasi">Gizlilik</a>
                <a href="/cerez-politikasi">Cerez Politikasi</a>
            </div>
            <label class="perf-control" title="Gorsel efekt yogunlugu">
                <span aria-hidden="true">✦</span> Efektler:
                <select id="perf-select" aria-label="Gorsel efekt yogunlugu">
                    <option value="auto">Otomatik</option>
                    <option value="high">Yuksek</option>
                    <option value="medium">Orta</option>
                    <option value="low">Dusuk</option>
                    <option value="off">Kapali</option>
                </select>
            </label>
        </div>
    </div>
</footer>
<script>
(function(){
  var sel=document.getElementById('perf-select'); if(!sel)return;
  try{ sel.value=localStorage.getItem('punch_perf')||'auto'; }catch(e){}
  sel.addEventListener('change',function(){
    if(window.PunchPerf){ window.PunchPerf.set(sel.value); }
    else { try{localStorage.setItem('punch_perf',sel.value);}catch(e){} location.reload(); }
  });
})();
</script>

<?php if ($wa): ?>
<a class="wa-float" href="https://wa.me/<?= e(preg_replace('/\D/', '', $wa)) ?>" target="_blank" rel="noopener" aria-label="WhatsApp ile yazin">
    <svg viewBox="0 0 24 24" fill="#fff"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.187zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>
<?php endif; ?>
