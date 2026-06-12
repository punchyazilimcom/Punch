/* ============================================================
   PUNCH YAZILIM — Ust seviye etkilesim motoru (interactions.js)
   Pinned storytelling, sinematik yorum slider, kinetik harf reveal,
   sayi sayaclari, miknatisli nav, buton sok dalgasi, imlec durumlari,
   sayfa gecisleri, header otomatik gizle/goster, yukari-cik dugmesi.
   Tum moduller performans kademesine ve reduced-motion'a saygi duyar.
   ============================================================ */
(function () {
  'use strict';
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var P = window.PunchPerf;
  var lvl = P ? P.level : 3;
  var gsap = window.gsap, ST = window.ScrollTrigger;
  var ready = function (fn) { document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn); };

  /* ---------- 1) Kinetik harf-harf baslik reveal ---------- */
  function initCharReveal() {
    var titles = document.querySelectorAll('[data-chars]');
    if (!titles.length) return;
    titles.forEach(function (el) {
      if (el.dataset.split === '1') return; el.dataset.split = '1';
      var html = '';
      el.childNodes.forEach(function (node) {
        if (node.nodeType === 3) {
          node.textContent.split('').forEach(function (ch) {
            html += ch === ' ' ? ' ' : '<span class="char">' + ch + '</span>';
          });
        } else { html += node.outerHTML || ''; }
      });
      el.innerHTML = html;
    });
    if (reduce || !gsap || lvl < 2) return;
    titles.forEach(function (el) {
      gsap.from(el.querySelectorAll('.char'), {
        yPercent: 120, opacity: 0, rotateX: -40, duration: 0.7, ease: 'back.out(1.7)', stagger: 0.018,
        scrollTrigger: { trigger: el, start: 'top 85%', once: true }
      });
    });
  }

  /* ---------- 2) Sayi sayaclari ---------- */
  function initCounters() {
    document.querySelectorAll('[data-countup]').forEach(function (el) {
      var raw = el.getAttribute('data-countup');
      var m = raw.match(/\d[\d.,]*/);              // ilk sayi blogu
      if (!m) { el.textContent = raw; return; }
      var target = parseFloat(m[0].replace(/,/g, '')) || 0;
      var prefix = raw.slice(0, m.index);          // sayidan onceki kisim (% gibi)
      var suffix = raw.slice(m.index + m[0].length); // sayidan sonraki kisim (+ /7 gibi)
      if (reduce || !gsap) { el.textContent = raw; return; }
      var obj = { v: 0 };
      gsap.to(obj, {
        v: target, duration: 1.8, ease: 'power2.out',
        scrollTrigger: { trigger: el, start: 'top 90%', once: true },
        onUpdate: function () {
          var val = target % 1 === 0 ? Math.round(obj.v) : obj.v.toFixed(0);
          el.textContent = prefix + val + suffix;
        }
      });
    });
  }

  /* ---------- 3) Pinned "nasil calisir" storytelling ---------- */
  function initStory() {
    var story = document.querySelector('[data-story]');
    if (!story) return;
    var steps = story.querySelectorAll('[data-story-step]');
    var bigNum = story.querySelector('[data-story-num]');
    var bigTitle = story.querySelector('[data-story-title]');
    var fill = story.querySelector('[data-story-fill]');
    if (reduce || !gsap || !ST || lvl < 2) {
      steps.forEach(function (s) { s.classList.add('active'); });
      return;
    }
    var sticky = story.querySelector('[data-story-sticky]');
    ST.create({
      trigger: story, start: 'top top', end: 'bottom bottom',
      onUpdate: function (self) {
        var i = Math.min(steps.length - 1, Math.floor(self.progress * steps.length));
        steps.forEach(function (s, k) { s.classList.toggle('active', k === i); });
        var d = steps[i];
        if (bigNum) bigNum.textContent = ('0' + (i + 1)).slice(-2);
        if (bigTitle) bigTitle.textContent = d.getAttribute('data-step-title') || '';
        if (fill) fill.style.height = (self.progress * 100) + '%';
      }
    });
  }

  /* ---------- 4) Sinematik yorum slider ---------- */
  function initSlider() {
    var slider = document.querySelector('[data-slider]');
    if (!slider) return;
    var track = slider.querySelector('[data-slider-track]');
    var slides = track ? track.children : [];
    if (slides.length < 2) return;
    var i = 0, timer, dotsWrap = slider.querySelector('[data-slider-dots]');
    var dots = [];
    if (dotsWrap) {
      for (var d = 0; d < slides.length; d++) {
        var b = document.createElement('button');
        b.className = 'slider-dot'; b.setAttribute('aria-label', 'Yorum ' + (d + 1));
        (function (idx) { b.addEventListener('click', function () { go(idx); rearm(); }); })(d);
        dotsWrap.appendChild(b); dots.push(b);
      }
    }
    function go(n) {
      i = (n + slides.length) % slides.length;
      track.style.transform = 'translateX(' + (-i * 100) + '%)';
      for (var k = 0; k < slides.length; k++) slides[k].classList.toggle('is-active', k === i);
      dots.forEach(function (dd, k) { dd.classList.toggle('active', k === i); });
    }
    function rearm() { clearInterval(timer); if (!reduce) timer = setInterval(function () { go(i + 1); }, 5000); }
    slider.querySelectorAll('[data-slider-prev]').forEach(function (el) { el.addEventListener('click', function () { go(i - 1); rearm(); }); });
    slider.querySelectorAll('[data-slider-next]').forEach(function (el) { el.addEventListener('click', function () { go(i + 1); rearm(); }); });
    // suruklenebilir
    var sx = 0, dx = 0, drag = false;
    track.addEventListener('pointerdown', function (e) { drag = true; sx = e.clientX; track.style.transition = 'none'; });
    window.addEventListener('pointermove', function (e) { if (!drag) return; dx = e.clientX - sx; track.style.transform = 'translateX(calc(' + (-i * 100) + '% + ' + dx + 'px))'; });
    window.addEventListener('pointerup', function () { if (!drag) return; drag = false; track.style.transition = ''; if (Math.abs(dx) > 60) go(i + (dx < 0 ? 1 : -1)); else go(i); dx = 0; rearm(); });
    slider.addEventListener('mouseenter', function () { clearInterval(timer); });
    slider.addEventListener('mouseleave', rearm);
    go(0); rearm();
  }

  /* ---------- 5) Miknatisli ogeler (gelismis) ---------- */
  function initMagneticPlus() {
    if (reduce || (matchMedia('(pointer: coarse)').matches)) return;
    document.querySelectorAll('.nav a').forEach(function (el) {
      if (el.dataset.mag === '1') return; el.dataset.mag = '1';
      var s = parseFloat(el.dataset.magnetic) || 0.4;
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect();
        el.style.transform = 'translate(' + (e.clientX - r.left - r.width / 2) * s + 'px,' + (e.clientY - r.top - r.height / 2) * s + 'px)';
      });
      el.addEventListener('mouseleave', function () { el.style.transform = ''; });
    });
  }

  /* ---------- 6) Buton sok dalgasi (ripple) ---------- */
  function initRipple() {
    document.addEventListener('pointerdown', function (e) {
      var btn = e.target.closest('.btn');
      if (!btn || reduce) return;
      var r = btn.getBoundingClientRect();
      var rip = document.createElement('span');
      rip.className = 'ripple';
      var size = Math.max(r.width, r.height);
      rip.style.width = rip.style.height = size + 'px';
      rip.style.left = (e.clientX - r.left - size / 2) + 'px';
      rip.style.top = (e.clientY - r.top - size / 2) + 'px';
      btn.appendChild(rip);
      setTimeout(function () { rip.remove(); }, 650);
    });
  }

  /* ---------- 7) Imlec durumlari (etiketli) ---------- */
  function initCursorStates() {
    if (reduce || matchMedia('(pointer: coarse)').matches || lvl < 2) return;
    var ring = document.querySelector('.cursor-ring');
    if (!ring) return;
    var label = document.createElement('span'); label.className = 'cursor-label'; ring.appendChild(label);
    document.addEventListener('mouseover', function (e) {
      var t = e.target.closest('[data-cursor]');
      if (t) { ring.classList.add('labeled'); label.textContent = t.getAttribute('data-cursor'); }
    });
    document.addEventListener('mouseout', function (e) {
      if (e.target.closest('[data-cursor]')) { ring.classList.remove('labeled'); label.textContent = ''; }
    });
  }

  /* ---------- 8) Yumusak sayfa gecisleri ---------- */
  function initPageTransition() {
    if (reduce) return;
    document.body.classList.add('page-enter');
    requestAnimationFrame(function () { requestAnimationFrame(function () { document.body.classList.remove('page-enter'); }); });
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (!a) return;
      var href = a.getAttribute('href') || '';
      if (a.target === '_blank' || a.hasAttribute('download') || e.metaKey || e.ctrlKey || e.shiftKey) return;
      if (!href || href[0] === '#' || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0 || href.indexOf('wa.me') > -1) return;
      if (a.hostname && a.hostname !== location.hostname) return;
      e.preventDefault();
      document.body.classList.add('page-leave');
      setTimeout(function () { location.href = a.href; }, 230);
    });
    window.addEventListener('pageshow', function (ev) { if (ev.persisted) document.body.classList.remove('page-leave', 'page-enter'); });
  }

  /* ---------- 9) Header otomatik gizle/goster ---------- */
  function initHeaderAutohide() {
    var header = document.querySelector('.site-header');
    if (!header) return;
    var last = 0;
    addEventListener('scroll', function () {
      var y = scrollY;
      if (y > 300 && y > last) header.classList.add('hide');
      else header.classList.remove('hide');
      last = y;
    }, { passive: true });
  }

  /* ---------- 10) Kozmik "yukari cik" dugmesi ---------- */
  function initToTop() {
    var btn = document.createElement('button');
    btn.className = 'to-top'; btn.setAttribute('aria-label', 'Yukari cik');
    btn.innerHTML = '<svg class="to-top-ring" viewBox="0 0 44 44"><circle cx="22" cy="22" r="20"/></svg><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>';
    document.body.appendChild(btn);
    var circ = btn.querySelector('.to-top-ring circle');
    var len = 2 * Math.PI * 20; if (circ) { circ.style.strokeDasharray = len; circ.style.strokeDashoffset = len; }
    addEventListener('scroll', function () {
      var h = document.documentElement.scrollHeight - innerHeight;
      var p = h > 0 ? scrollY / h : 0;
      btn.classList.toggle('show', scrollY > 600);
      if (circ) circ.style.strokeDashoffset = len * (1 - p);
    }, { passive: true });
    btn.addEventListener('click', function () {
      if (window.lenis) window.lenis.scrollTo(0); else scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  ready(function () {
    initCharReveal();
    initCounters();
    initStory();
    initSlider();
    initMagneticPlus();
    initRipple();
    initCursorStates();
    initPageTransition();
    initHeaderAutohide();
    initToTop();
  });
})();
