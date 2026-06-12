---
name: site-forge
description: >
  Uçtan uca, ödül-seviyesi (Awwwards/FWA) WebGL ve üst düzey grafik/efektli premium web sitesi
  üretim otomasyonu — kurumsal site, açılış sayfası (landing), portfolyo, e-ticaret/panel, her tür.
  Tek bir pipeline'da çok sayıda yeteneği ve mevcut Claude Code skill'lerini orkestre eder:
  Frontend Design + WebGL/Three.js/shader grafik arsenali, MCP/entegrasyon (PayTR, SMTP, Figma,
  özel API), tarayıcı doğrulama (Playwright/verify/run), güvenlik (security-review + nonce CSP),
  kalite (code-review), sadeleştirme (simplify), pazar/SEO araştırması (deep-research) ve CI
  kurulumu (session-start-hook). Cihaza göre uyarlanabilir performans, üst düzey teknik SEO,
  sözleşmeler ve deploy paketleme dahil. Stack varsayılanı PHP 8.1+/MySQL (Hostinger) ama
  Vite+Three.js/Next.js gibi statik/JS hedeflere de uyarlanır. Kullanıcı "WebGL site / üst düzey
  efektli site / kozmik/3D/animasyonlu site / premium kurumsal site + panel / landing page /
  site yapma otomasyonu" gibi bir şey istediğinde ya da /site-forge çağrıldığında kullan.
---

# SITE-FORGE — WebGL & Üst Düzey Grafik Web Sitesi Üretim Otomasyonu

Bu skill, "dünyada tek" hissi veren, WebGL ve ağır grafik destekli premium bir web deneyimini
(gerekirse müşteri paneli + admin paneli ile) **eksiksiz ve deploy'a hazır** üretmek için tek bir
pipeline'da çok sayıda yeteneği ve diğer skill'leri orkestre eder. Yarım iş, TODO, placeholder
bırakma. Her fazın sonunda bir KAPI (gate) vardır; geçmeden sonrakine geçme.

## KAPSAM (kendini sınırlama)
- Bu skill **yalnız 5 yetenekle veya tek temayla sınırlı DEĞİL.** Aşağıdaki grafik arsenalinden
  (Three.js, raw shader, particle/fluid sim, 3D GLTF, post-processing, generative SVG, scroll-driven
  animasyon…) projeye uygun olanları seç ve birleştir. Tam liste: `reference/capability-library.md`.
- **Tema ön ayarları** (kozmik yalnızca biri): cosmic/uzay, liquid/fluid, glassmorphic, neo-brutalist,
  particle-field, 3D-product, generative-art, editorial-kinetik, retro-CRT. Marka ve sektöre göre seç
  ya da harmanla; istersen birden fazlasını birleştir.
- Site türü değişebilir: kurumsal, **landing page**, portfolyo, e-ticaret, etkinlik, SaaS. Panel/admin
  yalnız gerekiyorsa kur (FAZ 3 koşullu).
- Stack varsayılanı PHP/MySQL (panel+ödeme+Hostinger için ideal). Sadece statik/animasyon ağırlıklı
  bir vitrin isteniyorsa **Vite + Three.js + GSAP** (veya Next.js) hedefine geç; pipeline aynı kalır.

## ZİNCİRLENEN CLAUDE CODE SKILL'LERİ (mevcutsa kullan)
- **deep-research** → FAZ 0: sektör/rakip/anahtar kelime ve referans (Awwwards) araştırması.
- **session-start-hook** → FAZ 0: web oturumlarında lint/test çalışsın diye SessionStart hook'u.
- **claude-api** → AI özelliği (chatbot, içerik üretici, öneri) eklenecekse model/SDK referansı.
- **security-review** → FAZ 4: bekleyen değişikliklerde tam güvenlik incelemesi.
- **code-review** → FAZ 5: doğruluk/bug taraması (gerekirse `--fix`).
- **simplify** → FAZ 5: tekrar/ölü kod temizliği (davranışı bozmadan).
- **verify** / **run** → FAZ 6: uygulamayı gerçekten çalıştırıp davranışı gözlemle/ekran görüntüsü al.
- **update-config** → permission/hook/env ayarları gerektiğinde.
- Yeni bir tekrarlı iş (ör. PR babysit) gerekirse **loop**; PR/issue işleri için **review**.
Bu skill'ler yoksa, ilgili işi kendin yap (incele/sadeleştir/çalıştır).

