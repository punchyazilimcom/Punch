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

  /* -------- Hiperuzay preloader (warp tunnel intro) -------- */
  function initPreloader() {
    const pre = document.querySelector('.preloader');
    if (!pre) return;
    const finish = () => { pre.classList.add('done'); document.body.classList.remove('loading'); };
    if (reduceMotion) { finish(); return; }

    const cv = pre.querySelector('.preloader-canvas');
    let boost = 0, closing = false, raf;
    if (cv) {
      const ctx = cv.getContext('2d');
      let w, h, dpr, cx, cy, stars;
      const resize = () => {
        dpr = Math.min(devicePixelRatio || 1, 2);
        w = innerWidth; h = innerHeight; cv.width = w * dpr; cv.height = h * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0); cx = w / 2; cy = h / 2;
      };
      resize(); addEventListener('resize', resize);
      stars = Array.from({ length: 260 }, () => ({ a: Math.random() * Math.PI * 2, r: Math.random() * 40, z: Math.random() }));
      const COL = ['223,231,255', '173,132,247', '54,224,214', '255,95,209'];
      (function loop() {
        ctx.fillStyle = 'rgba(5,3,16,0.35)'; ctx.fillRect(0, 0, w, h);
        ctx.globalCompositeOperation = 'lighter';
        const sp = 1.6 + boost;
        for (const s of stars) {
          const pr = s.r;
          s.r += (0.6 + s.r * 0.03) * sp;
          const x0 = cx + Math.cos(s.a) * pr, y0 = cy + Math.sin(s.a) * pr;
          const x1 = cx + Math.cos(s.a) * s.r, y1 = cy + Math.sin(s.a) * s.r;
          ctx.strokeStyle = `rgba(${COL[(s.z * COL.length) | 0]},${Math.min(1, s.r / 240)})`;
          ctx.lineWidth = Math.min(2.4, 0.4 + s.r / 160);
          ctx.beginPath(); ctx.moveTo(x0, y0); ctx.lineTo(x1, y1); ctx.stroke();
          if (s.r > Math.hypot(w, h) * 0.6) { s.r = Math.random() * 20; s.a = Math.random() * Math.PI * 2; }
        }
        ctx.globalCompositeOperation = 'source-over';
        raf = requestAnimationFrame(loop);
      })();
    }

    const close = () => {
      if (closing) return; closing = true;
      pre.classList.add('warp');
      // hiperuzay sicramasi
      let b = 0; const ramp = setInterval(() => { boost = (b += 1.4); if (b > 14) clearInterval(ramp); }, 16);
      setTimeout(() => { cancelAnimationFrame(raf); finish(); }, 620);
    };
    const start = performance.now();
    addEventListener('load', () => setTimeout(close, Math.max(0, 700 - (performance.now() - start))));
    setTimeout(close, 2600); // guvenlik
  }

  /* -------- Hero derinlik parallax (fareyle 3D katman) -------- */
  function initHeroParallax() {
    if (reduceMotion) return;
    const hero = document.querySelector('.hero');
    const inner = document.querySelector('.hero .hero-inner');
    if (!hero || !inner) return;
    hero.addEventListener('mousemove', (e) => {
      const r = hero.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width - 0.5;
      const py = (e.clientY - r.top) / r.height - 0.5;
      inner.style.transform = `translate3d(${px * -18}px, ${py * -12}px, 0)`;
    });
    hero.addEventListener('mouseleave', () => { inner.style.transform = ''; });
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

  /* -------- Kuyruklu yildiz imleci izi (kozmik) -------- */
  function initCometTrail() {
    if (reduceMotion || window.matchMedia('(pointer: coarse)').matches) return;
    if (window.PunchPerf && !window.PunchPerf.atLeast('medium')) return; // orta+ kademe
    const cv = document.createElement('canvas');
    cv.id = 'comet-trail';
    document.body.appendChild(cv);
    const ctx = cv.getContext('2d');
    let w, h, dpr;
    const resize = () => {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = innerWidth; h = innerHeight; cv.width = w * dpr; cv.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    };
    resize();
    addEventListener('resize', resize);

    const COL = ['223,231,255', '173,132,247', '54,224,214', '255,95,209'];
    const parts = [];
    let mx = -99, my = -99, pmx = -99, pmy = -99;
    addEventListener('mousemove', (e) => { mx = e.clientX; my = e.clientY; }, { passive: true });

    (function loop() {
      ctx.clearRect(0, 0, w, h);
      if (pmx >= 0) {
        const d = Math.hypot(mx - pmx, my - pmy);
        const n = Math.min(4, Math.floor(d / 6));
        for (let i = 0; i < n; i++) {
          parts.push({
            x: mx, y: my,
            vx: (Math.random() - 0.5) * 0.8, vy: (Math.random() - 0.5) * 0.8 + 0.3,
            life: 1, r: Math.random() * 2 + 0.6, col: COL[(Math.random() * COL.length) | 0],
          });
        }
      }
      pmx = mx; pmy = my;
      ctx.globalCompositeOperation = 'lighter';
      for (let i = parts.length - 1; i >= 0; i--) {
        const p = parts[i];
        p.x += p.vx; p.y += p.vy; p.life -= 0.035;
        if (p.life <= 0) { parts.splice(i, 1); continue; }
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r * p.life, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(${p.col},${p.life * 0.8})`;
        ctx.fill();
      }
      ctx.globalCompositeOperation = 'source-over';
      requestAnimationFrame(loop);
    })();
  }

  /* -------- Warp: hizli scroll'da yildiz cizgileri -------- */
  function initWarp() {
    if (reduceMotion) return;
    if (window.PunchPerf && !window.PunchPerf.atLeast('high')) return; // yalniz yuksek
    const streaks = document.querySelector('.warp-streaks');
    if (!streaks) return;
    const ctx = streaks.getContext ? streaks.getContext('2d') : null;
    let w, h, dpr, last = window.scrollY, vel = 0, raf = null;
    const resize = () => {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = innerWidth; h = innerHeight; streaks.width = w * dpr; streaks.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    };
    if (!ctx) return;
    resize(); addEventListener('resize', resize);

    const draw = () => {
      ctx.clearRect(0, 0, w, h);
      const cx = w / 2, cy = h / 2;
      const intensity = Math.min(1, Math.abs(vel) / 45);
      if (intensity > 0.05) {
        document.body.classList.add('warping');
        ctx.globalCompositeOperation = 'lighter';
        const lines = 40;
        for (let i = 0; i < lines; i++) {
          const ang = Math.random() * Math.PI * 2;
          const r0 = 60 + Math.random() * 120;
          const len = 30 + intensity * 220 * Math.random();
          const x0 = cx + Math.cos(ang) * r0, y0 = cy + Math.sin(ang) * r0;
          const x1 = cx + Math.cos(ang) * (r0 + len), y1 = cy + Math.sin(ang) * (r0 + len);
          ctx.strokeStyle = `rgba(200,210,255,${0.08 * intensity})`;
          ctx.lineWidth = 1.2;
          ctx.beginPath(); ctx.moveTo(x0, y0); ctx.lineTo(x1, y1); ctx.stroke();
        }
        ctx.globalCompositeOperation = 'source-over';
      } else {
        document.body.classList.remove('warping');
      }
      vel *= 0.82;
      raf = Math.abs(vel) > 0.5 ? requestAnimationFrame(draw) : null;
      if (!raf) { ctx.clearRect(0, 0, w, h); document.body.classList.remove('warping'); }
    };
    addEventListener('scroll', () => {
      vel = window.scrollY - last; last = window.scrollY;
      if (!raf) raf = requestAnimationFrame(draw);
    }, { passive: true });
  }

  ready(function () {
    initPreloader();
    initHeader();
    initLenis();
    initCursor();
    initCometTrail();
    initWarp();
    initHeroParallax();
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
