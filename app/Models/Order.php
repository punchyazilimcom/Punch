<?php

namespace App\Models;

class Order extends Model
{
    protected string $table = 'orders';

    public function byMerchantOid(string $oid): ?array
    {
        return $this->findBy('merchant_oid', $oid);
    }

    public function forUser(int $userId): array
    {
        return $this->db()->fetchAll(
            'SELECT o.*, p.title AS package_title FROM orders o
             LEFT JOIN packages p ON p.id = o.package_id
             WHERE o.user_id = :u ORDER BY o.created_at DESC',
            ['u' => $userId]
        );
    }

    public function recent(int $limit = 20): array
    {
        return $this->db()->fetchAll(
            "SELECT o.*, u.name AS user_name, u.email AS user_email, p.title AS package_title
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             LEFT JOIN packages p ON p.id = o.package_id
             ORDER BY o.created_at DESC LIMIT {$limit}"
        );
    }

    public function totalRevenue(): float
    {
        return (float) $this->db()->fetchColumn("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status = 'paid'");
    }

    public function revenueThisMonth(): float
    {
        return (float) $this->db()->fetchColumn(
            "SELECT COALESCE(SUM(amount),0) FROM orders WHERE status = 'paid'
             AND YEAR(paid_at) = YEAR(CURDATE()) AND MONTH(paid_at) = MONTH(CURDATE())"
        );
    }
}