## Girdi / Varsayımlar
Kullanıcıdan eksikse şunları topla (yoksa makul varsayım yap, kod içinde yorumla belirt):
marka adı, sektör, konum (yerel SEO), iletişim (e-posta/telefon/WhatsApp/adres), marka renkleri
(birebir hex), ödeme sağlayıcı (varsayılan: PayTR), hedef hosting (varsayılan: Hostinger paylaşımlı).
Tam spesifikasyon için: `reference/master-prompt.md`.

## Pipeline (sırayla, her fazda KAPI var)

### FAZ 0 — Keşif & İskelet
- Stack: PHP 8.1+ (PDO prepared statements) + MySQL, hafif MVC (front controller + router +
  saf PHP template: layout/partial/section). Framework yok. Composer: phpdotenv, phpmailer,
  dompdf, php-jwt.
- Dizin: `app/{Core,Controllers,Models,Services,Middleware,Views}`, `config/`, `database/{migrations,seeds}`,
  `storage/`, `public_html/{index.php,.htaccess,assets}`, `bin/migrate.php`, `.env.example`.
- KAPI: `php -l` tüm çekirdek dosyalarda temiz; bootstrap + router + bir 404 ayakta.

### FAZ 1 — Frontend Design (tema + WebGL/grafik arsenali)
- Önce TEMA seç (cosmic/liquid/glass/brutalist/particle/3D-product/generative/editorial/retro) ve
  marka renklerini BİREBİR `tokens.css`'e koy. Token tabanlı CSS: `tokens.css`, `base.css`,
  `components.css`, `effects.css`, `interactions.css` (+ temaya özel katman, ör. `cosmos.css`). Tailwind YOK.
- Grafik tekniklerini `reference/capability-library.md`'den seç-birleştir (Three.js/shader/particle/
  fluid/3D/post-processing/generative SVG/scroll-driven…). Kozmik referans uygulaması ve uyarlanabilir
  performans motoru için: `reference/design-system.md`.
- Kural: tüm efektler 60fps, yalnız transform/opacity (DOM) ve GPU (WebGL); `prefers-reduced-motion`
  tam destekli; WebGL/ağır efekt yoksa zarif fallback; JS kapalıyken içerik tam görünür (noscript).
- **Uyarlanabilir performans motoru (perf.js) ZORUNLU**: cihaza göre high/medium/low/off + FPS gözcüsü
  + müşteri elle seçimi. Ağır grafikler yalnız uygun kademede çalışsın.
- KAPI: ana sayfa/landing şablonu sahte veriyle render olur; `node --check` tüm JS'de temiz; düşük
  kademede ağır efektlerin kapandığı doğrulanır.

### FAZ 2 — İçerik & Veri (şablon metin YASAK)
- Gerçek, ikna edici Türkçe metinler: hero, hizmet/paket açıklamaları + madde özellikler,
  gerçekçi yorumlar, 6+ SSS, süreç adımları, hakkımızda. Hepsi admin CMS'ten düzenlenebilir.
- DB şeması (migrations) + demo seed + UPDATE-only içerik güncelleme scripti.
- KAPI: `bin/migrate.php` ile şema+seed kurulur (varsa MySQL); seed JSON/SQL geçerli.

### FAZ 3 — İş Mantığı (panel + admin + ödeme + sözleşmeler)
- Müşteri paneli (/panel) ve admin (/admin) tüm modülleriyle; çift guard auth (Argon2id).
- PayTR iFrame: sunucu taraflı get-token (hash sırası dokümana BİREBİR) + hash doğrulamalı
  callback + idempotency + "OK" yanıtı + otomatik fatura (dompdf) + mail.
- Satın alma öncesi ZORUNLU sözleşme onayı (Ön Bilgilendirme + Mesafeli Satış), audit log'a kaydet.
  Tüm hukuki metinler kodda hazır (`LegalText`), panelden düzenlenebilir.
- KAPI: tüm route handler'lar reflection ile çözümlenir (`scripts/verify.sh`).

