-- ============================================================
--  PUNCH YAZILIM - Demo / Baslangic Verisi (Seed)
--  Bu veriler canlida site bos gorunmesin diye eklenir.
--  Admin girisi:  destek@punchyazilim.com  /  PunchAdmin!2026
--  Demo musteri:  demo@punchyazilim.com    /  Demo!2026
--  >>> CANLIYA ALMADAN ONCE SIFRELERI DEGISTIRIN <<<
-- ============================================================

SET NAMES utf8mb4;

-- --- Admin ---
INSERT INTO `admins` (`name`, `email`, `password_hash`, `role`) VALUES
('Punch Yonetici', 'destek@punchyazilim.com', '$argon2id$v=19$m=65536,t=4,p=1$SE5ybC40b2NodU9iM1NzSQ$mU2qlrgobWGo0RI3i3h2GqwYdr4CP8aD8ndR56LtrM8', 'superadmin')
ON DUPLICATE KEY UPDATE email = email;

-- --- Demo musteri ---
INSERT INTO `users` (`name`, `email`, `password_hash`, `phone`, `company`, `city`, `status`, `email_verified_at`) VALUES
('Demo Musteri', 'demo@punchyazilim.com', '$argon2id$v=19$m=65536,t=4,p=1$Z0Z2cXk1TXFPdUNubnAwZg$YO5iNN2gENTcstq8daxgG/+wGCEGN03VXKEa4Tme7ec', '0500 000 00 00', 'Demo Sirket A.S.', 'Ankara', 'active', NOW())
ON DUPLICATE KEY UPDATE email = email;

-- --- Paketler ---
INSERT INTO `packages` (`slug`, `title`, `short_desc`, `description`, `features_json`, `price`, `currency`, `is_quote_only`, `is_popular`, `icon`, `is_active`, `sort_order`, `meta_title`, `meta_description`) VALUES
('kurumsal-web-site', 'Kurumsal Web Site',
 'Markanizi ilk saniyede anlatan; hizli, sik ve Google dostu ozel tasarim kurumsal site.',
 'Isletmenizi dijitalde en guclu sekilde temsil eden, tamamen size ozel tasarlanan kurumsal web sitesi. Ziyaretciyi musteriye ceviren akici bir deneyim, saniyenin altinda acilan sayfalar ve arama motorlarinda one cikaran teknik SEO altyapisi bir arada. Yonetim paneli sayesinde icerigi, gorselleri ve metinleri teknik bilgi gerektirmeden kendiniz guncellersiniz. Teslimde site canli, hizli ve buyumeye hazirdir.',
 '["Markaniza ozel tasarim (hazir tema degil)","Mobil, tablet ve masaustu tam uyum","Teknik SEO altyapisi (hiz, sitemap, schema)","Kolay yonetim paneli (CMS)","Ucretsiz SSL & guvenlik onlemleri","Iletisim formu, harita ve WhatsApp entegrasyonu","Google Analytics & Search Console kurulumu","2 tur ucretsiz revizyon","1 yil teknik destek"]',
 24900.00, 'TRY', 0, 1, 'layout', 1, 1,
 'Kurumsal Web Site Tasarimi | Punch Yazilim Ankara',
 'Ankara kurumsal web site tasarimi. Ozel tasarim, SEO uyumlu, hizli ve mobil uyumlu profesyonel web siteleri.'),

