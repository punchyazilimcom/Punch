<?php
/**
 * Punch Yazilim — Veritabani kurulum/migrasyon CLI'i.
 *
 * KULLANIM:
 *   php bin/migrate.php            # migrations + seeds calistir
 *   php bin/migrate.php --fresh    # once tablolari DROP et, sonra kur (DIKKAT: veri siler)
 *   php bin/migrate.php --no-seed  # yalniz sema, demo veri yok
 *
 * .env dosyasindaki DB_* degerlerini kullanir.
 */

require __DIR__ . '/../app/bootstrap.php';

use App\Core\App;

$args = array_slice($argv, 1);
$fresh  = in_array('--fresh', $args, true);
$noSeed = in_array('--no-seed', $args, true);

$cfg = App::get()->config['db'];

echo "Punch Yazilim — Veritabani Kurulumu\n";
echo "DB: {$cfg['name']} @ {$cfg['host']}:{$cfg['port']}\n\n";

try {
    // Once veritabani olmadan baglan, gerekirse olustur
    $dsnNoDb = "mysql:host={$cfg['host']};port={$cfg['port']};charset={$cfg['charset']}";
    $pdo = new PDO($dsnNoDb, $cfg['user'], $cfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$cfg['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$cfg['name']}`");
    echo "[ok] Veritabani hazir.\n";
} catch (PDOException $e) {
    fwrite(STDERR, "[hata] Baglanti: " . $e->getMessage() . "\n");
    exit(1);
}

if ($fresh) {
    echo "[uyari] --fresh: mevcut tablolar siliniyor...\n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        $pdo->exec("DROP TABLE IF EXISTS `{$t}`");
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "[ok] " . count($tables) . " tablo silindi.\n";
}

$run = function (string $file) use ($pdo) {
    if (!is_file($file)) {
        echo "[atla] Bulunamadi: {$file}\n";
        return;
    }
    $sql = file_get_contents($file);
    $pdo->exec($sql);
    echo "[ok] Calistirildi: " . basename($file) . "\n";
};

$root = __DIR__ . '/..';

// Migrations
foreach (glob($root . '/database/migrations/*.sql') as $f) {
    $run($f);
}

// Seeds
if (!$noSeed) {
    foreach (glob($root . '/database/seeds/*.sql') as $f) {
        $run($f);
    }
}

echo "\nTamamlandi.\n";
if (!$noSeed) {
    echo "Demo giris bilgileri:\n";
    echo "  Admin:    destek@punchyazilim.com / PunchAdmin!2026\n";
    echo "  Musteri:  demo@punchyazilim.com   / Demo!2026\n";
    echo "  >>> CANLIDA bu sifreleri MUTLAKA degistirin. <<<\n";
}
