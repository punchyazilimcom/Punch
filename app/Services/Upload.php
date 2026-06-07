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
     * @return string|null storage'a goreli yol (uploads/...) veya null
     */
    public static function handle(?array $file, string $subdir = 'tickets', int $maxBytes = 8_388_608): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        if ($file['size'] > $maxBytes) {
            throw new \RuntimeException('Dosya boyutu cok buyuk (max 8MB).');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            throw new \RuntimeException('Desteklenmeyen dosya turu.');
        }
        $ext = self::ALLOWED[$mime];

        $dir = PUNCH_ROOT . '/storage/uploads/' . trim($subdir, '/');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $name = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $dir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            // CLI/test ortami icin fallback
            if (!@rename($file['tmp_name'], $dest)) {
                Logger::error('Dosya tasinamadi', ['dest' => $dest]);
                throw new \RuntimeException('Dosya kaydedilemedi.');
            }
        }
        @chmod($dest, 0644);

        return 'uploads/' . trim($subdir, '/') . '/' . $name;
    }
}
