/**
 * Punch Yazilim — Opsiyonel asset build.
 *
 * NE YAPAR:
 *  - public_html/assets/js/*.js dosyalarini minify ederek .min.js olarak yazar.
 *  - public_html/assets/css/*.css token mimarisini tek app.min.css'e birlestirip minify eder.
 *
 * NEDEN OPSIYONEL:
 *  - Kaynak JS dosyalari zaten tarayicida calisir (CDN global'leri kullanir),
 *    CSS .htaccess ile Brotli/Gzip sikistirilir. Build yalniz ekstra performans icindir.
 *
 * KULLANIM:
 *  npm install && npm run build
 *  Ardindan .env icindeki ASSET_VERSION'i artirin (cache-busting).
 *
 * NOT: Minify edilmis ciktiyi kullanmak isterseniz layout'lardaki
 *      asset('css/...') / asset('js/app.js') yollarini .min surumlerle degistirin
 *      veya ASSET_BUNDLE=1 mantigi ekleyin.
 */
import { build } from 'esbuild';
import { readFile, writeFile, readdir } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = path.dirname(fileURLToPath(import.meta.url));
const jsDir = path.join(root, 'public_html/assets/js');
const cssDir = path.join(root, 'public_html/assets/css');

// --- JS minify ---
const jsFiles = (await readdir(jsDir)).filter((f) => f.endsWith('.js') && !f.endsWith('.min.js'));
for (const file of jsFiles) {
  await build({
    entryPoints: [path.join(jsDir, file)],
    outfile: path.join(jsDir, file.replace(/\.js$/, '.min.js')),
    minify: true,
    bundle: false,
    target: ['es2019'],
    legalComments: 'none',
  });
  console.log('JS minified ->', file.replace(/\.js$/, '.min.js'));
}

// --- CSS bundle + minify (token sirasini koru) ---
const cssOrder = ['tokens.css', 'base.css', 'components.css', 'effects.css', 'cosmos.css', 'interactions.css', 'utilities.css', 'dashboard.css'];
let cssConcat = '';
for (const f of cssOrder) {
  try {
    cssConcat += await readFile(path.join(cssDir, f), 'utf8') + '\n';
  } catch { /* dosya yoksa atla */ }
}
const tmp = path.join(cssDir, '_bundle.tmp.css');
await writeFile(tmp, cssConcat);
await build({
  entryPoints: [tmp],
  outfile: path.join(cssDir, 'app.min.css'),
  minify: true,
  loader: { '.css': 'css' },
});
await import('node:fs/promises').then((fs) => fs.unlink(tmp));
console.log('CSS bundled -> app.min.css');

console.log('\nBuild tamamlandi. .env icindeki ASSET_VERSION degerini guncellemeyi unutmayin.');
