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

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([$name]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
