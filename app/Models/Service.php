<?php

namespace App\Models;

class Service extends Model
{
    protected string $table = 'services';

    public function active(): array
    {
        return $this->db()->fetchAll('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC');
    }

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }
}
