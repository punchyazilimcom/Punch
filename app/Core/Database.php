<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

/**
 * PDO sarmalayici. Tum sorgular prepared statement ile calisir.
 */
class Database
{
    private ?PDO $pdo = null;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function pdo(): PDO
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo;
    }

    private function connect(): void
    {
        $c = $this->config;
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $c['host'], $c['port'], $c['name'], $c['charset']
        );
        try {
            $this->pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        } catch (PDOException $e) {
            Logger::error('DB baglanti hatasi: ' . $e->getMessage());
            throw new \RuntimeException('Veritabanina baglanilamadi.', 0, $e);
        }
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $row = $this->query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchColumn(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    public function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $cols);
        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $table,
            implode(', ', array_map(fn($c) => "`$c`", $cols)),
            implode(', ', $placeholders)
        );
        $this->query($sql, $data);
        return (int) $this->pdo()->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = implode(', ', array_map(fn($c) => "`$c` = :set_$c", array_keys($data)));
        $params = [];
        foreach ($data as $k => $v) {
            $params['set_' . $k] = $v;
        }
        $params = array_merge($params, $whereParams);
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $table, $set, $where);
        return $this->query($sql, $params)->rowCount();
    }

    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, $where);
        return $this->query($sql, $params)->rowCount();
    }

    public function beginTransaction(): void { $this->pdo()->beginTransaction(); }
    public function commit(): void { $this->pdo()->commit(); }
    public function rollBack(): void { if ($this->pdo()->inTransaction()) { $this->pdo()->rollBack(); } }
}
