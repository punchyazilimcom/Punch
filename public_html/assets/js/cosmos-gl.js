/* ============================================================
   PUNCH YAZILIM — WebGL Nebula + Kara Delik ("evren disi" cekirdek)
   Fullscreen fragment shader: FBM nebula (mor/cyan/pembe) +
   fareyle etkilesimli gravitasyonel bukulme (kara delik) + akresyon halkasi.
   WebGL yoksa / reduced-motion / dusuk cihazda sessizce devre disi kalir
   (arkadaki 2D kozmik katman zaten calismaya devam eder).
   ============================================================ */
(function () {
  'use strict';
  const canvas = document.getElementById('nebula-gl');
  if (!canvas) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  if ((navigator.hardwareConcurrency || 4) < 4) return;

  let gl;
  try {
    gl = canvas.getContext('webgl', { antialias: false, alpha: true, premultipliedAlpha: false })
      || canvas.getContext('experimental-webgl');
  } catch (e) { return; }
  if (!gl) return;

  const VERT = `
    attribute vec2 p;
    void main(){ gl_Position = vec4(p, 0.0, 1.0); }
  `;

  const FRAG = `
    precision highp float;
    uniform vec2 u_res;
    uniform float u_time;
    uniform vec2 u_mouse;     // 0..1
    uniform float u_mAmt;     // kara delik etkisi 0..1

    // hash & noise
    float hash(vec2 p){ p = fract(p*vec2(123.34,456.21)); p += dot(p, p+45.32); return fract(p.x*p.y); }
    float noise(vec2 p){
      vec2 i = floor(p); vec2 f = fract(p);
      float a = hash(i), b = hash(i+vec2(1.,0.)), c = hash(i+vec2(0.,1.)), d = hash(i+vec2(1.,1.));
      vec2 u = f*f*(3.-2.*f);
      return mix(a,b,u.x) + (c-a)*u.y*(1.-u.x) + (d-b)*u.x*u.y;
    }
    float fbm(vec2 p){
      float v = 0.0, a = 0.5;
      mat2 m = mat2(1.6,1.2,-1.2,1.6);
      for(int i=0;i<6;i++){ v += a*noise(p); p = m*p; a *= 0.5; }
      return v;
    }

    void main(){
      vec2 uv = gl_FragCoord.xy / u_res.xy;
      float aspect = u_res.x / u_res.y;
      vec2 st = uv; st.x *= aspect;
      vec2 m = u_mouse; m.x *= aspect;

      // --- Kara delik: merkeze dogru gravitasyonel cekme/bukulme ---
      vec2 dir = st - m;
      float dist = length(dir);
      float pull = u_mAmt * 0.06 / (dist*dist + 0.02);
      st -= normalize(dir) * pull;                 // domain warp (lensing)
      float swirl = u_mAmt * 0.5 / (dist + 0.18);  // burgac
      float s = sin(swirl), c = cos(swirl);
      st = m + mat2(c,-s,s,c) * (st - m);

      // --- Nebula (akan FBM) ---
      float t = u_time * 0.03;
      vec2 q = st*1.6;
      float n1 = fbm(q + vec2(t, -t*0.6));
      float n2 = fbm(q*1.8 + vec2(-t*0.5, t) + n1);
      float neb = pow(clamp(n1*0.6 + n2*0.6, 0.0, 1.0), 1.6);

      vec3 violet = vec3(0.49,0.23,0.93);
      vec3 cyan   = vec3(0.21,0.88,0.84);
      vec3 pink   = vec3(1.0,0.37,0.82);
      vec3 col = mix(violet, pink, smoothstep(0.3,0.9,n2));
      col = mix(col, cyan, smoothstep(0.55,1.0,n1)*0.5);
      col *= neb * 1.3;

      // yildiz kirintilari
      float star = step(0.995, hash(floor(uv*u_res.xy/2.0)));
      col += star * 0.6;

      // --- Kara delik govdesi + akresyon halkasi ---
      float hole = smoothstep(0.085, 0.02, dist);          // karanlik cekirdek
      float ring = smoothstep(0.16,0.10,dist) - smoothstep(0.10,0.06,dist);
      col = mix(col, vec3(0.0), hole * u_mAmt);
      col += ring * u_mAmt * vec3(0.8,0.55,1.0) * 1.4;      // parlayan halka

      // kenar karartma (vinyet)
      float vig = smoothstep(1.25, 0.2, length(uv-0.5));
      col *= vig;

      gl_FragColor = vec4(col, clamp(neb*1.1 + ring*u_mAmt + hole*u_mAmt, 0.0, 1.0));
    }
  `;

  function compile(type, src) {
    const sh = gl.createShader(type);
    gl.shaderSource(sh, src); gl.compileShader(sh);
    if (!gl.getShaderParameter(sh, gl.COMPILE_STATUS)) { gl.deleteShader(sh); return null; }
    return sh;
  }
  const vs = compile(gl.VERTEX_SHADER, VERT);
  const fs = compile(gl.FRAGMENT_SHADER, FRAG);
  if (!vs || !fs) return;
  const prog = gl.createProgram();
  gl.attachShader(prog, vs); gl.attachShader(prog, fs); gl.linkProgram(prog);
  if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) return;
  gl.useProgram(prog);

  const buf = gl.createBuffer();
  gl.bindBuffer(gl.ARRAY_BUFFER, buf);
  gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
  const loc = gl.getAttribLocation(prog, 'p');
  gl.enableVertexAttribArray(loc);
  gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

  const uRes = gl.getUniformLocation(prog, 'u_res');
  const uTime = gl.getUniformLocation(prog, 'u_time');
  const uMouse = gl.getUniformLocation(prog, 'u_mouse');
  const uMAmt = gl.getUniformLocation(prog, 'u_mAmt');

  // performans icin 0.7x cozunurluk
  const SCALE = window.innerWidth < 768 ? 0.5 : 0.7;
  let mx = 0.5, my = 0.5, tmx = 0.5, tmy = 0.5, amt = 0, tamt = 0, raf, start = performance.now();

  function resize() {
    const w = Math.max(1, (canvas.clientWidth * SCALE) | 0);
    const h = Math.max(1, (canvas.clientHeight * SCALE) | 0);
    if (canvas.width !== w || canvas.height !== h) { canvas.width = w; canvas.height = h; }
    gl.viewport(0, 0, canvas.width, canvas.height);
  }

  function render() {
    resize();
    mx += (tmx - mx) * 0.08; my += (tmy - my) * 0.08;
    amt += (tamt - amt) * 0.05;
    gl.uniform2f(uRes, canvas.width, canvas.height);
    gl.uniform1f(uTime, (performance.now() - start) / 1000);
    gl.uniform2f(uMouse, mx, my);
    gl.uniform1f(uMAmt, amt);
    gl.drawArrays(gl.TRIANGLES, 0, 3);
    raf = requestAnimationFrame(render);
  }
  render();

  const hero = canvas.closest('.hero') || canvas.parentElement;
  window.addEventListener('mousemove', (e) => {
    const r = (hero || canvas).getBoundingClientRect();
    tmx = (e.clientX - r.left) / r.width;
    tmy = 1.0 - (e.clientY - r.top) / r.height;
    tamt = (tmx >= 0 && tmx <= 1 && tmy >= 0 && tmy <= 1) ? 1.0 : 0.0;
  }, { passive: true });
  window.addEventListener('mouseout', () => { tamt = 0.0; });
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf); else render();
  });
})();
