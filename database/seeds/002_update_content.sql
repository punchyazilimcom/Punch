-- ============================================================
--  PUNCH YAZILIM - Icerik Guncelleme (002)
--  AMAC: Mevcut bir veritabanini SILMEDEN, zenginlestirilmis
--        metinleri (hero, paketler, yorumlar, SSS) uygular.
--
--  KULLANIM:
--    - Yeni kurulumda gerek YOK (001_seed.sql zaten guncel metinleri icerir).
--    - Daha once eski seed'i import ettiyseniz, bu dosyayi phpMyAdmin >
--      Import ile calistirin; yalnizca asagidaki alanlari GUNCELLER.
--
--  NOT: Bu dosya yalnizca ICERIK metinlerini gunceller; TABLO YAPISINI (sema)
--       DEGISTIRMEZ. Yeni tablo/kolon eklemez, veri SILMEZ.
--
--  UYARI: Asagidaki UPDATE'ler ilgili alanlari uzerine yazar. Bu metinleri
--         panelden ozellestirdiyseniz, calistirmadan once not alin.
-- ============================================================

SET NAMES utf8mb4;

-- ---------------- Site & Hero metinleri ----------------
UPDATE `settings` SET `setting_value` = 'Markanizi dijitalde one cikaran, sonuc ureten yazilim ajansi.'
  WHERE `setting_key` = 'site_tagline';

UPDATE `settings` SET `setting_value` = 'Ankara · Dijital Yazilim & Tasarim Ajansi'
  WHERE `setting_key` = 'hero_eyebrow';

UPDATE `settings` SET `setting_value` = 'Markanizi dijitalde fark yaratan deneyimlere donusturuyoruz'
  WHERE `setting_key` = 'hero_title';

UPDATE `settings` SET `setting_value` = 'Sablon degil, size ozel. Hizli yuklenen, Google''da one cikan ve satisa donusen web siteleri, e-ticaret altyapilari ve sosyal medya yonetimi. Stratejiden yayina kadar tek catida.'
  WHERE `setting_key` = 'hero_subtitle';

-- ---------------- Musteri yorumlari ----------------
UPDATE `settings` SET `setting_value` = '[{"name":"Mehmet K.","company":"Nova Teknoloji · Kurucu","text":"Eski sitemiz yavas ve eskiydi. Punch ekibi 3 haftada hem pirilti gibi hem de cok hizli bir site teslim etti. Teklif formundan gelen talepler ilk aydan itibaren ikiye katlandi.","rating":5},{"name":"Aylin D.","company":"Atlas Store · E-ticaret Mudiru","text":"PayTR ve kargo entegrasyonu kusursuz calisiyor. Mobilde sayfa hizi cok iyi oldugu icin sepet terk oranimiz belirgin sekilde dustu. Panel kullanimi da cok kolay.","rating":5},{"name":"Caner T.","company":"Lumen Group · Pazarlama","text":"Sosyal medya yonetiminde icerik kalitesi ve raporlama seffafligi bizi etkiledi. 3 ayda etkilesimimiz %180 artti, gercek musteri talepleri gelmeye basladi.","rating":5}]'
  WHERE `setting_key` = 'testimonials_json';

-- ---------------- Sik Sorulan Sorular ----------------
UPDATE `settings` SET `setting_value` = '[{"q":"Bir web sitesi ne kadar surede teslim edilir?","a":"Kurumsal web siteleri, icerik ve gorseller tamamsa ortalama 2-4 hafta icinde yayina alinir. Surecin her asamasinda (tasarim onayi, gelistirme, test) bilgilendirilirsiniz; gizli surpriz yoktur."},{"q":"Odeme nasil yapiliyor, guvenli mi?","a":"Sabit fiyatli paketleri sitemizden PayTR 3D Secure altyapisi ile guvenle online satin alabilirsiniz. Kart bilgileriniz bizde saklanmaz. Daha buyuk kurumsal projelerde asamali odeme plani sunariz."},{"q":"Site sablon mu, yoksa size mi ozel?","a":"Hazir tema satmiyoruz. Her proje; marka kimliginize, hedef kitlenize ve is hedeflerinize gore sifirdan tasarlanir ve kodlanir. Boylece hem ozgun gorunur hem de hizli calisir."},{"q":"SEO ve Google siralamasi dahil mi?","a":"Tum sitelerimiz teknik SEO altyapisiyla (hizli yukleme, mobil uyum, yapilandirilmis veri, sitemap) teslim edilir. Surekli icerik ve SEO buyumesi icin ayrica aylik paketlerimiz vardir."},{"q":"Teslimden sonra destek aliyor muyum?","a":"Evet. Kurumsal paketlerde 1 yil teknik destek dahildir. Panelden 7/24 destek talebi acabilir, guncelleme ve bakim paketlerimizden yararlanabilirsiniz."},{"q":"Iceriklerimi kendim guncelleyebilir miyim?","a":"Elbette. Yonetim paneli (CMS) ile blog yazisi, gorsel, fiyat ve sayfa metinlerini teknik bilgi gerekmeden kolayca guncelleyebilirsiniz."}]'
  WHERE `setting_key` = 'faq_json';

