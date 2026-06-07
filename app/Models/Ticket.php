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

    public function addMessage(int $ticketId, string $senderType, int $senderId, string $message, ?string $attachment = null, bool $internal = false): int
    {
        return $this->db()->insert('ticket_messages', [
            'ticket_id'        => $ticketId,
            'sender_type'      => $senderType,
            'sender_id'        => $senderId,
            'message'          => $message,
            'attachment_path'  => $attachment,
            'is_internal_note' => $internal ? 1 : 0,
        ]);
    }

    public function touch(int $ticketId, string $status): void
    {
        $this->update($ticketId, ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }
}
