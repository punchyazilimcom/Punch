<?php

namespace App\Services;

use App\Core\Logger;

/**
 * Guvenli dosya yukleme. Web kok disindaki storage/uploads altina kaydeder.
 */
class Upload
{
    private const ALLOWED = [
        'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
        'image/gif' => 'gif', 'application/pdf' => 'pdf',
        'application/zip' => 'zip',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];

    /**
     * Ozel (private) dosya yukler -> storage/uploads. Donus: "uploads/..." (storage'a goreli).
     * Ticket ekleri gibi yetki gerektiren dosyalar icin kullanilir; controller uzerinden servis edilir.
     */
    public static function handle(?array $file, string $subdir = 'tickets', int $maxBytes = 8_388_608): ?string
    {
        return self::store($file, $subdir, false, $maxBytes);
    }

    /**
     * Herkese acik (public) gorsel yukler -> public_html/assets/uploads.
     * Donus: web yolu "/assets/uploads/..." (dogrudan src olarak kullanilabilir).
     */
    public static function image(?array $file, string $subdir = 'blog', int $maxBytes = 5_242_880): ?string
    {
        return self::store($file, $subdir, true, $maxBytes);
    }

    private static function store(?array $file, string $subdir, bool $public, int $maxBytes): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        if ($file['size'] > $maxBytes) {
            throw new \RuntimeException('Dosya boyutu cok buyuk.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            throw new \RuntimeException('Desteklenmeyen dosya turu.');
        }
        // Public yuklemede yalniz gorsel kabul et
        if ($public && !str_starts_with($mime, 'image/')) {
            throw new \RuntimeException('Yalniz gorsel yuklenebilir.');
        }
        $ext = self::ALLOWED[$mime];

        $sub = trim($subdir, '/');
        $base = $public
            ? PUNCH_ROOT . '/public_html/assets/uploads/' . $sub
            : PUNCH_ROOT . '/storage/uploads/' . $sub;
        if (!is_dir($base)) {
            @mkdir($base, 0775, true);
        }
        $name = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $base . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            if (!@rename($file['tmp_name'], $dest)) {
                Logger::error('Dosya tasinamadi', ['dest' => $dest]);
                throw new \RuntimeException('Dosya kaydedilemedi.');
            }
        }
        @chmod($dest, 0644);

        return $public
            ? '/assets/uploads/' . $sub . '/' . $name
            : 'uploads/' . $sub . '/' . $name;
    }
}