('e-ticaret', 'E-Ticaret Sitesi',
 'Satisa odakli, guvenli ve buyumeye hazir profesyonel e-ticaret altyapisi.',
 'Urunlerinizi en iyi sekilde sergileyen, ziyaretciyi adim adim satin almaya goturen tam donanimli e-ticaret platformu. PayTR & sanal POS ile 3D Secure guvenli odeme, uyelik ve sepet sistemi, kampanya ve kupon motoru, kargo ve stok yonetimi bir arada. Mobil oncelikli ve hizli yapisi sayesinde sepet terk oranini dusurur, donusumu artirir. Pazaryeri (Trendyol, Hepsiburada) entegrasyonlarina hazir mimari ile isletmenizle birlikte olceklenir.',
 '["Sinirsiz urun, varyant & kategori","3D Secure guvenli odeme (PayTR / Sanal POS)","Uyelik, sepet & hizli odeme akisi","Kampanya, indirim & kupon yonetimi","Kargo entegrasyonu & otomatik takip","Stok, siparis & iade yonetimi","Mobil oncelikli, yuksek hizli altyapi","SEO uyumlu & pazaryerine hazir","Yonetim paneli & satis raporlari"]',
 49900.00, 'TRY', 0, 0, 'shopping-cart', 1, 2,
 'E-Ticaret Sitesi Kurulumu | Punch Yazilim',
 'Profesyonel e-ticaret sitesi kurulumu. PayTR & sanal POS entegrasyonu, urun yonetimi ve SEO uyumlu altyapi.'),

('sosyal-medya-yonetimi', 'Sosyal Medya Yonetimi',
 'Takipciyi musteriye ceviren, strateji ve reklam odakli sosyal medya yonetimi.',
 'Markanizi sosyal medyada sadece "gorunur" degil, "tercih edilir" kilan butuncul bir yonetim hizmeti. Hedef kitlenize ozel icerik stratejisi, dikkat ceken grafik ve video tasarimlari, dogru hedefli Meta & Google reklamlari ve hizli topluluk yonetimi ile etkilesimi gercek is sonuclarina donusturuyoruz. Her ay seffaf performans raporu ile nereye para harcandigini ve ne kazandirdigini net gorursunuz. Ihtiyaciniza ve sektorunuze gore fiyatlandirilir; size ozel teklif alin.',
 '["Markaya ozel aylik icerik stratejisi & takvim","Profesyonel grafik & kisa video tasarimi","Meta (Instagram/Facebook) & Google reklam yonetimi","Topluluk, yorum & mesaj yonetimi","Aylik seffaf performans raporu","Rakip, trend & hedef kitle analizi"]',
 0.00, 'TRY', 1, 0, 'megaphone', 1, 3,
 'Sosyal Medya Yonetimi Ajansi | Punch Yazilim',
 'Ankara sosyal medya yonetimi. Icerik, tasarim ve reklam yonetimiyle markanizi buyutun. Size ozel teklif alin.')
ON DUPLICATE KEY UPDATE slug = slug;

-- --- Hizmetler ---
INSERT INTO `services` (`slug`, `title`, `short_desc`, `description`, `features_json`, `icon`, `is_active`, `sort_order`, `meta_title`, `meta_description`) VALUES
('web-tasarim', 'Web Tasarim', 'Ozel, hizli ve donusum odakli web siteleri.',
 'Her pikseli kasitli, markaniza ozel web tasarimlari. Performans, erisilebilirlik ve SEO en bastan dusunulur.',
 '["UI/UX tasarim","Ozel kodlama","Performans optimizasyonu","SEO uyumlu yapi"]', 'layout', 1, 1,
 'Web Tasarim Ankara | Punch Yazilim', 'Ankara web tasarim hizmeti. Ozel, hizli ve SEO uyumlu web siteleri.'),
('e-ticaret-cozumleri', 'E-Ticaret Cozumleri', 'Satisa hazir, olceklenebilir e-ticaret sistemleri.',
 'Kucuk isletmeden kurumsala, buyumeye hazir e-ticaret altyapilari. Odeme, kargo ve pazaryeri entegrasyonlari.',
 '["Sanal POS / PayTR","Pazaryeri entegrasyonu","Stok yonetimi","Kampanya motoru"]', 'shopping-cart', 1, 2,
 'E-Ticaret Cozumleri | Punch Yazilim', 'E-ticaret yazilimi ve kurulumu. PayTR, sanal POS ve pazaryeri entegrasyonlari.'),
