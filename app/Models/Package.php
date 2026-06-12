<?php

namespace App\Models;

class Package extends Model
{
    protected string $table = 'packages';

    public function active(): array
    {
        return $this->db()->fetchAll(
            'SELECT * FROM packages WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public function featured(int $limit = 3): array
    {
        return $this->db()->fetchAll(
            "SELECT * FROM packages WHERE is_active = 1 ORDER BY sort_order ASC, id ASC LIMIT {$limit}"
        );
    }

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /** features_json'u diziye cevirir */
    public static function features(array $package): array
    {
        $f = $package['features_json'] ?? '[]';
        $arr = is_array($f) ? $f : json_decode((string) $f, true);
        return is_array($arr) ? $arr : [];
    }
}
