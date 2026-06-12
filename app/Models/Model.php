<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

/**
 * Cok hafif aktif-kayit benzeri temel model.
 */
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    protected function db(): Database
    {
        return App::get()->db();
    }

    public function find(int $id): ?array
    {
        return $this->db()->fetch(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1",
            ['id' => $id]
        );
    }

    public function findBy(string $column, mixed $value): ?array
    {
        return $this->db()->fetch(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = :v LIMIT 1",
            ['v' => $value]
        );
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db()->fetchAll("SELECT * FROM `{$this->table}` ORDER BY {$orderBy}");
    }

    public function create(array $data): int
    {
        return $this->db()->insert($this->table, $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db()->update($this->table, $data, "`{$this->primaryKey}` = :id", ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db()->delete($this->table, "`{$this->primaryKey}` = :id", ['id' => $id]);
    }

    public function count(string $where = '1', array $params = []): int
    {
        return (int) $this->db()->fetchColumn(
            "SELECT COUNT(*) FROM `{$this->table}` WHERE {$where}",
            $params
        );
    }
}
