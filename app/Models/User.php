<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', strtolower(trim($email)));
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function search(string $q = '', int $limit = 50, int $offset = 0): array
    {
        $like = '%' . $q . '%';
        return $this->db()->fetchAll(
            "SELECT * FROM users WHERE name LIKE :q OR email LIKE :q OR company LIKE :q
             ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}",
            ['q' => $like]
        );
    }
}
