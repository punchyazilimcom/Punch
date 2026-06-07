<?php

namespace App\Models;

class AuditLog extends Model
{
    protected string $table = 'audit_logs';

    public function record(string $actorType, ?int $actorId, string $action, array $meta = [], string $ip = ''): void
    {
        $this->create([
            'actor_type' => $actorType,
            'actor_id'   => $actorId,
            'action'     => $action,
            'meta_json'  => json_encode($meta, JSON_UNESCAPED_UNICODE),
            'ip'         => $ip,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function recent(int $limit = 100): array
    {
        return $this->db()->fetchAll("SELECT * FROM audit_logs ORDER BY id DESC LIMIT {$limit}");
    }
}
