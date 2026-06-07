/* ============================================================
   PUNCH YAZILIM — Hero canvas (hafif partikul mesh)
   three.js/WebGL yerine 2D canvas: dusuk agirlik, genis uyumluluk.
   prefers-reduced-motion ve dusuk cihazda otomatik kapanir.
   ============================================================ */
(function () {
  'use strict';
  const canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  // Dusuk cekirdek sayili cihazlarda calistirma
  if ((navigator.hardwareConcurrency || 4) < 4) return;

  const ctx = canvas.getContext('2d');
  let w, h, dpr, particles, raf;
  const COUNT = window.innerWidth < 768 ? 36 : 70;
  const MAX_DIST = 150;

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth; h = canvas.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function init() {
    resize();
    particles = Array.from({ length: COUNT }, () => ({
      x: Math.random() * w, y: Math.random() * h,
      vx: (Math.random() - 0.5) * 0.4, vy: (Math.random() - 0.5) * 0.4,
      r: Math.random() * 1.8 + 0.6,
    }));
  }

  function step() {
    ctx.clearRect(0, 0, w, h);
    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(173, 132, 247, 0.55)';
      ctx.fill();
      for (let j = i + 1; j < particles.length; j++) {
        const q = particles[j];
        const dx = p.x - q.x, dy = p.y - q.y;
        const dist = Math.hypot(dx, dy);
        if (dist < MAX_DIST) {
          ctx.beginPath();
          ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y);
          ctx.strokeStyle = `rgba(124, 58, 237, ${0.16 * (1 - dist / MAX_DIST)})`;
          ctx.lineWidth = 1;
          ctx.stroke();
        }
      }
    }
    raf = requestAnimationFrame(step);
  }

  init();
  step();
  let rt;
  window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(init, 200); });
  // Sekme gizliyken duraklat
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf);
    else step();
  });
})();
