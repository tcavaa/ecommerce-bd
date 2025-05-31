<?php 

namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected Database $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT * FROM {$this->table}")->get();
    }

    public function getById(string $id): array 
    {
        return $this->db->query(
            "SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]
            )->getOrFail();
    }
}