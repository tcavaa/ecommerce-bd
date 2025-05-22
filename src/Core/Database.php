<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?self $instance = null;
    private PDO $connection;
    private \PDOStatement $statement;

    private function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db   = $_ENV['DB_NAME'] ?? 'ecommerce';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $query, array $params = []): self
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    public function get(): array
    {
        return $this->statement->fetchAll();
    }

    public function getOne(): ?array
    {
        $result = $this->statement->fetch();
        return $result ?: null;
    }

    public function getOrFail(): array
    {
        $result = $this->getOne();
        if (!$result) {
            throw new Exception("No result found.");
        }
        return $result;
    }

    public function fetchColumn(): string|false
    {
        return $this->statement->fetchColumn();
    }

    public function getLastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
