<?php

class User
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.name AS role_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             ORDER BY u.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.name AS role_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.name AS role_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.email = ?'
        );
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (role_id, name, email, password, phone)
             VALUES (:role_id, :name, :email, :password, :phone)'
        );
        $stmt->execute([
            ':role_id'  => $data['role_id'],
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':phone'    => $data['phone'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['role_id', 'name', 'email', 'phone'];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $params[":$col"] = $data[$col];
            }
        }

        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $fields[] = 'password = :password';
            $params[':password'] = $data['password'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateProfile(int $id, string $name, string $phone): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET name = :name, phone = :phone WHERE id = :id'
        );
        $stmt->execute([':name' => $name, ':phone' => $phone, ':id' => $id]);
    }

    public function updatePassword(int $id, string $newPassword): void
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'UPDATE users SET password = :password WHERE id = :id'
        );
        $stmt->execute([':password' => $hash, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
