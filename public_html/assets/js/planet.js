/* ============================================================
   PUNCH YAZILIM — 3D Donen Gezegen (canvas)
   Procedural yuzey dokusu + kuresel golgeleme (limb darkening) +
   atmosfer halkasi. Hafif; reduced-motion'da statik kalir.
   #planet-canvas uzerine cizer.
   ============================================================ */
(function () {
  'use strict';
  const canvas = document.getElementById('planet-canvas');
  if (!canvas) return;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const ctx = canvas.getContext('2d');
  let size, dpr, rot = 0, raf;

  // --- Yuzey dokusu (offscreen, bir kez uretilir) ---
  const tex = document.createElement('canvas');
  tex.width = 512; tex.height = 256;
  const tx = tex.getContext('2d');
  (function buildTexture() {
    // taban gradyan
    const g = tx.createLinearGradient(0, 0, 0, 256);
    g.addColorStop(0, '#3a1170'); g.addColorStop(0.5, '#7c3aed'); g.addColorStop(1, '#2a0b4d');
    tx.fillStyle = g; tx.fillRect(0, 0, 512, 256);
    // bantlar + lekeler
    for (let i = 0; i < 60; i++) {
      const y = Math.random() * 256;
      tx.fillStyle = `rgba(${[192, 38, 211, 0.12][0]},${38},${211},${Math.random() * 0.12})`;
      tx.fillRect(0, y, 512, Math.random() * 10 + 2);
    }
    for (let i = 0; i < 140; i++) {
      const x = Math.random() * 512, y = Math.random() * 256, r = Math.random() * 18 + 4;
      const c = Math.random() > 0.5 ? '54,224,214' : '255,95,209';
      const rg = tx.createRadialGradient(x, y, 0, x, y, r);
      rg.addColorStop(0, `rgba(${c},${Math.random() * 0.25})`);
      rg.addColorStop(1, `rgba(${c},0)`);
      tx.fillStyle = rg; tx.beginPath(); tx.arc(x, y, r, 0, Math.PI * 2); tx.fill();
    }
  })();

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    size = Math.min(canvas.clientWidth, canvas.clientHeight);
    canvas.width = canvas.clientWidth * dpr;
    canvas.height = canvas.clientHeight * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function drawRing(cx, cy, R, half) {
    const rx = R * 1.95, ry = R * 0.52, tilt = -0.32;
    ctx.save();
    ctx.translate(cx, cy); ctx.rotate(tilt);
    if (half) { ctx.beginPath(); ctx.rect(-rx * 1.2, 0, rx * 2.4, ry * 1.4); ctx.clip(); } // sadece on (alt) yari
    const g = ctx.createLinearGradient(-rx, 0, rx, 0);
    g.addColorStop(0, 'rgba(124,58,237,0)');
    g.addColorStop(0.3, 'rgba(173,132,247,0.55)');
    g.addColorStop(0.5, 'rgba(54,224,214,0.6)');
    g.addColorStop(0.7, 'rgba(255,95,209,0.5)');
    g.addColorStop(1, 'rgba(124,58,237,0)');
    ctx.strokeStyle = g; ctx.lineWidth = R * 0.12;
    ctx.beginPath(); ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2); ctx.stroke();
    ctx.lineWidth = R * 0.04; ctx.globalAlpha = 0.6;
    ctx.beginPath(); ctx.ellipse(0, 0, rx * 0.82, ry * 0.82, 0, 0, Math.PI * 2); ctx.stroke();
    ctx.restore();
  }

  function draw() {
    const W = canvas.clientWidth, H = canvas.clientHeight;
    const R = Math.min(W, H) * 0.32;
    const cx = W / 2, cy = H / 2;
    ctx.clearRect(0, 0, W, H);

    // atmosfer parlamasi
    const atm = ctx.createRadialGradient(cx, cy, R * 0.7, cx, cy, R * 1.5);
    atm.addColorStop(0, 'rgba(139,61,255,0.0)');
    atm.addColorStop(0.55, 'rgba(139,61,255,0.28)');
    atm.addColorStop(1, 'rgba(54,224,214,0.0)');
    ctx.fillStyle = atm;
    ctx.beginPath(); ctx.arc(cx, cy, R * 1.5, 0, Math.PI * 2); ctx.fill();

    // halka — arka yari (gezegenin arkasinda)
    drawRing(cx, cy, R, false);

    // uydu — gezegenin arkasindaysa once ciz
    const ma = rot * 0.02;
    const mx = cx + Math.cos(ma) * R * 2.2, my = cy + Math.sin(ma) * R * 0.8;
    const moonBehind = Math.sin(ma) < 0;
    const drawMoon = () => {
      const mr = R * 0.13;
      const mg = ctx.createRadialGradient(mx - mr * 0.3, my - mr * 0.3, 0, mx, my, mr);
      mg.addColorStop(0, '#fff'); mg.addColorStop(0.6, '#c9b8f0'); mg.addColorStop(1, '#2a1b45');
      ctx.fillStyle = mg; ctx.beginPath(); ctx.arc(mx, my, mr, 0, Math.PI * 2); ctx.fill();
    };
    if (moonBehind) drawMoon();

    // kure (clip) + donen doku
    ctx.save();
    ctx.beginPath(); ctx.arc(cx, cy, R, 0, Math.PI * 2); ctx.clip();
    const off = (rot % 512);
    ctx.drawImage(tex, cx - R - off, cy - R, R * 2, R * 2);
    ctx.drawImage(tex, cx - R - off + R * 2, cy - R, R * 2, R * 2);
    const sh = ctx.createRadialGradient(cx - R * 0.35, cy - R * 0.35, R * 0.1, cx, cy, R * 1.05);
    sh.addColorStop(0, 'rgba(255,255,255,0.22)');
    sh.addColorStop(0.5, 'rgba(0,0,0,0)');
    sh.addColorStop(1, 'rgba(0,0,0,0.72)');
    ctx.fillStyle = sh; ctx.fillRect(cx - R, cy - R, R * 2, R * 2);
    ctx.restore();

    // ince kenar halkasi
    ctx.beginPath(); ctx.arc(cx, cy, R, 0, Math.PI * 2);
    ctx.strokeStyle = 'rgba(173,132,247,0.45)'; ctx.lineWidth = 1.5; ctx.stroke();

    // halka — on yari (gezegenin onunde)
    drawRing(cx, cy, R, true);

    // uydu — onde ise
    if (!moonBehind) drawMoon();

    if (!reduce) rot += 0.4;
    raf = requestAnimationFrame(draw);
  }

  resize();
  draw();
  let rt;
  window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(resize, 200); });
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf); else draw();
  });
})();