('sosyal-medya', 'Sosyal Medya Yonetimi', 'Markanizi buyuten icerik ve reklam yonetimi.',
 'Strateji, icerik ve reklamla sosyal medyada surdurulebilir buyume.',
 '["Icerik uretimi","Grafik & video","Reklam yonetimi","Raporlama"]', 'megaphone', 1, 3,
 'Sosyal Medya Yonetimi | Punch Yazilim', 'Profesyonel sosyal medya yonetimi: icerik, tasarim ve reklam.'),
('yazilim-gelistirme', 'Yazilim Gelistirme', 'Ihtiyaca ozel web & mobil yazilim cozumleri.',
 'Surec otomasyonu, panel, API ve mobil uygulama gelistirme.',
 '["Ozel web uygulamalari","API gelistirme","Panel & otomasyon","Mobil uygulama"]', 'code', 1, 4,
 'Yazilim Gelistirme | Punch Yazilim', 'Ozel yazilim gelistirme: web, mobil, API ve otomasyon cozumleri.')
ON DUPLICATE KEY UPDATE slug = slug;

-- --- Blog kategorileri ---
INSERT INTO `categories` (`slug`, `name`) VALUES
('web-tasarim', 'Web Tasarim'),
('e-ticaret', 'E-Ticaret'),
('seo', 'SEO'),
('sosyal-medya', 'Sosyal Medya')
ON DUPLICATE KEY UPDATE slug = slug;

-- --- Demo blog yazilari ---
INSERT INTO `posts` (`slug`, `title`, `excerpt`, `body`, `category_id`, `author_id`, `status`, `published_at`, `reading_time`, `meta_title`, `meta_description`) VALUES
('2026-web-tasarim-trendleri',
 '2026 Web Tasarim Trendleri: Hareket, Derinlik ve Hiz',
 'Bu yil one cikan web tasarim trendlerini ve bunlari sitenize nasil tasiyabileceginizi inceledik.',
 '<p>Web tasarim her gecen yil daha da deneyim odakli hale geliyor. 2026''da one cikan baslica trendler; akici scroll deneyimleri, derinlik hissi veren cok katmanli arka planlar ve performanstan odun vermeyen mikro-etkilesimler.</p><h2>1. Akici Hareket (Motion)</h2><p>GSAP ve ScrollTrigger gibi araclarla scroll tabanli anlatim artik standart. Onemli olan 60fps performansi korumak.</p><h2>2. Derinlik ve Katman</h2><p>Glassmorphism, gradient mesh ve ince grain dokular sayfalara fiziksel bir derinlik kazandiriyor.</p><h2>3. Hiz Her Seyden Onemli</h2><p>Core Web Vitals (LCP, INP, CLS) artik hem kullanici deneyimi hem de SEO icin kritik.</p><p>Punch Yazilim olarak tum projelerimizi bu ilkeler uzerine insa ediyoruz.</p>',
 1, 1, 'published', DATE_SUB(NOW(), INTERVAL 5 DAY), 4,
 '2026 Web Tasarim Trendleri | Punch Yazilim', '2026 web tasarim trendleri: akici hareket, derinlik ve hiz. Sitenizi gelecege tasiyin.'),
('e-ticarette-donusum-artirma',
 'E-Ticarette Donusum Oranini Artiran 7 Kanitlanmis Yontem',
 'Ziyaretciyi musteriye cevirmenin somut yollari: hiz, guven, sade odeme ve daha fazlasi.',
 '<p>E-ticarette trafik kadar onemli olan donusum oranidir. Iste donusumu artiran kanitlanmis yontemler:</p><h2>1. Sayfa Hizi</h2><p>Her 1 saniyelik gecikme donusumu ciddi dusurur. WebP gorseller ve lazy-load sart.</p><h2>2. Guven Unsurlari</h2><p>SSL, yorumlar, iade politikasi ve guvenli odeme rozetleri.</p><h2>3. Sade Odeme Akisi</h2><p>PayTR gibi guvenli ve tek adimli odeme deneyimi terk oranini dusurur.</p>',
 2, 1, 'published', DATE_SUB(NOW(), INTERVAL 12 DAY), 5,
 'E-Ticarette Donusum Artirma | Punch Yazilim', 'E-ticarette donusum oranini artiran 7 kanitlanmis yontem. Daha fazla satis icin uygulayin.'),
