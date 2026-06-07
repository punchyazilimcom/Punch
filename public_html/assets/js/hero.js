/* ============================================================
   PUNCH YAZILIM — Kozmik Hero ("evren disi")
   Katmanlar:
     1) Nebula (suzulen renkli gaz bulutlari, additive)
     2) Derinlikli yildiz alani (parallax + twinkle)
     3) Boyutsal portal girdabi (merkeze cekilen parcaciklar)
     4) Kayan yildizlar (shooting stars)
   2D canvas; WebGL gerektirmez. Reduced-motion / dusuk cihazda kapanir.
   ============================================================ */
(function () {
  'use strict';
  const canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const lowPower = (navigator.hardwareConcurrency || 4) < 4;

  const ctx = canvas.getContext('2d');
  let w, h, dpr, stars, nebula, swirl, shooters, raf, t = 0;
  const mouse = { x: 0, y: 0, tx: 0, ty: 0 };
  const cfg = {
    stars: (window.innerWidth < 768 ? 140 : 320) * (lowPower ? 0.45 : 1) | 0,
    swirl: (window.innerWidth < 768 ? 90 : 220) * (lowPower ? 0.5 : 1) | 0,
  };
  const COL = ['223,231,255', '173,132,247', '54,224,214', '255,95,209'];

  function portal() { return { x: w * 0.58, y: h * 0.5 }; }
  function rand(a, b) { return a + Math.random() * (b - a); }

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth; h = canvas.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function init() {
    resize();
    stars = Array.from({ length: cfg.stars }, () => ({
      x: Math.random() * w, y: Math.random() * h,
      z: rand(0.25, 1), r: rand(0.4, 1.7),
      tw: Math.random() * Math.PI * 2, col: COL[(Math.random() * COL.length) | 0],
    }));
    nebula = [
      { x: 0.55, y: 0.45, r: 0.55, c: '124,58,237', a: 0 },
      { x: 0.7, y: 0.3, r: 0.4, c: '192,38,211', a: 2 },
      { x: 0.4, y: 0.65, r: 0.45, c: '54,224,214', a: 4 },
    ];
    const p = portal();
    swirl = Array.from({ length: cfg.swirl }, () => spawnSwirl(p));
    shooters = [];
  }

  function spawnSwirl(p) {
    const ang = Math.random() * Math.PI * 2;
    const rad = rand(40, Math.min(w, h) * 0.42);
    return {
      ang, rad, baseRad: rad,
      sp: rand(0.004, 0.012), pull: rand(0.05, 0.22),
      r: rand(0.5, 1.8), col: COL[(Math.random() * COL.length) | 0],
      a: rand(0.3, 0.9),
    };
  }

  function drawNebula() {
    ctx.globalCompositeOperation = 'lighter';
    for (const n of nebula) {
      const cx = (n.x + Math.cos(t * 0.0003 + n.a) * 0.03) * w;
      const cy = (n.y + Math.sin(t * 0.0004 + n.a) * 0.03) * h;
      const R = n.r * Math.min(w, h);
      const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, R);
      g.addColorStop(0, `rgba(${n.c},0.14)`);
      g.addColorStop(1, `rgba(${n.c},0)`);
      ctx.fillStyle = g;
      ctx.beginPath(); ctx.arc(cx, cy, R, 0, Math.PI * 2); ctx.fill();
    }
    ctx.globalCompositeOperation = 'source-over';
  }

  function drawStars() {
    const ox = (mouse.x - w / 2);
    const oy = (mouse.y - h / 2);
    for (const s of stars) {
      s.tw += 0.03;
      const px = s.x + ox * s.z * 0.04;
      const py = s.y + oy * s.z * 0.04;
      const a = 0.5 + Math.sin(s.tw) * 0.5;
      ctx.beginPath();
      ctx.arc(px, py, s.r * s.z, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${s.col},${0.25 + a * 0.6 * s.z})`;
      ctx.fill();
    }
  }

  function drawSwirl() {
    const p = portal();
    ctx.globalCompositeOperation = 'lighter';
    for (const s of swirl) {
      s.ang += s.sp * (1 + (1 - s.rad / s.baseRad));
      s.rad -= s.pull;
      if (s.rad < 6) Object.assign(s, spawnSwirl(p));
      const x = p.x + Math.cos(s.ang) * s.rad;
      const y = p.y + Math.sin(s.ang) * s.rad * 0.92;
      const fade = Math.min(1, s.rad / (s.baseRad));
      ctx.beginPath();
      ctx.arc(x, y, s.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${s.col},${s.a * (1 - fade) + 0.06})`;
      ctx.fill();
    }
    // olay ufku parlamasi
    const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, 90);
    g.addColorStop(0, 'rgba(173,132,247,0.30)');
    g.addColorStop(1, 'rgba(173,132,247,0)');
    ctx.fillStyle = g;
    ctx.beginPath(); ctx.arc(p.x, p.y, 90, 0, Math.PI * 2); ctx.fill();
    ctx.globalCompositeOperation = 'source-over';
  }

  function drawShooters() {
    if (Math.random() < 0.012 && shooters.length < 3) {
      shooters.push({ x: rand(0, w), y: rand(0, h * 0.5), vx: rand(6, 11), vy: rand(2, 4), life: 1 });
    }
    ctx.globalCompositeOperation = 'lighter';
    for (let i = shooters.length - 1; i >= 0; i--) {
      const s = shooters[i];
      s.x += s.vx; s.y += s.vy; s.life -= 0.012;
      if (s.life <= 0 || s.x > w || s.y > h) { shooters.splice(i, 1); continue; }
      const len = 90 * s.life;
      const g = ctx.createLinearGradient(s.x, s.y, s.x - s.vx * 9, s.y - s.vy * 9);
      g.addColorStop(0, `rgba(255,255,255,${0.9 * s.life})`);
      g.addColorStop(1, 'rgba(255,255,255,0)');
      ctx.strokeStyle = g; ctx.lineWidth = 2; ctx.lineCap = 'round';
      ctx.beginPath(); ctx.moveTo(s.x, s.y); ctx.lineTo(s.x - s.vx * (len / 9), s.y - s.vy * (len / 9)); ctx.stroke();
    }
    ctx.globalCompositeOperation = 'source-over';
  }

  function frame() {
    t += 16;
    mouse.x += (mouse.tx - mouse.x) * 0.06;
    mouse.y += (mouse.ty - mouse.y) * 0.06;
    ctx.clearRect(0, 0, w, h);
    drawNebula();
    drawStars();
    drawSwirl();
    drawShooters();
    raf = requestAnimationFrame(frame);
  }

  init();
  mouse.x = mouse.tx = w / 2; mouse.y = mouse.ty = h / 2;
  frame();

  window.addEventListener('mousemove', (e) => {
    const r = canvas.getBoundingClientRect();
    mouse.tx = e.clientX - r.left; mouse.ty = e.clientY - r.top;
  }, { passive: true });
  let rt;
  window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(init, 200); });
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf); else frame();
  });
})();
