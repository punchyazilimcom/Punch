# Grafik & Efekt Arsenali + Tema Ön Ayarları

site-forge yalnız "kozmik" temayla veya 5 yetenekle sınırlı değildir. Aşağıdaki teknikleri
projeye/markaya göre **seç ve birleştir**. Her teknik için: 60fps hedefi, `prefers-reduced-motion`
desteği, WebGL/ağır efekt yoksa fallback, ve performans kademesine (perf.js: high/medium/low/off)
bağlanma ZORUNLUDUR.

## A) WebGL / 3D katmanı
- **Three.js** — 3D sahne, GLTF/GLB model, ışık, kamera, OrbitControls, instancing.
- **OGL / raw WebGL** — hafif tam-ekran shader (FBM nebula, fluid, plasma, voronoi, dithering).
- **Fragment shader efektleri**: domain warp, gravitasyonel lensing (kara delik), iridescence,
  displacement (hover/scroll ile mesh bozma), gerçek zamanlı gürültü (Perlin/Simplex/FBM).
- **Post-processing**: bloom, chromatic aberration, film grain, vignette, depth of field
  (Three.js EffectComposer / postprocessing).
- **Mesh teknikleri**: GPGPU particle (binlerce parçacık), morph targets, ribbon/trail,
  reaction-diffusion, metaball/marching cubes.
- **Görsele shader**: hover'da görsel sıvılaşma (fluid distortion), RGB shift, pixelate/ASCII.
- **3D ürün/portföy**: GLTF model döndürme, konfigüratör, AR (model-viewer/USDZ).

## B) 2D Canvas
- Parçacık takımyıldızı (parallax + bağlantı çizgileri), kayan yıldız, fluid metaball,
  generative noise field, sayaç/grafik (mini bar/line), procedural gezegen/küre, warp tunnel.

## C) Hareket (DOM)
- **GSAP 3** + **ScrollTrigger** (reveal, parallax, pin, scrub, batch), **Flip** (layout geçişleri),
  **SplitText/manuel char split** (kinetik tipografi), **MotionPath**.
- **Lenis** (yumuşak scroll). **Splitting.js**. **Lottie** (vektör animasyon). **Rive** (interaktif).
- **Native**: View Transitions API, scroll-driven animations (CSS `animation-timeline`), `@property`,
  `:has()`, container queries, `clip-path`/mask reveal, conic-gradient, backdrop-filter.
- Mikro-etkileşim: magnetik buton/nav, ripple, custom cursor (durum etiketli), 3D tilt, spotlight,
  marquee, header autohide, ilerleme halkalı yukarı-çık, sayfa geçiş fade.

## D) Atmosfer / doku
- Film grain/noise overlay, gradient mesh, aurora blob, ışık sızıntısı, holografik gradient,
  ince grid çizgileri, glassmorphism (blur + 1px gradient border + iç highlight).

## E) Ses/erişim (opsiyonel)
- İsteğe bağlı UI sesleri (varsayılan KAPALI, kullanıcı açar). Tümünde WCAG AA, görünür focus, klavye.

---

## Tema ön ayarları (preset)
Marka/sektöre göre birini seç ya da harmanla:

| Preset | His | Çekirdek teknikler |
|---|---|---|
| **cosmic** | uzay, derin mor/siyah | shader nebula + kara delik, yıldız alanı, portal, 3D gezegen, warp |
| **liquid** | akışkan, organik | fluid sim shader, metaball, görsel sıvılaşma, gradient mesh |
| **glass** | premium, kurumsal sakin | glassmorphism, yumuşak gradient, hafif parallax, ince mikro-etkileşim |
| **brutalist** | cesur, editorial | büyük tipografi, sert grid, marquee, hızlı kesme geçişler, yüksek kontrast |
| **particle** | teknolojik | GPGPU/2D particle field, bağlantı çizgileri, imleç etkileşimi |
| **3D-product** | e-ticaret/ürün | GLTF model döndürme/konfigüratör, stüdyo ışık, AR |
| **generative** | sanatsal | shader/SVG generative art, her ziyarette farklı kompozisyon |
| **editorial-kinetik** | dergi/marka | kinetik tipografi (SplitText), pinned storytelling, Flip geçişleri |
| **retro-CRT** | nostaljik | tarama çizgisi/CRT shader, dithering, neon |

Her preset için yine: token tabanlı CSS, perf.js kademeleri, reduced-motion + fallback.

---

## Stack seçenekleri (hedefe göre)
- **PHP 8.1+/MySQL (varsayılan)** — panel + admin + ödeme + Hostinger paylaşımlı hosting gereken
  her durumda. Server-rendered + progressive enhancement. (Punch referans uygulaması bu.)
- **Vite + Three.js + GSAP (statik)** — yalnız vitrin/landing/portfolyo, backend gerekmiyorsa.
  Build çıktısı CDN/Netlify/Hostinger static. SEO için prerender/SSG.
- **Next.js (React Three Fiber + drei)** — uygulama benzeri, dinamik içerik + 3D ağırlıklı isteniyorsa.
- **Astro** — içerik ağırlıklı + adacık (island) interaktivite; mükemmel CWV.
Stack ne olursa olsun ilkeler sabit: uyarlanabilir performans, reduced-motion, fallback, üst düzey SEO,
güvenlik (CSP nonce vb.), erişilebilirlik.

## Performans bütçesi (her stack)
- Hero hariç toplam JS (gzip) hedefi < 150KB; ağır 3D/shader lazy-init ve yalnız uygun kademede.
- WebGL canvas %50–70 çözünürlükte render + DPR cap; sekme gizliyken dur; düşük cihazda kapan.
- Lighthouse mobil: Performance ≥90 (vitrinde 3D varsa ≥80 kabul edilebilir), SEO=100, A11y ≥95.