('yerel-seo-rehberi-ankara',
 'Yerel Isletmeler Icin SEO Rehberi: Ankara Ornegi',
 'Ankara''da musterilerinize ulasmanin yolu yerel SEO''dan geciyor. Adim adim anlattik.',
 '<p>Yerel isletmeler icin Google''da gorunur olmak buyumenin anahtaridir. Iste temel adimlar:</p><h2>1. Google Isletme Profili</h2><p>Eksiksiz ve guncel bir profil, harita sonuclarinda one cikmanizi saglar.</p><h2>2. Yerel Anahtar Kelimeler</h2><p>"Ankara web tasarim" gibi konum + hizmet kombinasyonlari.</p><h2>3. Schema.org LocalBusiness</h2><p>Yapilandirilmis veri ile arama motorlarina net sinyal verin.</p>',
 3, 1, 'published', DATE_SUB(NOW(), INTERVAL 20 DAY), 6,
 'Yerel SEO Rehberi Ankara | Punch Yazilim', 'Ankara yerel SEO rehberi. Google''da gorunur olun, yerel musterilere ulasin.')
ON DUPLICATE KEY UPDATE slug = slug;

-- --- Portfolyo (demo) ---
INSERT INTO `portfolio` (`slug`, `title`, `summary`, `body`, `category`, `client`, `is_active`, `sort_order`) VALUES
('nova-kurumsal', 'Nova Teknoloji Kurumsal Site', 'Teknoloji firmasi icin kinetik, koyu temali kurumsal site.', '<p>Marka kimligiyle butunlesen, scroll tabanli anlatima sahip kurumsal web sitesi.</p>', 'Web Tasarim', 'Nova Teknoloji', 1, 1),
('atlas-eticaret', 'Atlas Store E-Ticaret', 'Moda markasi icin yuksek donusumlu e-ticaret deneyimi.', '<p>Hizli, mobil oncelikli ve PayTR entegre e-ticaret platformu.</p>', 'E-Ticaret', 'Atlas Store', 1, 2),
('lumen-sosyal', 'Lumen Sosyal Medya Kampanyasi', 'Restoran zinciri icin buyume odakli sosyal medya yonetimi.', '<p>3 ayda etkilesimde %180 artis saglayan icerik & reklam stratejisi.</p>', 'Sosyal Medya', 'Lumen Group', 1, 3),
('vertex-panel', 'Vertex Yonetim Paneli', 'Lojistik firmasi icin ozel surec otomasyon paneli.', '<p>Operasyonu hizlandiran, gercek zamanli raporlamali ozel yazilim.</p>', 'Yazilim', 'Vertex Lojistik', 1, 4)
ON DUPLICATE KEY UPDATE slug = slug;

