/* ============================================================
   PUNCH YAZILIM — Site geneli kozmik arka plan (cosmic-bg.js)
   Scroll ile derinlikte ilerleyen cok katmanli yildiz alani:
   "uzayda yolculuk" hissi. Yalniz YUKSEK kademede calisir.
   data-perf dususunde kendini kapatir.
   ============================================================ */
(function () {
  'use strict';
  var P = window.PunchPerf;
  if (P && !P.atLeast('high')) return;
  if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var canvas = document.getElementById('cosmic-bg');
  if (!canvas) return;
  var ctx = canvas.getContext('2d');
  var w, h, dpr, layers, raf, running = true;
  var COL = ['223,231,255', '173,132,247', '54,224,214', '255,95,209'];

  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = innerWidth; h = innerHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function build() {
    resize();
    // 3 derinlik katmani: uzak (yavas) -> yakin (hizli)
    layers = [
      { n: 60, depth: 0.15, size: 0.8 },
      { n: 45, depth: 0.4, size: 1.2 },
      { n: 30, depth: 0.8, size: 1.8 },
    ].map(function (L) {
      L.stars = Array.from({ length: L.n }, function () {
        return { x: Math.random() * w, y: Math.random() * h, r: Math.random() * L.size + 0.3, c: COL[(Math.random() * COL.length) | 0], tw: Math.random() * 6.28 };
      });
      return L;
    });
  }

  function frame() {
    if (!running) return;
    var sc = window.scrollY || 0;
    ctx.clearRect(0, 0, w, h);
    for (var i = 0; i < layers.length; i++) {
      var L = layers[i];
      for (var j = 0; j < L.stars.length; j++) {
        var s = L.stars[j];
        // scroll'a gore dikey kayma (mod ile sarmal)
        var y = (s.y - sc * L.depth) % h;
        if (y < 0) y += h;
        s.tw += 0.02;
        var a = 0.4 + Math.sin(s.tw) * 0.4;
        ctx.beginPath();
        ctx.arc(s.x, y, s.r, 0, 6.2832);
        ctx.fillStyle = 'rgba(' + s.c + ',' + (a * (0.3 + L.depth * 0.6)).toFixed(3) + ')';
        ctx.fill();
      }
    }
    raf = requestAnimationFrame(frame);
  }

  build();
  frame();
  var rt;
  window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(build, 200); });
  document.addEventListener('visibilitychange', function () {
    if (document.hidden) { running = false; cancelAnimationFrame(raf); }
    else if (P && P.atLeast('high')) { running = true; frame(); }
  });
  // Kademe dususunde kapan
  document.addEventListener('punch:downgrade', function () {
    if (window.PunchPerf && !window.PunchPerf.atLeast('high')) {
      running = false; cancelAnimationFrame(raf); ctx.clearRect(0, 0, w, h);
      canvas.style.display = 'none';
    }
  });
})();
