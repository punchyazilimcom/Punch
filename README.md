# Punch Yazılım — Kurumsal Site + Müşteri Paneli + Admin Paneli

Premium, koyu mor/siyah temalı, animasyonlu (GSAP + Lenis) ve **server-rendered** (SEO-odaklı) bir dijital ajans platformu. PHP 8.1+ / MySQL 8 üzerinde, framework şişirmesi olmadan, hafif bir MVC mimarisiyle yazılmıştır.

> **Marka renkleri hakkında not:** Canlı site (`punchyazilim.com`) bu geliştirme ortamından ağ politikası nedeniyle erişime kapalı olduğundan, marka paleti brief'teki tanıma (koyu mor / siyah) göre **`public_html/assets/css/tokens.css`** içinde merkezî token'lar olarak tanımlandı. Canlı sitedeki **birebir hex değerlerini** aldıktan sonra yalnızca `tokens.css` dosyasındaki `--punch-violet-*` ve `--bg-*` değişkenlerini güncellemeniz yeterlidir; tüm site otomatik uyum sağlar.

---

## İçindekiler
1. [Özellikler](#özellikler)
2. [Teknoloji](#teknoloji)
3. [Proje Yapısı](#proje-yapısı)
4. [Yerelde Çalıştırma](#yerelde-çalıştırma)
5. [Hostinger Paylaşımlı Hostinge Deploy](#hostinger-paylaşımlı-hostinge-deploy)
6. [PayTR Entegrasyonu](#paytr-entegrasyonu)
7. [E-posta (SMTP)](#e-posta-smtp)
8. [Asset Build (opsiyonel)](#asset-build-opsiyonel)
9. [Demo Giriş Bilgileri](#demo-giriş-bilgileri)
10. [Güvenlik Kontrol Listesi](#güvenlik-kontrol-listesi)
11. [SEO Kontrol Listesi](#seo-kontrol-listesi)

---

## Özellikler

**Public site:** Ana sayfa (kinetik hero + canvas mesh), Hizmetler, Paketler, Portfolyo, Hakkımızda, Blog (kategori + tekil), İletişim, Hukuki sayfalar (KVKK/Gizlilik/Mesafeli Satış/Çerez/Teslimat), çerez onay banner'ı, özel 404/500.

**Müşteri paneli (`/panel`):** Dashboard, Paketlerim, Faturalarım (PDF indir), Destek (ticket + dosya eki + thread), Teklifler (kabul/red), Profil & Güvenlik (şifre değiştirme).

**Admin paneli (`/admin`):** Dashboard (KPI + gelir trendi + paket dağılımı), Müşteriler (cari ekstre), Siparişler, Faturalar (oluştur + PDF), Teklifler (fiyatlandır + mail), Destek (yanıt + dahili not), Paketler/Blog/Portfolyo CRUD, Sayfa İçerikleri CMS (hero/istatistik/yorum/SSS/hukuki), Ayarlar, Denetim Logları, CSV export.

**Ödeme:** PayTR iFrame API — sunucu taraflı token + hash doğrulamalı callback + idempotency + otomatik fatura.

---

## Teknoloji
- **Backend:** PHP 8.1+ (PDO prepared statements), MySQL 8 / MariaDB
- **Mimari:** Front controller + Router + saf PHP template motoru (layout/partial/section)
- **Frontend:** Server-rendered HTML + progressive enhancement (GSAP 3, ScrollTrigger, Lenis CDN), token-tabanlı modern CSS (Tailwind yok), custom cursor, magnetik butonlar, 3D tilt, canvas hero
- **Paketler (Composer):** `vlucas/phpdotenv`, `phpmailer/phpmailer`, `dompdf/dompdf`, `firebase/php-jwt`
- **Auth:** Session tabanlı çift guard (müşteri + admin), Argon2id

---

## Proje Yapısı
```
/app
  /Core          Router, Request, Response, View, Auth, Database, Csrf, Session,
                 Validator, RateLimiter, Mailer, Logger, Env, App, helpers.php
  /Controllers   Public + /Panel + /Admin controller'ları
  /Models        Aktif-kayıt benzeri hafif modeller
  /Services      Paytr, Order, Invoice, Upload, Seo
  /Middleware    Auth / Guest / Admin
  /Views         layouts, partials, pages, panel, admin, emails, pdf, errors
  bootstrap.php  Autoload + config + App boot
  routes.php     Tüm rota tanımları
/config          config.php (.env okur)
/database        /migrations  /seeds
/storage         /logs /invoices /uploads /cache   (web kök DIŞINDA)
/public_html     index.php, .htaccess, paytr-notify.php, robots.txt, /assets
/bin             migrate.php (DB kurulum CLI)
.env.example     Tüm değişkenler
build.mjs        Opsiyonel asset minify (esbuild)
```

---

## Yerelde Çalıştırma

```bash
# 1) Bağımlılıklar
composer install

# 2) Ortam dosyası
cp .env.example .env
php -r "echo 'APP_KEY=' . bin2hex(random_bytes(32)) . PHP_EOL;"   # çıktıyı .env'e yazın
# .env içinde DB_*, MAIL_*, PAYTR_* değerlerini doldurun
# Yerelde: APP_DEBUG=true, COOKIE_SECURE=false, APP_URL=http://localhost:8000

# 3) Veritabanı (MySQL çalışıyor olmalı)
php bin/migrate.php          # şema + demo veri
# php bin/migrate.php --fresh # tabloları sıfırlayıp yeniden kur

# 4) Sunucu
php -S localhost:8000 -t public_html
```

`http://localhost:8000` → site · `/giris` → müşteri · `/admin/giris` → admin.

---

## Hostinger Paylaşımlı Hostinge Deploy

Hostinger'da doküman kökü `public_html/`'dir. Güvenlik için uygulama kodu (`app`, `config`, `vendor`, `storage`, `database`) **kökün dışında** durmalıdır.

### Adım 1 — Dosyaları yükleyin
Repoyu hesabınızın **ev dizinine** (örn. `/home/uXXXX/punch/`) yükleyin (Git veya FTP). Hedef yapı:
```
/home/uXXXX/punch/        <- app, config, vendor, storage, database, .env, bin ...
/home/uXXXX/public_html/  <- public_html/ İÇERİĞİ buraya
```
İki seçenek:
- **A) public_html içeriğini taşıyın:** Repodaki `public_html/*` dosyalarını Hostinger'ın `public_html/` klasörüne kopyalayın.
- **B) Symlink (SSH varsa):** `ln -s /home/uXXXX/punch/public_html /home/uXXXX/public_html`

> `index.php` ve `paytr-notify.php` içindeki `require dirname(__DIR__) . '/app/bootstrap.php'` yolu, `public_html`'in repo ile aynı üst dizinde olmasını bekler. A seçeneğinde `public_html`'i repo klasörünün içinde bırakıp, Hostinger panelinden **doküman kökünü** `punch/public_html`'e ayarlamak en temiz yoldur (hPanel → Gelişmiş → "Document Root").

### Adım 2 — Composer
SSH varsa: `cd /home/uXXXX/punch && composer install --no-dev --optimize-autoloader`
SSH yoksa: yerelde `composer install --no-dev` çalıştırıp `vendor/` klasörünü FTP ile yükleyin.

### Adım 3 — Veritabanı
hPanel → MySQL Veritabanları → veritabanı + kullanıcı oluşturun. Ardından:
- **phpMyAdmin** ile `database/migrations/001_schema.sql` ve `database/seeds/001_seed.sql` dosyalarını **içe aktarın**, _veya_
- SSH'de `php bin/migrate.php`.

### Adım 4 — `.env`
`.env.example`'ı `.env` olarak kopyalayıp doldurun:
- `APP_ENV=production`, `APP_DEBUG=false`, `COOKIE_SECURE=true`
- `APP_URL=https://punchyazilim.com`, `APP_KEY=<64 hex>`
- `DB_*`, `MAIL_*`, `PAYTR_*`

### Adım 5 — İzinler
`storage/` ve alt klasörleri (`logs`, `invoices`, `uploads`, `cache`) ile `public_html/assets/uploads/` yazılabilir olmalı (`755`/`775`).

### Adım 6 — HTTPS & SSL
Hostinger'da ücretsiz SSL'i etkinleştirin. `public_html/.htaccess` zaten HTTP→HTTPS ve www→non-www 301 yönlendirmesi yapar; HSTS başlığı da gönderir.

### Adım 7 — Cron (opsiyonel)
Zamanlanmış blog yazıları `published_at` geçtiğinde otomatik görünür (sorgu tarih filtreli). Ek cron gerekmez.

---

## PayTR Entegrasyonu

1. PayTR mağaza panelinden `Mağaza ID`, `Mağaza Anahtarı`, `Mağaza Salt` alın ve `.env`'e yazın.
2. **Bildirim URL'i** olarak şunu girin: `https://punchyazilim.com/paytr-notify.php`
3. `PAYTR_TEST_MODE=1` ile test edin; canlıda `0` yapın.

**Akış:** Müşteri "Satın Al" → `orders` tablosunda `pending` sipariş + benzersiz `merchant_oid` → sunucudan `get-token` (hash: `merchant_id+user_ip+merchant_oid+email+payment_amount+user_basket+no_installment+max_installment+currency+test_mode` + salt, HMAC-SHA256) → iFrame gösterilir → PayTR `paytr-notify.php`'ye POST → hash doğrulanır (`merchant_oid+salt+status+total_amount`) → başarılıysa `paid` + fatura + mail; callback **`OK`** döndürür (idempotent).

> Gerçek doğrulama **her zaman** callback ile yapılır; dönüş sayfasına (`/odeme/basarili`) güvenilmez. Tutarlar kuruş cinsindendir (`amount * 100`).

---

## E-posta (SMTP)
`.env` içindeki `MAIL_*` değerlerini doldurun (Hostinger SMTP: `smtp.hostinger.com`, port `465`, `ssl`). Gönderilen mailler: hoşgeldin, şifre sıfırlama, ödeme başarılı, yeni ödeme/iletişim/teklif (admin), ticket yanıtı. SMTP yapılandırılmamışsa sistem çalışmaya devam eder; mail denemesi loglanır.

---

## Asset Build (opsiyonel)
Site **build olmadan** çalışır (JS tarayıcıda hazır, CSS `.htaccess` ile Brotli/Gzip sıkıştırılır, `ASSET_VERSION` ile cache-busting yapılır). Ekstra minify için:
```bash
npm install
npm run build      # *.min.js + app.min.css üretir
```
Ardından `.env` içindeki `ASSET_VERSION`'ı artırın.

---

## Demo Giriş Bilgileri
Seed verisiyle gelir (`database/seeds/001_seed.sql`):
- **Admin:** `destek@punchyazilim.com` / `PunchAdmin!2026`
- **Müşteri:** `demo@punchyazilim.com` / `Demo!2026`

> ⚠️ **Canlıya almadan önce bu şifreleri mutlaka değiştirin.**

---

## Güvenlik Kontrol Listesi
- [x] Tüm DB erişimi PDO **prepared statements** (SQL injection yok)
- [x] Tüm dinamik çıktı `e()` / `htmlspecialchars` ile kaçışlı (XSS)
- [x] **CSRF token** tüm POST/PUT/DELETE formlarında (`Csrf::check`)
- [x] Rate limiting: giriş, kayıt, iletişim, teklif, PayTR token
- [x] Güvenli session: `HttpOnly`, `Secure`, `SameSite`, login'de regenerate, mutlak timeout
- [x] Argon2id parola hash + tek kullanımlık süreli şifre sıfırlama token'ı
- [x] Dosya yükleme: MIME + boyut + uzantı doğrulama, web kökü dışı (ticket), yeniden adlandırma
- [x] Güvenlik başlıkları: CSP (PayTR iFrame + GSAP/Lenis CDN izinli), X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS
- [x] `.env`/`vendor`/`app`/`config`/`storage` web'den erişime kapalı (`.htaccess` deny)
- [x] Admin paneli ayrı login + opsiyonel IP allowlist + audit log
- [x] PayTR callback hash doğrulaması zorunlu; sahte bildirim reddedilir
- [x] Hatalar kullanıcıya generic, detaylar `storage/logs/app.log`'a

## SEO Kontrol Listesi
- [x] Her sayfada benzersiz `<title>` + `meta description` (panelden düzenlenebilir)
- [x] Canonical URL, temiz/slug URL'ler, trailing-slash normalizasyonu
- [x] **JSON-LD:** Organization, ProfessionalService (LocalBusiness), WebSite+SearchAction, Service, Product/Offer, BlogPosting, BreadcrumbList, FAQPage
- [x] Open Graph + Twitter Card (her sayfa, dinamik OG)
- [x] Otomatik `/sitemap.xml` (sayfalar + blog + portfolyo + paketler, lastmod) + `robots.txt`
- [x] Core Web Vitals: görsel `width/height` (CLS), `loading=lazy`, font `display=swap` + preconnect, JS `defer`, uzun cache
- [x] Tek `<h1>`, mantıklı `h2/h3` hiyerarşisi, anlamlı `alt`
- [x] GA4 + Search Console doğrulama alanı (panelden, çerez onayına bağlı **consent mode**)
- [x] `prefers-reduced-motion` tam destekli; WCAG AA kontrast, görünür focus, klavye erişimi

---

### Geliştirici Notları
- Rota tanımları: `app/routes.php` · İş mantığı: `app/Controllers` · Görseller: `app/Views`
- Tasarım token'ları: `public_html/assets/css/tokens.css` (marka renkleri buradan)
- Etkileşim: `public_html/assets/js/app.js` (+ `hero.js`)
- Loglar: `storage/logs/app.log`
