<?php

namespace App\Models;

class Post extends Model
{
    protected string $table = 'posts';

    public function published(int $limit = 10, int $offset = 0, ?int $categoryId = null): array
    {
        $params = [];
        $where = "p.status = 'published' AND p.published_at <= NOW()";
        if ($categoryId) {
            $where .= ' AND p.category_id = :cat';
            $params['cat'] = $categoryId;
        }
        return $this->db()->fetchAll(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM posts p LEFT JOIN categories c ON c.id = p.category_id
             WHERE {$where} ORDER BY p.published_at DESC LIMIT {$limit} OFFSET {$offset}",
            $params
        );
    }

    public function publishedCount(?int $categoryId = null): int
    {
        $params = [];
        $where = "status = 'published' AND published_at <= NOW()";
        if ($categoryId) {
            $where .= ' AND category_id = :cat';
            $params['cat'] = $categoryId;
        }
        return $this->count($where, $params);
    }

    public function bySlug(string $slug): ?array
    {
        return $this->db()->fetch(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
             FROM posts p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN admins a ON a.id = p.author_id
             WHERE p.slug = :s LIMIT 1",
            ['s' => $slug]
        );
    }

    public function related(int $postId, ?int $categoryId, int $limit = 3): array
    {
        return $this->db()->fetchAll(
            "SELECT * FROM posts WHERE status = 'published' AND id <> :id
             " . ($categoryId ? 'AND category_id = :cat ' : '') . "
             ORDER BY published_at DESC LIMIT {$limit}",
            $categoryId ? ['id' => $postId, 'cat' => $categoryId] : ['id' => $postId]
        );
    }

    public function allForAdmin(): array
    {
        return $this->db()->fetchAll(
            'SELECT p.*, c.name AS category_name FROM posts p
             LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC'
        );
    }
}
