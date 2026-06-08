#!/usr/bin/env bash
# site-forge — Hostinger paketleme: tek-kök yapı + iki ZIP (TAM + GÜNCELLEME).
# Kullanım: bash .claude/skills/site-forge/scripts/package.sh [çıktı_dizini]
# Varsayılan çıktı: ./dist
set -euo pipefail

ROOT="$(pwd)"
while [ "$ROOT" != "/" ] && { [ ! -d "$ROOT/app" ] || [ ! -d "$ROOT/public_html" ]; }; do
  ROOT="$(dirname "$ROOT")"
done
[ -d "$ROOT/app" ] || { echo "✗ Repo kökü bulunamadı"; exit 1; }
cd "$ROOT"

OUT="${1:-$ROOT/dist}"
mkdir -p "$OUT"
STAGE="$(mktemp -d)"
echo "→ Kök: $ROOT"
echo "→ Staging: $STAGE"

# 1) public_html içeriği köke
cp -a public_html/. "$STAGE/"
# 2) uygulama klasörleri alt klasör olarak
for d in app config vendor storage database bin; do
  [ -d "$ROOT/$d" ] && cp -a "$ROOT/$d" "$STAGE/"
done
[ -f "$ROOT/.env.example" ] && cp "$ROOT/.env.example" "$STAGE/.env.example"
[ -f "$ROOT/README.md" ] && cp "$ROOT/README.md" "$STAGE/README.md"
[ -f "$ROOT/HOSTINGER-KURULUM.txt" ] && cp "$ROOT/HOSTINGER-KURULUM.txt" "$STAGE/HOSTINGER-KURULUM.txt"

# 3) bootstrap yolunu tek-kök yapıya göre düzelt
for f in index.php paytr-notify.php; do
  [ -f "$STAGE/$f" ] && sed -i 's#dirname(__DIR__)#__DIR__#g' "$STAGE/$f"
done

# 4) hassas alt klasörlere deny
for d in vendor bin; do
  [ -d "$STAGE/$d" ] && printf '# Web erisimine kapali\nRequire all denied\nDeny from all\n' > "$STAGE/$d/.htaccess"
done

# 5) runtime temizle + git/gitignore at
find "$STAGE/storage" -type f ! -name '.gitkeep' -delete 2>/dev/null || true
rm -rf "$STAGE/.git" "$STAGE/.gitignore" 2>/dev/null || true

# 6) .env üret (production + APP_KEY); DB alanları boş
if [ -f "$STAGE/.env.example" ] && [ ! -f "$STAGE/.env" ]; then
  php -r '
    $p=$argv[1]."/.env"; $e=file_get_contents($argv[1]."/.env.example");
    $k=bin2hex(random_bytes(32));
    $e=preg_replace("/^APP_ENV=.*/m","APP_ENV=production",$e);
    $e=preg_replace("/^APP_DEBUG=.*/m","APP_DEBUG=false",$e);
    $e=preg_replace("/^COOKIE_SECURE=.*/m","COOKIE_SECURE=true",$e);
    $e=preg_replace("/^APP_KEY=.*/m","APP_KEY=".$k,$e);
    file_put_contents($p,$e);
  ' "$STAGE"
fi

# 7) ZIP'ler
( cd "$STAGE"
  rm -f "$OUT/site-FINAL-public_html.zip" "$OUT/site-GUNCELLEME.zip"
  zip -r -q -y "$OUT/site-FINAL-public_html.zip" . -x '*.DS_Store'
  zip -r -q -y "$OUT/site-GUNCELLEME.zip" . \
    -x '.env' -x 'storage/logs/*' -x 'storage/cache/*' -x 'storage/invoices/*' -x 'storage/uploads/*' \
    -x 'assets/uploads/blog/*' -x 'assets/uploads/portfolio/*' -x '*.DS_Store'
)

echo "✓ TAM:        $OUT/site-FINAL-public_html.zip ($(du -h "$OUT/site-FINAL-public_html.zip" | cut -f1))"
echo "✓ GÜNCELLEME: $OUT/site-GUNCELLEME.zip ($(du -h "$OUT/site-GUNCELLEME.zip" | cut -f1))"
rm -rf "$STAGE"
