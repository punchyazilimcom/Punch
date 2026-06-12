<?php

namespace App\Models;

class Portfolio extends Model
{
    protected string $table = 'portfolio';

    public function active(): array
    {
        return $this->db()->fetchAll('SELECT * FROM portfolio WHERE is_active = 1 ORDER BY sort_order ASC, id DESC');
    }

    public function featured(int $limit = 6): array
    {
        return $this->db()->fetchAll("SELECT * FROM portfolio WHERE is_active = 1 ORDER BY sort_order ASC, id DESC LIMIT {$limit}");
    }

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function categories(): array
    {
        return $this->db()->fetchAll("SELECT DISTINCT category FROM portfolio WHERE is_active = 1 AND category <> '' ORDER BY category");
    }
}
