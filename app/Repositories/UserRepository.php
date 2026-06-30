<?php

class UserRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findActiveByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, email, password_hash, role, status
             FROM users
             WHERE email = :email AND status = :status
             LIMIT 1'
        );
        $stmt->execute(['email' => $email, 'status' => 'active']);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}
