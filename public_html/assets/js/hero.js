/* ============================================================
   PUNCH YAZILIM — Hero canvas (ust segment)
   Katman 1: akiskan gradient bloblar (additive glow / aurora)
   Katman 2: imlece tepki veren partikul takimyildizi + baglanti cizgileri
   2D canvas; dusuk agirlik, genis uyumluluk. WebGL gerektirmez.
   prefers-reduced-motion ve dusuk cihazda otomatik sadelesir/kapanir.
   ============================================================ */
(function () {
  'use strict';
  const canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  const lowPower = (navigator.hardwareConcurrency || 4) < 4;

  const ctx = canvas.getContext('2d');
  let w, h, dpr, particles, blobs, raf;
  const mouse = { x: -9999, y: -9999, active: false };

  const COUNT = (window.innerWidth < 768 ? 28 : 64) * (lowPower ? 0.5 : 1) | 0;
  const MAX_DIST = 150;

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth; h = canvas.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function rand(a, b) { return a + Math.random() * (b - a); }

  function init() {
    resize();
    particles = Array.from({ length: COUNT }, () => ({
      x: Math.random() * w, y: Math.random() * h,
      vx: rand(-0.35, 0.35), vy: rand(-0.35, 0.35),
      r: rand(0.6, 2.2),
    }));
    // akiskan bloblar (aurora)
    const palette = [[124, 58, 237], [192, 38, 211], [139, 61, 255]];
    blobs = palette.map((c, i) => ({
      x: rand(0.2, 0.8) * w, y: rand(0.2, 0.8) * h,
      r: rand(0.28, 0.45) * Math.min(w, h),
      a: rand(0, Math.PI * 2), sp: rand(0.0006, 0.0014) * (i % 2 ? 1 : -1),
      col: c, orbit: rand(0.08, 0.16) * Math.min(w, h),
    }));
  }

  function drawBlobs(t) {
    ctx.globalCompositeOperation = 'lighter';
    for (const b of blobs) {
      b.a += b.sp;
      const cx = b.x + Math.cos(b.a) * b.orbit;
      const cy = b.y + Math.sin(b.a * 1.2) * b.orbit;
      const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, b.r);
      const [r, gr, bl] = b.col;
      g.addColorStop(0, `rgba(${r},${gr},${bl},0.16)`);
      g.addColorStop(1, `rgba(${r},${gr},${bl},0)`);
      ctx.fillStyle = g;
      ctx.beginPath(); ctx.arc(cx, cy, b.r, 0, Math.PI * 2); ctx.fill();
    }
    ctx.globalCompositeOperation = 'source-over';
  }

  function step(t) {
    ctx.clearRect(0, 0, w, h);
    drawBlobs(t);

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];
      // imlec etkilesimi (yumusak itme)
      if (mouse.active) {
        const dx = p.x - mouse.x, dy = p.y - mouse.y;
        const d2 = dx * dx + dy * dy;
        if (d2 < 16000) {
          const f = (16000 - d2) / 16000 * 0.9;
          p.vx += (dx / Math.sqrt(d2 + 0.01)) * f * 0.25;
          p.vy += (dy / Math.sqrt(d2 + 0.01)) * f * 0.25;
        }
      }
      p.vx *= 0.98; p.vy *= 0.98;
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
      p.x = Math.max(0, Math.min(w, p.x));
      p.y = Math.max(0, Math.min(h, p.y));

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(196, 168, 255, 0.7)';
      ctx.shadowColor = 'rgba(139,61,255,0.8)';
      ctx.shadowBlur = 6;
      ctx.fill();
      ctx.shadowBlur = 0;

      for (let j = i + 1; j < particles.length; j++) {
        const q = particles[j];
        const dx = p.x - q.x, dy = p.y - q.y;
        const dist = Math.hypot(dx, dy);
        if (dist < MAX_DIST) {
          ctx.beginPath();
          ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y);
          ctx.strokeStyle = `rgba(124, 58, 237, ${0.18 * (1 - dist / MAX_DIST)})`;
          ctx.lineWidth = 1;
          ctx.stroke();
        }
      }
    }
    raf = requestAnimationFrame(step);
  }

  function onMove(e) {
    const r = canvas.getBoundingClientRect();
    mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top; mouse.active = true;
  }
  function onLeave() { mouse.active = false; mouse.x = mouse.y = -9999; }

  init();
  step(0);
  window.addEventListener('mousemove', onMove, { passive: true });
  window.addEventListener('mouseout', onLeave);
  let rt;
  window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(init, 200); });
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf); else step(0);
  });
})();
