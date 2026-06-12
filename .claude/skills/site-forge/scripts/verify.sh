#!/usr/bin/env bash
# site-forge — Doğrulama: PHP lint + route çözümleme + JS söz dizimi.
# Kullanım: bash .claude/skills/site-forge/scripts/verify.sh
set -uo pipefail

# Repo kökünü bul (app/ ve public_html/ içeren dizin)
ROOT="$(pwd)"
while [ "$ROOT" != "/" ] && { [ ! -d "$ROOT/app" ] || [ ! -d "$ROOT/public_html" ]; }; do
  ROOT="$(dirname "$ROOT")"
done
[ -d "$ROOT/app" ] || { echo "✗ Repo kökü bulunamadı (app/ + public_html/ yok)"; exit 1; }
cd "$ROOT"
echo "→ Kök: $ROOT"

fail=0

echo "== 1) PHP lint =="
while IFS= read -r f; do
  out="$(php -l "$f" 2>&1)"
  echo "$out" | grep -q "No syntax errors" || { echo "  ✗ $f"; echo "$out" | sed 's/^/    /'; fail=1; }
done < <(find app config public_html bin -name '*.php' 2>/dev/null)
[ $fail -eq 0 ] && echo "  ✓ tüm PHP temiz"

echo "== 2) Route handler çözümleme (reflection) =="
SF_ROOT="$ROOT" php -r '
$root=getenv("SF_ROOT");
require $root."/app/bootstrap.php";
use App\Core\Router;
$router = new Router();
require $root."/app/routes.php";
$ref = new ReflectionClass($router); $p = $ref->getProperty("routes"); $p->setAccessible(true);
$routes = $p->getValue($router); $h=[];
foreach($routes as $m=>$list) foreach($list as $rt) if(is_string($rt["handler"])) $h[]=$rt["handler"];
$bad=0;
foreach(array_unique($h) as $x){ [$c,$mm]=explode("@",$x,2);
  if(!class_exists($c)){echo "  ✗ MISSING CLASS $c\n";$bad++;continue;}
  if(!method_exists($c,$mm)){echo "  ✗ MISSING METHOD $c::$mm\n";$bad++;}
}
echo $bad===0 ? "  ✓ ".count(array_unique($h))." route handler çözümlendi\n" : "  ✗ $bad sorun\n";
exit($bad===0?0:1);
' || fail=1

echo "== 3) JS söz dizimi (node --check) =="
if command -v node >/dev/null 2>&1; then
  while IFS= read -r f; do
    node --check "$f" 2>/dev/null || { echo "  ✗ $f"; fail=1; }
  done < <(find public_html/assets/js -name '*.js' ! -name '*.min.js' 2>/dev/null)
  [ $fail -eq 0 ] && echo "  ✓ tüm JS temiz"
else
  echo "  ⚠ node yok, JS kontrolü atlandı"
fi

echo
[ $fail -eq 0 ] && { echo "✅ DOĞRULAMA BAŞARILI"; exit 0; } || { echo "❌ DOĞRULAMA BAŞARISIZ"; exit 1; }
