# Güvenlik & SEO — Uygulama Detayları (gerçek, laf değil)

## Güvenlik
- **Veritabanı**: yalnız PDO prepared statements; LIMIT/OFFSET int cast; ORDER BY whitelist (kullanıcı girdisi yok).
- **Çıktı**: tüm dinamik çıktı `e()`/htmlspecialchars (ENT_QUOTES, UTF-8). HTML içerik alanları (blog/sözleşme)
  yalnız admin girer (güvenilir).
- **Nonce tabanlı CSP** (kritik): `Response::securityHeaders()` her istekte bir nonce üretir
  (`base64(random_bytes(16))`); `script-src 'self' 'nonce-XXX' <güvenilir CDN>` — `'unsafe-inline'` YOK.
  Tüm inline `<script>` etiketlerine `nonce="<?= csp_nonce() ?>"` ekle. `style-src` için `'unsafe-inline'`
  kalabilir (inline style attribute'ları). securityHeaders, view render'dan ÖNCE çağrılmalı (Controller::render).
- **Diğer başlıklar**: X-Frame-Options SAMEORIGIN, X-Content-Type-Options nosniff, Referrer-Policy
  strict-origin-when-cross-origin, Permissions-Policy, COOP same-origin, CORP same-origin,
  X-Permitted-Cross-Domain-Policies none, HSTS (HTTPS'te), upgrade-insecure-requests, frame-ancestors 'self'.
- **CSRF**: tüm POST/PUT/DELETE'te token; `Csrf::check` middleware.
- **Rate limit** (DB tabanlı): login, kayıt, iletişim, teklif, PayTR token, şifre sıfırlama.
- **Session**: HttpOnly + Secure + SameSite, login'de regenerate, mutlak timeout.
- **Parola**: Argon2id; şifre sıfırlama tek kullanımlık süreli (hash'li token).
- **Upload**: finfo MIME + boyut + uzantı; web kökü dışı (ticket) / yalnız görsel (public); yeniden adlandır.
- **Admin**: ayrı guard + opsiyonel IP allowlist + audit log; Panel/Admin'e `X-Robots-Tag: noindex` +
  `Cache-Control: no-store`.
- **PayTR callback**: hash doğrula (merchant_oid+salt+status+total_amount); sahte bildirim reddet; idempotent.
- **.htaccess**: HTTP→HTTPS + www→non-www 301; `Options -Indexes`; `ServerSignature Off`; deny:
  `.env*`, `.git*`, `composer.*`, `package*.json`, ve `.(sql|log|md|lock|ini|ya?ml|bak|sh|dist|example)$`.
  app/config/vendor/storage/database/bin dizinlerinde "Require all denied" .htaccess.
- **/.well-known/security.txt** (Contact + Policy + Expires).
- Hatalar kullanıcıya generic; detay `storage/logs/app.log`.

### KAPI testi
`curl -sD - .../kvkk` → CSP header'daki `nonce-XXX` ile body'deki `<script nonce="XXX">` BİREBİR eşleşmeli;
`script-src`'de `'unsafe-inline'` OLMAMALI.

## SEO
- Her sayfa benzersiz `<title>` (≤60) + meta description (≤155), panelden düzenlenebilir; canonical (mutlak).
- Temiz/slug URL, trailing-slash normalizasyonu (Request'te), 301 yönetimi.
- **JSON-LD**: Organization (logo + email + `sameAs` sosyal + `contactPoint` telefon), LocalBusiness/
  ProfessionalService (adres/saat), WebSite + SearchAction, Service, Product/Offer (fiyatlı paketler),
  BlogPosting (yazılar), BreadcrumbList, FAQPage.
- **OG + Twitter**: her sayfa; `og:image` + width/height/alt; dinamik (blog kapağı).
- **hreflang**: tr + x-default. **robots meta**: index'lenenlerde `index, follow, max-image-preview:large,
  max-snippet:-1`; özel alanlarda `noindex, nofollow`.
- **Otomatik**: `/sitemap.xml` (sayfalar+blog+portfolyo+paketler, lastmod), `/robots.txt` (Sitemap satırı),
  blog **RSS `/feed.xml`** (RSS2.0 + atom self-link), head'de `<link rel="alternate" rss>` + `<link rel="sitemap">`.
- **GA4** consent-mode (çerez onayına bağlı) + Search Console doğrulama alanı (panelden).
- **CWV**: görsel width/height (CLS=0), `loading=lazy`, font `display=swap` + preconnect, JS `defer`,
  WebP/AVIF + srcset, Brotli/Gzip + uzun cache + ASSET_VERSION cache-busting, kritik kaynak preconnect.
- Tek `<h1>`, mantıklı h2/h3; anlamlı `alt`; semantic HTML; iç linkleme (hizmet↔portfolyo↔blog↔CTA).

### KAPI testi
`/sitemap.xml`, `/feed.xml`, `/robots.txt` 200; her sayfada tek h1; JSON-LD geçerli (Rich Results uyumlu).