### FAZ 4 — Güvenlik & SEO sertleştirme (GERÇEK, laf değil)
- Güvenlik + SEO tam listesi ve uygulama detayı: `reference/security-seo.md` (zorunlu).
- Özellikle: **nonce tabanlı CSP** (script-src'de `'unsafe-inline'` YOK), CSRF, rate-limit,
  güvenli upload, güvenlik başlıkları, .htaccess deny, security.txt.
- SEO: JSON-LD (Organization sameAs + LocalBusiness + Product + BlogPosting + Breadcrumb + FAQ),
  OG/Twitter, hreflang, sitemap.xml + robots.txt + RSS /feed.xml, CWV optimizasyonu.
- KAPI: CSP header'daki nonce ile inline script nonce'u BİREBİR eşleşir (curl ile doğrula).

### FAZ 5 — Code Review + Refactor
- `/code-review` mantığı: değişen diff'i güvenlik (XSS/SQLi/CSRF/yetki/secret sızıntısı) ve
  doğruluk açısından tara; bulguları düzelt.
- `/simplify` mantığı: tekrarı azalt, ölü kodu temizle, isimlendirmeyi tutarlı yap — DAVRANIŞI BOZMA.
- KAPI: tekrar `php -l` + route + render testleri temiz.

### FAZ 6 — Playwright doğrulama (gerçek tarayıcı)
- MySQL mevcutsa: yerel sunucuyu başlat (`php -S`), ana sayfa + giriş + paket-detay + checkout
  akışını gerçek tarayıcıda aç, ekran görüntüsü al, konsol hatası/CSP ihlali olmadığını doğrula.
- MySQL yoksa: DB gerektirmeyen sayfaları (404, hukuki, sitemap) + tüm şablonları sahte veriyle
  render-test et; ayrıca self-contained statik demo HTML üret (CSS/JS inline) ve kullanıcıya gönder.
- KAPI: en az 1 görsel kanıt (demo HTML veya ekran görüntüsü) üretildi.

### FAZ 7 — Paketleme & Teslim
- `scripts/package.sh` ile iki ZIP üret: **TAM** (ilk kurulum, `.env` + DB SQL dahil) ve
  **GÜNCELLEME** (`.env` ve yüklenen görseller hariç — mevcut kurulumun üzerine yazılır).
- Tek-kök Hostinger yapısı: içerik doğrudan `public_html`'e açılır; app/config/vendor/storage/database
  alt klasör ama `.htaccess` ile web'e kapalı; `index.php` bootstrap yolu `__DIR__` ile düzeltilir.
- README + HOSTINGER-KURULUM.txt (adım adım: DB import, .env, izinler, SSL, PayTR callback).
- KAPI: paketlenen yapı `php -S` ile boot eder (giriş/feed/security.txt = 200).

## Doğrulama (her commit öncesi)
`scripts/verify.sh` çalıştır: tüm PHP `php -l`, route reflection çözümlemesi, şablon render smoke
testi, JS `node --check`. Hepsi yeşil olmadan commit/teslim etme.

## MCP / Entegrasyon notları (MCP Builder yeteneği)
- Figma MCP bağlıysa tasarımı oradan çekebilir/itebilirsin (skill://figma).
- GitHub MCP ile PR aç (draft), CI'yi izle.
- Kullanıcının kendi sistemini (stok, kargo, PayTR raporu) bağlamak gerekirse küçük bir MCP
  sunucusu yaz (Anthropic Agent SDK / MCP), anahtarları `.env`'de tut.

## Kabul Kriteri (Definition of Done)
Lighthouse mobil hedef: Performance ≥90, SEO=100, Best Practices ≥95, Accessibility ≥95.
Tüm PHP lint temiz · tüm route'lar çözümlenir · tüm şablonlar render olur · JS sözdizimi temiz ·
CSP nonce header↔body eşleşir · iki ZIP boot eder. Eksiksiz, çalışır, deploy'a hazır.

## Referans dosyaları
- `reference/master-prompt.md` — tam ürün spesifikasyonu (kopyalanabilir master prompt)
- `reference/capability-library.md` — WebGL/grafik tekniği arsenali + tema ön ayarları + stack seçenekleri
- `reference/design-system.md` — kozmik referans efektleri + uyarlanabilir performans motoru
- `reference/security-seo.md` — güvenlik + SEO uygulama detayları ve kontrol listesi
- `scripts/verify.sh` — lint + route + render doğrulama
- `scripts/package.sh` — TAM + GÜNCELLEME Hostinger ZIP üretimi
