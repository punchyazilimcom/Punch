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
 'Markaniza ozel, hizli ve SEO uyumlu kurumsal web sitesi.',
 'Isletmenizi internette en iyi sekilde temsil eden, mobil uyumlu, hizli yuklenen ve arama motorlarinda one cikan ozel tasarim kurumsal web sitesi. Yonetim paneli ile icerigi kendiniz guncelleyebilirsiniz.',
 '["Ozel tasarim (template degil)","Mobil & tablet uyumlu","Teknik SEO altyapisi","Yonetim paneli (CMS)","SSL sertifikasi & guvenlik","Iletisim formu & harita","Google Analytics entegrasyonu","1 yil teknik destek"]',
 24900.00, 'TRY', 0, 1, 'layout', 1, 1,
 'Kurumsal Web Site Tasarimi | Punch Yazilim Ankara',
 'Ankara kurumsal web site tasarimi. Ozel tasarim, SEO uyumlu, hizli ve mobil uyumlu profesyonel web siteleri.'),

('e-ticaret', 'E-Ticaret Sitesi',
 'Satislarinizi buyutecek, guvenli ve olceklenebilir e-ticaret altyapisi.',
 'Urun yonetimi, sepet, uyelik, sanal POS & PayTR entegrasyonu, kargo ve kampanya yonetimi ile tam donanimli e-ticaret cozumu. Pazaryeri entegrasyonlarina hazir mimari.',
 '["Sinirsiz urun & kategori","Guvenli odeme (PayTR/Sanal POS)","Uyelik & sepet sistemi","Kampanya & kupon yonetimi","Kargo entegrasyonu","Stok & siparis yonetimi","Mobil uyumlu & hizli","SEO & pazaryeri hazir altyapi"]',
 49900.00, 'TRY', 0, 0, 'shopping-cart', 1, 2,
 'E-Ticaret Sitesi Kurulumu | Punch Yazilim',
 'Profesyonel e-ticaret sitesi kurulumu. PayTR & sanal POS entegrasyonu, urun yonetimi ve SEO uyumlu altyapi.'),

('sosyal-medya-yonetimi', 'Sosyal Medya Yonetimi',
 'Markanizi buyuten, donusum odakli sosyal medya yonetimi.',
 'Icerik uretimi, tasarim, reklam yonetimi ve topluluk yonetimi ile markanizi sosyal medyada guclu sekilde konumlandiriyoruz. Ihtiyaciniza ozel paket icin teklif alin.',
 '["Aylik icerik takvimi","Ozel grafik & video tasarim","Reklam (Meta/Google) yonetimi","Topluluk & mesaj yonetimi","Aylik performans raporu","Rakip & trend analizi"]',
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
('site_tagline', 'Markanizi dijitalde one cikaran yazilim ajansi'),
('hero_eyebrow', 'Ankara · Dijital Yazilim Ajansi'),
('hero_title', 'Dijitalde fark yaratan deneyimler tasarliyoruz'),
('hero_subtitle', 'Web tasarim, e-ticaret, sosyal medya ve ozel yazilim. Markanizi hizli, guvenli ve etkileyici dijital cozumlerle buyutuyoruz.'),
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
('testimonials_json', '[{"name":"Mehmet K.","company":"Nova Teknoloji","text":"Sitemiz hem cok hizli hem de inanilmaz sik oldu. Donusumlerimiz gozle gorulur arti.","rating":5},{"name":"Aylin D.","company":"Atlas Store","text":"E-ticaret altyapisi sorunsuz calisiyor, PayTR entegrasyonu kusursuz.","rating":5},{"name":"Caner T.","company":"Lumen Group","text":"Sosyal medya yonetimiyle etkilesimimiz katlandi. Profesyonel bir ekip.","rating":5}]'),
('faq_json', '[{"q":"Bir web sitesi ne kadar surede teslim edilir?","a":"Kurumsal web siteleri genellikle 2-4 hafta icinde teslim edilir. Kapsam ve icerik hazirligina gore sure degisebilir."},{"q":"Odeme nasil yapiliyor?","a":"Sabit fiyatli paketlerde guvenli PayTR altyapisi ile online odeme yapabilirsiniz. Kurumsal projelerde asamali odeme sunariz."},{"q":"SEO calismasi dahil mi?","a":"Tum sitelerimiz teknik SEO altyapisiyla teslim edilir. Suregelen icerik/SEO calismalari icin ayrica paket sunariz."},{"q":"Site teslim sonrasi destek veriyor musunuz?","a":"Evet, tum kurumsal paketlerde 1 yil teknik destek dahildir. Sonrasinda bakim paketleri mevcuttur."}]')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
