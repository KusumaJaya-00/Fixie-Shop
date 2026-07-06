<?php

class Category
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories ORDER BY name ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