-- --- Ayarlar (site metinleri, sosyal, SEO, CMS) ---
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'Punch Yazilim — Dijital Yazilim Ajansi'),
('site_tagline', 'Markanizi dijitalde one cikaran, sonuc ureten yazilim ajansi.'),
('hero_eyebrow', 'Ankara · Dijital Yazilim & Tasarim Ajansi'),
('hero_title', 'Markanizi dijitalde fark yaratan deneyimlere donusturuyoruz'),
('hero_subtitle', 'Sablon degil, size ozel. Hizli yuklenen, Google''da one cikan ve satisa donusen web siteleri, e-ticaret altyapilari ve sosyal medya yonetimi. Stratejiden yayina kadar tek catida.'),
('contact_phone', ''),
('contact_whatsapp', ''),
('contact_email', 'destek@punchyazilim.com'),
('contact_address', 'Yenimahalle, Ankara'),
('contact_maps_embed', ''),
('social_instagram', 'https://instagram.com/punchyazilim'),
('social_linkedin', ''),
('social_x', ''),
('social_youtube', ''),
('seo_default_title', 'Punch Yazilim — Ankara Web Tasarim & Dijital Ajans'),
('seo_default_description', 'Ankara merkezli dijital yazilim ajansi. Web tasarim, e-ticaret, sosyal medya yonetimi ve ozel yazilim cozumleri.'),
('ga4_id', ''),
('google_site_verification', ''),
('stats_json', '[{"value":"120+","label":"Tamamlanan Proje"},{"value":"8+","label":"Yil Tecrube"},{"value":"%98","label":"Musteri Memnuniyeti"},{"value":"24/7","label":"Teknik Destek"}]'),
('testimonials_json', '[{"name":"Mehmet K.","company":"Nova Teknoloji · Kurucu","text":"Eski sitemiz yavas ve eskiydi. Punch ekibi 3 haftada hem pirilti gibi hem de cok hizli bir site teslim etti. Teklif formundan gelen talepler ilk aydan itibaren ikiye katlandi.","rating":5},{"name":"Aylin D.","company":"Atlas Store · E-ticaret Mudiru","text":"PayTR ve kargo entegrasyonu kusursuz calisiyor. Mobilde sayfa hizi cok iyi oldugu icin sepet terk oranimiz belirgin sekilde dustu. Panel kullanimi da cok kolay.","rating":5},{"name":"Caner T.","company":"Lumen Group · Pazarlama","text":"Sosyal medya yonetiminde icerik kalitesi ve raporlama seffafligi bizi etkiledi. 3 ayda etkilesimimiz %180 artti, gercek musteri talepleri gelmeye basladi.","rating":5}]'),
('faq_json', '[{"q":"Bir web sitesi ne kadar surede teslim edilir?","a":"Kurumsal web siteleri, icerik ve gorseller tamamsa ortalama 2-4 hafta icinde yayina alinir. Surecin her asamasinda (tasarim onayi, gelistirme, test) bilgilendirilirsiniz; gizli surpriz yoktur."},{"q":"Odeme nasil yapiliyor, guvenli mi?","a":"Sabit fiyatli paketleri sitemizden PayTR 3D Secure altyapisi ile guvenle online satin alabilirsiniz. Kart bilgileriniz bizde saklanmaz. Daha buyuk kurumsal projelerde asamali odeme plani sunariz."},{"q":"Site sablon mu, yoksa size mi ozel?","a":"Hazir tema satmiyoruz. Her proje; marka kimliginize, hedef kitlenize ve is hedeflerinize gore sifirdan tasarlanir ve kodlanir. Boylece hem ozgun gorunur hem de hizli calisir."},{"q":"SEO ve Google siralamasi dahil mi?","a":"Tum sitelerimiz teknik SEO altyapisiyla (hizli yukleme, mobil uyum, yapilandirilmis veri, sitemap) teslim edilir. Surekli icerik ve SEO buyumesi icin ayrica aylik paketlerimiz vardir."},{"q":"Teslimden sonra destek aliyor muyum?","a":"Evet. Kurumsal paketlerde 1 yil teknik destek dahildir. Panelden 7/24 destek talebi acabilir, guncelleme ve bakim paketlerimizden yararlanabilirsiniz."},{"q":"Iceriklerimi kendim guncelleyebilir miyim?","a":"Elbette. Yonetim paneli (CMS) ile blog yazisi, gorsel, fiyat ve sayfa metinlerini teknik bilgi gerekmeden kolayca guncelleyebilirsiniz."}]')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
