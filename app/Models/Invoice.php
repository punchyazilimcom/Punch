<?php

namespace App\Models;

class Invoice extends Model
{
    protected string $table = 'invoices';

    public function forUser(int $userId): array
    {
        return $this->db()->fetchAll(
            'SELECT * FROM invoices WHERE user_id = :u ORDER BY issued_at DESC',
            ['u' => $userId]
        );
    }

    public function byNumber(string $no): ?array
    {
        return $this->findBy('invoice_no', $no);
    }

    public function nextNumber(): string
    {
        $year = date('Y');
        $count = (int) $this->db()->fetchColumn(
            "SELECT COUNT(*) FROM invoices WHERE invoice_no LIKE :p",
            ['p' => "PUNCH-{$year}-%"]
        );
        return sprintf('PUNCH-%s-%05d', $year, $count + 1);
    }
}
