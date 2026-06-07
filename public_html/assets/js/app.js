/* ============================================================
   PUNCH YAZILIM — Etkilesim katmani (progressive enhancement)
   GSAP + ScrollTrigger + Lenis CDN'den yuklenir (varsa).
   Tum animasyonlar prefers-reduced-motion'a saygi duyar.
   ============================================================ */
(function () {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const hasGsap = typeof window.gsap !== 'undefined';
  const ready = (fn) => (document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn));

  /* -------- Header scroll durumu -------- */
  function initHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 20);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const toggle = document.querySelector('.nav-toggle');
    if (toggle) {
      toggle.addEventListener('click', () => {
        const open = document.body.classList.toggle('nav-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      document.querySelectorAll('.nav a').forEach((a) =>
        a.addEventListener('click', () => document.body.classList.remove('nav-open'))
      );
    }
  }

  /* -------- Lenis yumusak scroll -------- */
  function initLenis() {
    if (reduceMotion || typeof window.Lenis === 'undefined') return null;
    const lenis = new window.Lenis({ lerp: 0.1, smoothWheel: true });
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);
    if (hasGsap && window.ScrollTrigger) {
      lenis.on('scroll', window.ScrollTrigger.update);
    }
    // Ankra linkleri
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
      a.addEventListener('click', (e) => {
        const id = a.getAttribute('href');
        if (id.length > 1) {
          const el = document.querySelector(id);
          if (el) { e.preventDefault(); lenis.scrollTo(el, { offset: -90 }); }
        }
      });
    });
    return lenis;
  }

  /* -------- Custom cursor -------- */
  function initCursor() {
    if (window.matchMedia('(pointer: coarse)').matches) return;
    const dot = document.createElement('div');
    const ring = document.createElement('div');
    dot.className = 'cursor-dot';
    ring.className = 'cursor-ring';
    document.body.append(dot, ring);

    let mx = innerWidth / 2, my = innerHeight / 2, rx = mx, ry = my;
    addEventListener('mousemove', (e) => { mx = e.clientX; my = e.clientY; dot.style.transform = `translate(${mx - 3}px, ${my - 3}px)`; });
    (function loop() {
      rx += (mx - rx) * 0.18; ry += (my - ry) * 0.18;
      ring.style.transform = `translate(${rx - 19}px, ${ry - 19}px)`;
      requestAnimationFrame(loop);
    })();

    const hoverables = 'a, button, .card, .work-card, .filter-chip, [data-magnetic]';
    document.addEventListener('mouseover', (e) => { if (e.target.closest(hoverables)) ring.classList.add('hovering'); });
    document.addEventListener('mouseout', (e) => { if (e.target.closest(hoverables)) ring.classList.remove('hovering'); });
  }

  /* -------- Magnetik butonlar -------- */
  function initMagnetic() {
    if (reduceMotion) return;
    document.querySelectorAll('[data-magnetic]').forEach((el) => {
      const strength = parseFloat(el.dataset.magnetic) || 0.3;
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        const x = (e.clientX - r.left - r.width / 2) * strength;
        const y = (e.clientY - r.top - r.height / 2) * strength;
        el.style.transform = `translate(${x}px, ${y}px)`;
      });
      el.addEventListener('mouseleave', () => { el.style.transform = ''; });
    });
  }

  /* -------- 3D tilt kartlar -------- */
  function initTilt() {
    if (reduceMotion) return;
    document.querySelectorAll('[data-tilt]').forEach((el) => {
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - 0.5;
        const py = (e.clientY - r.top) / r.height - 0.5;
        el.style.transform = `perspective(800px) rotateY(${px * 8}deg) rotateX(${-py * 8}deg)`;
      });
      el.addEventListener('mouseleave', () => { el.style.transform = ''; });
    });
  }

  /* -------- Scroll reveal + parallax + hero -------- */
  function initScrollAnimations() {
    const revealEls = document.querySelectorAll('[data-reveal]');
    if (reduceMotion || !hasGsap) {
      revealEls.forEach((el) => el.classList.add('is-revealed'));
      return;
    }
    const { gsap } = window;
    if (window.ScrollTrigger) gsap.registerPlugin(window.ScrollTrigger);

    // Hero kinetik baslik
    const heroWords = document.querySelectorAll('.hero [data-split] .reveal-word > span');
    if (heroWords.length) {
      gsap.from(heroWords, { yPercent: 110, opacity: 0, duration: 1, ease: 'power4.out', stagger: 0.06, delay: 0.15 });
    }
    gsap.from('.hero [data-hero-fade]', { y: 24, opacity: 0, duration: 0.9, ease: 'power3.out', stagger: 0.12, delay: 0.5 });

    // Genel reveal
    revealEls.forEach((el) => {
      gsap.to(el, {
        opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 86%', once: true },
      });
    });

    // Parallax
    document.querySelectorAll('[data-parallax]').forEach((el) => {
      const amount = parseFloat(el.dataset.parallax) || 60;
      gsap.to(el, { yPercent: -amount, ease: 'none', scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true } });
    });

    // Sayac animasyonu
    document.querySelectorAll('[data-count]').forEach((el) => {
      const target = parseFloat(el.dataset.count);
      const suffix = el.dataset.suffix || '';
      const prefix = el.dataset.prefix || '';
      const obj = { v: 0 };
      gsap.to(obj, {
        v: target, duration: 1.6, ease: 'power2.out',
        scrollTrigger: { trigger: el, start: 'top 90%', once: true },
        onUpdate: () => { el.textContent = prefix + Math.round(obj.v) + suffix; },
      });
    });
  }

  /* -------- SSS akordeon -------- */
  function initFaq() {
    document.querySelectorAll('.faq-item').forEach((item) => {
      const q = item.querySelector('.faq-q');
      const a = item.querySelector('.faq-a');
      if (!q || !a) return;
      q.addEventListener('click', () => {
        const open = item.classList.toggle('open');
        q.setAttribute('aria-expanded', open ? 'true' : 'false');
        a.style.maxHeight = open ? a.scrollHeight + 'px' : '0';
      });
    });
  }

  /* -------- Portfolyo filtre -------- */
  function initFilter() {
    const chips = document.querySelectorAll('.filter-chip');
    if (!chips.length) return;
    const cards = document.querySelectorAll('[data-cat]');
    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        chips.forEach((c) => c.classList.remove('active'));
        chip.classList.add('active');
        const f = chip.dataset.filter;
        cards.forEach((card) => {
          const show = f === 'all' || card.dataset.cat === f;
          card.style.display = show ? '' : 'none';
        });
      });
    });
  }

  /* -------- Cerez onayi -------- */
  function initCookies() {
    const banner = document.querySelector('.cookie-banner');
    if (!banner) return;
    if (!localStorage.getItem('punch_cookie_consent')) {
      setTimeout(() => banner.classList.add('show'), 1200);
    }
    banner.querySelectorAll('[data-cookie]').forEach((btn) => {
      btn.addEventListener('click', () => {
        localStorage.setItem('punch_cookie_consent', btn.dataset.cookie);
        banner.classList.remove('show');
        if (btn.dataset.cookie === 'accept') document.dispatchEvent(new Event('punch:consent'));
      });
    });
  }

  /* -------- Form gonderiminde cift tiklama korumasi -------- */
  function initForms() {
    document.querySelectorAll('form[data-guard]').forEach((form) => {
      form.addEventListener('submit', () => {
        const btn = form.querySelector('[type="submit"]');
        if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Gonderiliyor…'; }
      });
    });
  }

  /* -------- Scroll ilerleme cubugu -------- */
  function initScrollProgress() {
    const bar = document.querySelector('.scroll-progress');
    if (!bar) return;
    const update = () => {
      const st = window.scrollY;
      const docH = document.documentElement.scrollHeight - window.innerHeight;
      bar.style.width = (docH > 0 ? (st / docH) * 100 : 0) + '%';
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
  }

  /* -------- Preloader (intro) -------- */
  function initPreloader() {
    const pre = document.querySelector('.preloader');
    if (!pre) return;
    const done = () => { pre.classList.add('done'); document.body.classList.remove('loading'); };
    // En az 450ms goster, en gec 2.2s'de kapan
    const start = performance.now();
    window.addEventListener('load', () => {
      const wait = Math.max(0, 450 - (performance.now() - start));
      setTimeout(done, wait);
    });
    setTimeout(done, 2200);
  }

  /* -------- Spotlight kartlar (imleci takip eden parlama) -------- */
  function initSpotlight() {
    if (reduceMotion) return;
    document.querySelectorAll('.spotlight, .card').forEach((el) => {
      el.classList.add('spotlight');
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        el.style.setProperty('--mx', ((e.clientX - r.left) / r.width) * 100 + '%');
        el.style.setProperty('--my', ((e.clientY - r.top) / r.height) * 100 + '%');
      });
    });
  }

  /* -------- Maske reveal (clip-path) -------- */
  function initRevealMask() {
    const els = document.querySelectorAll('[data-reveal-mask]');
    if (!els.length) return;
    if (reduceMotion || !('IntersectionObserver' in window)) {
      els.forEach((el) => el.classList.add('is-revealed'));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) { en.target.classList.add('is-revealed'); io.unobserve(en.target); } });
    }, { threshold: 0.2 });
    els.forEach((el) => io.observe(el));
  }

  ready(function () {
    initPreloader();
    initHeader();
    initLenis();
    initCursor();
    initMagnetic();
    initTilt();
    initScrollAnimations();
    initScrollProgress();
    initSpotlight();
    initRevealMask();
    initFaq();
    initFilter();
    initCookies();
    initForms();
    document.body.classList.add('js-ready');
  });
})();
