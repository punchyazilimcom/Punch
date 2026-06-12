<?php

namespace App\Models;

class Quote extends Model
{
    protected string $table = 'quotes';

    public function forUser(int $userId): array
    {
        return $this->db()->fetchAll(
            'SELECT * FROM quotes WHERE user_id = :u ORDER BY created_at DESC',
            ['u' => $userId]
        );
    }

    public function newCount(): int
    {
        return $this->count("status = 'new'");
    }
}
