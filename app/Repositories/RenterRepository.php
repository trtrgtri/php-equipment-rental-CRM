<?php

class RenterRepository
{
    private const SORTABLE = ['id', 'created_at', 'name', 'email', 'status'];

    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM renters';
        $params = [];

        if ($keyword !== '') {
            $sql .= ' WHERE name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3';
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $sortColumn = in_array($sort, self::SORTABLE, true) ? $sort : 'created_at';
        $sortDirection = $direction === 'asc' ? 'ASC' : 'DESC';

        $sql = 'SELECT id, name, email, phone, status, created_at FROM renters';
        $params = [];

        if ($keyword !== '') {
            $sql .= ' WHERE name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3';
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY {$sortColumn} {$sortDirection}, id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM renters WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $renter = $stmt->fetch();

        return $renter ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO renters (name, email, phone, status, note)
                VALUES (:name, :email, :phone, :status, :note)';
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate renter email.');
            }

            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = 'UPDATE renters
                SET name = :name, email = :email, phone = :phone,
                    status = :status, note = :note, updated_at = NOW()
                WHERE id = :id';

        try {
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate renter email.');
            }

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM renters WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM renters WHERE status = :status');
        $stmt->execute(['status' => $status]);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }
}
