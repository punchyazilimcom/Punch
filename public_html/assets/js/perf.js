/* ============================================================
   PUNCH YAZILIM — Uyarlanabilir Performans Motoru (perf.js)
   Ziyaretcinin cihazina/baglantisina gore gorsel efekt kademesini
   OTOMATIK belirler: high | medium | low | off
   + Calisirken FPS dususe otomatik kademe dusurur (asla yukseltmez).
   + Musteri elle secebilir (footer secici, localStorage'da saklanir).
   Diger kozmik scriptler window.PunchPerf.level degerini okur.
   ============================================================ */
(function () {
  'use strict';
  var LEVELS = { off: 0, low: 1, medium: 2, high: 3 };
  var NAMES = ['off', 'low', 'medium', 'high'];

  function hasWebGL() {
    try {
      var c = document.createElement('canvas');
      return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
    } catch (e) { return false; }
  }

  function detect() {
    var mm = window.matchMedia;
    if (mm && mm('(prefers-reduced-motion: reduce)').matches) return 0; // off
    var conn = navigator.connection || {};
    if (conn.saveData) return 1; // low (veri tasarrufu)
    if (conn.effectiveType && /(^|-)2g$/.test(conn.effectiveType)) return 1;

    var mem = navigator.deviceMemory || 4;      // GB (yoksa 4 varsay)
    var cpu = navigator.hardwareConcurrency || 4;
    var coarse = mm && mm('(pointer: coarse)').matches;

    var score = 3;
    if (mem <= 2 || cpu <= 2) score = 1;
    else if (mem <= 4 || cpu <= 4) score = 2;
    else score = 3;

    // Dokunmatik/mobil cihazlarda WebGL nebula yukunu sinirlamak icin tavan = medium
    if (coarse) score = Math.min(score, 2);
    // WebGL yoksa high olamaz
    if (!hasWebGL()) score = Math.min(score, 2);
    return score;
  }

  function readOverride() {
    try { return localStorage.getItem('punch_perf') || 'auto'; } catch (e) { return 'auto'; }
  }

  var override = readOverride();
  var level = (override !== 'auto' && override in LEVELS) ? LEVELS[override] : detect();

  var api = {
    level: level,
    auto: override === 'auto',
    name: function () { return NAMES[api.level]; },
    atLeast: function (n) { return api.level >= (typeof n === 'string' ? LEVELS[n] : n); },
    set: function (val) { // 'auto'|'high'|'medium'|'low'|'off'
      try { localStorage.setItem('punch_perf', val); } catch (e) {}
      location.reload();
    },
    _downgrade: function () {
      if (api.level > 1) {
        api.level--;
        document.documentElement.setAttribute('data-perf', api.name());
        document.dispatchEvent(new CustomEvent('punch:downgrade', { detail: { level: api.level, name: api.name() } }));
      }
    }
  };
  window.PunchPerf = api;
  document.documentElement.setAttribute('data-perf', api.name());

  // --- Calisma zamani FPS gozcusu (yalniz otomatik moddayken, sadece dusurur) ---
  if (api.auto && api.level > 1 && !(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) {
    var frames = 0, t0 = performance.now(), checks = 0;
    function probe(now) {
      frames++;
      var dt = now - t0;
      if (dt >= 1200) {
        var fps = (frames * 1000) / dt;
        if (fps < 45) { api._downgrade(); }
        frames = 0; t0 = now; checks++;
        if (checks >= 2 || api.level <= 1) return; // 2 pencere yeterli
      }
      requestAnimationFrame(probe);
    }
    // ilk boyamadan sonra basla
    setTimeout(function () { requestAnimationFrame(probe); }, 800);
  }
})();