-- ---------------- Paket: Kurumsal Web Site ----------------
UPDATE `packages` SET
  `short_desc` = 'Markanizi ilk saniyede anlatan; hizli, sik ve Google dostu ozel tasarim kurumsal site.',
  `description` = 'Isletmenizi dijitalde en guclu sekilde temsil eden, tamamen size ozel tasarlanan kurumsal web sitesi. Ziyaretciyi musteriye ceviren akici bir deneyim, saniyenin altinda acilan sayfalar ve arama motorlarinda one cikaran teknik SEO altyapisi bir arada. Yonetim paneli sayesinde icerigi, gorselleri ve metinleri teknik bilgi gerektirmeden kendiniz guncellersiniz. Teslimde site canli, hizli ve buyumeye hazirdir.',
  `features_json` = '["Markaniza ozel tasarim (hazir tema degil)","Mobil, tablet ve masaustu tam uyum","Teknik SEO altyapisi (hiz, sitemap, schema)","Kolay yonetim paneli (CMS)","Ucretsiz SSL & guvenlik onlemleri","Iletisim formu, harita ve WhatsApp entegrasyonu","Google Analytics & Search Console kurulumu","2 tur ucretsiz revizyon","1 yil teknik destek"]'
  WHERE `slug` = 'kurumsal-web-site';

-- ---------------- Paket: E-Ticaret Sitesi ----------------
UPDATE `packages` SET
  `short_desc` = 'Satisa odakli, guvenli ve buyumeye hazir profesyonel e-ticaret altyapisi.',
  `description` = 'Urunlerinizi en iyi sekilde sergileyen, ziyaretciyi adim adim satin almaya goturen tam donanimli e-ticaret platformu. PayTR & sanal POS ile 3D Secure guvenli odeme, uyelik ve sepet sistemi, kampanya ve kupon motoru, kargo ve stok yonetimi bir arada. Mobil oncelikli ve hizli yapisi sayesinde sepet terk oranini dusurur, donusumu artirir. Pazaryeri (Trendyol, Hepsiburada) entegrasyonlarina hazir mimari ile isletmenizle birlikte olceklenir.',
  `features_json` = '["Sinirsiz urun, varyant & kategori","3D Secure guvenli odeme (PayTR / Sanal POS)","Uyelik, sepet & hizli odeme akisi","Kampanya, indirim & kupon yonetimi","Kargo entegrasyonu & otomatik takip","Stok, siparis & iade yonetimi","Mobil oncelikli, yuksek hizli altyapi","SEO uyumlu & pazaryerine hazir","Yonetim paneli & satis raporlari"]'
  WHERE `slug` = 'e-ticaret';

-- ---------------- Paket: Sosyal Medya Yonetimi ----------------
UPDATE `packages` SET
  `short_desc` = 'Takipciyi musteriye ceviren, strateji ve reklam odakli sosyal medya yonetimi.',
  `description` = 'Markanizi sosyal medyada sadece "gorunur" degil, "tercih edilir" kilan butuncul bir yonetim hizmeti. Hedef kitlenize ozel icerik stratejisi, dikkat ceken grafik ve video tasarimlari, dogru hedefli Meta & Google reklamlari ve hizli topluluk yonetimi ile etkilesimi gercek is sonuclarina donusturuyoruz. Her ay seffaf performans raporu ile nereye para harcandigini ve ne kazandirdigini net gorursunuz. Ihtiyaciniza ve sektorunuze gore fiyatlandirilir; size ozel teklif alin.',
  `features_json` = '["Markaya ozel aylik icerik stratejisi & takvim","Profesyonel grafik & kisa video tasarimi","Meta (Instagram/Facebook) & Google reklam yonetimi","Topluluk, yorum & mesaj yonetimi","Aylik seffaf performans raporu","Rakip, trend & hedef kitle analizi"]'
  WHERE `slug` = 'sosyal-medya-yonetimi';
