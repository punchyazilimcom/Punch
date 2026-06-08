# Kozmik WebGL Tasarım Sistemi + Uyarlanabilir Performans

## Renk & token (tokens.css)
- Koyu mor/siyah uzay zemini: `--cosmic-deep: #050310`, `--bg-0..3`. Marka moru `--punch-violet-500/600`,
  magenta `--punch-magenta`. Kozmik vurgu: `--nebula-cyan:#36e0d6`, `--nebula-pink:#ff5fd1`, `--star:#dfe7ff`.
- Marka renklerini MÜŞTERİDEN al ve BİREBİR koy; uydurma. Tüm site bu token'lardan beslenir.
- Akışkan tipografi: `--fs-*` clamp() ile. Display: Clash Display/Satoshi (Fontshare), gövde: Inter.

## Efekt katmanları (dosya → içerik)
- **effects.css**: scroll progress, preloader, aurora (akışkan gradient bloblar), grid-lines,
  animasyonlu gradient metin, spotlight kartlar, glow-border (conic), reveal-mask (clip-path),
  tilt-shine, section-index, btn-glow, kinetik `.char`.
- **cosmos.css**: boyutsal portal (conic halka + olay ufku + dönen ringler), yörünge gezegenler,
  holografik başlık (.holo), kuyruklu yıldız imleci (#comet-trail), warp-streaks, yıldız tozu
  (.starfield-bg), WebGL nebula konumu (#nebula-gl), 3D gezegen (#planet-canvas), data-perf kapıları.
- **interactions.css**: ripple, imleç etiketi (cursor-label), sayfa geçişi (page-enter/leave),
  header autohide (.site-header.hide), yukarı-çık (.to-top + ilerleme halkası), sinematik slider (.tslider),
  pinned story (.story / sticky / num / fill / step).

## JS modülleri (dosya → görev, hepsi data-perf'e ve reduced-motion'a bağlı)
- **perf.js** (HEAD'de, defersiz, EN ÖNCE): cihaz tespiti → high/medium/low/off; FPS gözcüsü ile
  otomatik düşürme; `window.PunchPerf` API'si; footer seçici; `html[data-perf]` set eder.
- **cosmos-gl.js**: WebGL fragment shader nebula (FBM) + fareyle kara delik (lensing+burgaç+akresyon) +
  tıkla supernova (u_pulse). Yalnız `high`; WebGL yoksa sessizce çık; downgrade'de kapan.
- **hero.js**: 2D yıldız alanı (parallax+twinkle) + nebula + portal girdabı + kayan yıldızlar.
  Yoğunluk kademeye göre (high=1, medium=0.7, low=0.4); off=çalışmaz.
- **planet.js**: procedural dokulu, küresel gölgeli 3D gezegen + halka + uydu (medium+ döner, low statik).
- **cosmic-bg.js**: site-geneli scroll parallax yıldız alanı ("uzayda yolculuk"), yalnız `high`.
- **app.js**: header scroll, Lenis (window.lenis), custom cursor, magnetik, tilt, scroll reveal (GSAP),
  scroll progress, spotlight, reveal-mask, faq, filtre, cookie consent, comet trail (medium+), warp (high),
  hiperuzay preloader, hero parallax.
- **interactions.js**: kinetik harf reveal, sayaçlar, pinned story (ScrollTrigger), sinematik slider,
  magnetik nav, ripple, imleç etiketleri, sayfa geçişleri, header autohide, yukarı-çık.

## Performans kademeleri (perf.js)
- Sinyaller: prefers-reduced-motion→off; saveData/2g→low; deviceMemory≤2||cpu≤2→low; ≤4→medium; else high.
  Dokunmatik → tavan medium. WebGL yoksa → high olamaz. Override: localStorage `punch_perf`.
- FPS gözcüsü: ilk ~2s ölç; <45fps ise bir kademe düş (`punch:downgrade` event'i; ağır canvaslar dinleyip kapanır).
- CSS: `html[data-perf="low"]` → blur/aurora/portal/nebula/comet kapalı; `="off"` → tüm animasyon durur.

## Kurallar
- Yalnız transform/opacity anime et; `will-change` ölçülü; sekme gizliyken canvas'ları durdur (visibilitychange).
- JS kapalı: `<noscript>` ile `[data-reveal]` görünür yapılır; içerik tam okunur (SEO + erişilebilirlik).
- WCAG AA kontrast, görünür focus, klavye gezinme, anlamlı alt, semantic HTML.
