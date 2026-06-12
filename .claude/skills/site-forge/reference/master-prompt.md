# Master Prompt — Tam Ürün Spesifikasyonu

> Bu, site-forge pipeline'ının üreteceği ürünün tam tanımıdır. Bağımsız bir geliştiriciye/AI'ya
> da verilebilir. Köşeli parantezli alanları markaya göre doldur.

## 0) Ürün
[MARKA] için kurumsal site + müşteri paneli + admin paneli. Müşteriler kayıt olur, paketleri görür,
PayTR iFrame ile öder, fatura indirir, ticket açar, teklif ister. Admin her şeyi yönetir.

## 1) Teknoloji & Mimari
- PHP 8.1+ (PDO prepared statements) + MySQL 8 / MariaDB.
- Hafif MVC: front controller (index.php) + router + saf PHP template (layout/partial/section).
- Composer: vlucas/phpdotenv, phpmailer/phpmailer, dompdf/dompdf, firebase/php-jwt. Build OLMADAN çalışsın.
- Server-rendered HTML (SEO + ilk boya) + progressive enhancement (GSAP3 + ScrollTrigger + Lenis, CDN defer).
- Token tabanlı CSS (Tailwind YOK). Çift guard auth (müşteri+admin), Argon2id. PHPMailer SMTP, dompdf PDF.
- Deploy: Hostinger tek-kök (içerik doğrudan public_html'e; app/config/vendor/storage/database .htaccess ile kapalı).

## 2) Tasarım — kozmik, WebGL destekli
Bkz. `design-system.md`. Koyu mor/siyah uzay teması + cyan/pembe vurgu. WebGL nebula + fareyle kara delik +
tıkla supernova; yıldız alanı, portal, yörünge gezegenler, 3D halkalı+uydulu gezegen, hiperuzay açılış,
warp scroll, kuyruklu yıldız imleci, pinned storytelling, sinematik slider, kinetik harf reveal, sayaçlar,
magnetik nav, ripple, durum etiketli imleç, sayfa geçişleri, header autohide, yukarı-çık. Hepsi 60fps,
prefers-reduced-motion destekli, WebGL fallback'li.

## 3) Uyarlanabilir performans
perf.js: cihaza göre OTOMATİK kademe (high/medium/low/off) + çalışma anı FPS düşünce otomatik düşürme +
müşteri elle seçimi (footer, localStorage). html[data-perf] ile CSS gating.

## 4) İçerik
Şablon metin YASAK. Gerçek Türkçe metinler; admin CMS'ten düzenlenebilir; demo seed dolu.

## 5) Sayfalar
Public: ana sayfa, hizmetler(+detay), paketler(+detay), portfolyo(+detay filtreli), hakkımızda,
blog(liste+kategori+tekil), iletişim(spam korumalı), 404/500. Panel(/panel): dashboard, paketlerim,
faturalar(PDF), destek(ticket+ek), teklifler, profil. Admin(/admin): dashboard(KPI+grafik), müşteriler,
siparişler, faturalar(oluştur+PDF), teklifler, destek, Paket/Blog/Portfolyo CRUD, içerik CMS, ayarlar,
loglar, CSV export.

## 6) PayTR iFrame
Anahtarlar .env'de. pending sipariş + merchant_oid → sunucudan get-token (hash sırası dokümana BİREBİR,
HMAC-SHA256) → iFrame → callback hash doğrula → paid + fatura + mail, "OK" döndür; idempotency; kuruş(×100).

## 7) Sözleşmeler
Ön Bilgilendirme, Mesafeli Satış, Teslimat/İade, KVKK, Gizlilik, Çerez (kodda hazır, panelden düzenlenir).
Satın almada Ön Bilgilendirme + Mesafeli Satış için ZORUNLU onay; audit log. Çerez banner (consent mode).

## 8) Güvenlik & 9) SEO
Bkz. `security-seo.md` (nonce CSP, CSRF, rate-limit, güvenli upload, başlıklar, .htaccess, security.txt;
JSON-LD, OG/Twitter, hreflang, sitemap+robots+RSS, CWV).

## 10) Veritabanı & Teslimat
migrations + seeds + UPDATE-only content script + bin/migrate.php. Tablolar: users, admins, packages,
services, orders, invoices, tickets, ticket_messages, quotes, posts, categories, portfolio,
contact_messages, settings, audit_logs, password_resets, rate_limits. README + Hostinger adım adım.
İki ZIP: TAM (.env+DB dahil) + GÜNCELLEME (.env+upload hariç).

## Kabul kriteri
Lighthouse mobil P≥90, SEO=100, BP≥95, A11y≥95. Lint temiz, route'lar çözümlenir, render olur,
JS temiz, CSP nonce eşleşir.

## Marka
Marka: [AD] · Sektör: [..] · Konum: [şehir/ilçe] · İletişim: [..] · Renkler: [hex → tokens.css] · Ton: premium Türkçe.
