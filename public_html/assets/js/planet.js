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

  function draw() {
    const W = canvas.clientWidth, H = canvas.clientHeight;
    const R = Math.min(W, H) * 0.4;
    const cx = W / 2, cy = H / 2;
    ctx.clearRect(0, 0, W, H);

    // atmosfer parlamasi
    const atm = ctx.createRadialGradient(cx, cy, R * 0.7, cx, cy, R * 1.35);
    atm.addColorStop(0, 'rgba(139,61,255,0.0)');
    atm.addColorStop(0.6, 'rgba(139,61,255,0.25)');
    atm.addColorStop(1, 'rgba(54,224,214,0.0)');
    ctx.fillStyle = atm;
    ctx.beginPath(); ctx.arc(cx, cy, R * 1.35, 0, Math.PI * 2); ctx.fill();

    // kure (clip) + donen doku
    ctx.save();
    ctx.beginPath(); ctx.arc(cx, cy, R, 0, Math.PI * 2); ctx.clip();
    const off = (rot % 512);
    ctx.drawImage(tex, cx - R - off, cy - R, R * 2, R * 2);
    ctx.drawImage(tex, cx - R - off + R * 2, cy - R, R * 2, R * 2);
    // kuresel golgeleme (isik sol-ust)
    const sh = ctx.createRadialGradient(cx - R * 0.35, cy - R * 0.35, R * 0.1, cx, cy, R * 1.05);
    sh.addColorStop(0, 'rgba(255,255,255,0.22)');
    sh.addColorStop(0.5, 'rgba(0,0,0,0)');
    sh.addColorStop(1, 'rgba(0,0,0,0.72)');
    ctx.fillStyle = sh; ctx.fillRect(cx - R, cy - R, R * 2, R * 2);
    ctx.restore();

    // ince kenar halkasi
    ctx.beginPath(); ctx.arc(cx, cy, R, 0, Math.PI * 2);
    ctx.strokeStyle = 'rgba(173,132,247,0.4)'; ctx.lineWidth = 1.5; ctx.stroke();

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
