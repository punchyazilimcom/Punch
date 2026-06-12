<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function withCounts(): array
    {
        return $this->db()->fetchAll(
            "SELECT c.*, COUNT(p.id) AS post_count
             FROM categories c
             LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
             GROUP BY c.id ORDER BY c.name ASC"
        );
    }
}
