<?php

namespace App\Models;

class Ticket extends Model
{
    protected string $table = 'tickets';

    public function forUser(int $userId): array
    {
        return $this->db()->fetchAll(
            'SELECT * FROM tickets WHERE user_id = :u ORDER BY updated_at DESC',
            ['u' => $userId]
        );
    }

    public function withUser(): array
    {
        return $this->db()->fetchAll(
            'SELECT t.*, u.name AS user_name, u.email AS user_email
             FROM tickets t LEFT JOIN users u ON u.id = t.user_id
             ORDER BY (t.status = "open") DESC, t.updated_at DESC'
        );
    }

    public function messages(int $ticketId, bool $includeInternal = true): array
    {
        $sql = 'SELECT * FROM ticket_messages WHERE ticket_id = :t';
        if (!$includeInternal) {
            $sql .= ' AND is_internal_note = 0';
        }
        $sql .= ' ORDER BY created_at ASC';
        return $this->db()->fetchAll($sql, ['t' => $ticketId]);
    }

    public function openCount(): int
    {
        return $this->count("status = 'open'");
    }
}
