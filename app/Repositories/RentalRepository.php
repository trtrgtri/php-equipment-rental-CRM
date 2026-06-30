<?php

class RentalRepository
{
    private const SORTABLE = ['created_at', 'rental_code', 'renter_name', 'total_amount', 'status'];

    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM rentals';
        $params = [];

        if ($keyword !== '') {
            $sql .= ' WHERE rental_code LIKE :keyword1
                      OR renter_name LIKE :keyword2
                      OR renter_email LIKE :keyword3
                      OR equipment_name LIKE :keyword4';
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
            $params['keyword4'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $sortColumn = in_array($sort, self::SORTABLE, true) ? $sort : 'created_at';
        $sortDirection = $direction === 'asc' ? 'ASC' : 'DESC';

        $sql = 'SELECT id, rental_code, renter_name, renter_email, equipment_name, total_amount, status, created_at
                FROM rentals';
        $params = [];

        if ($keyword !== '') {
            $sql .= ' WHERE rental_code LIKE :keyword1
                      OR renter_name LIKE :keyword2
                      OR renter_email LIKE :keyword3
                      OR equipment_name LIKE :keyword4';
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
            $params['keyword4'] = '%' . $keyword . '%';
        }

        $sql .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset';

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
        $stmt = $this->db->prepare('SELECT * FROM rentals WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $rental = $stmt->fetch();

        return $rental ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO rentals (rental_code, renter_name, renter_email, equipment_name, total_amount, status)
                VALUES (:rental_code, :renter_name, :renter_email, :equipment_name, :total_amount, :status)';
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate rental code.');
            }

            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = 'UPDATE rentals
                SET rental_code = :rental_code, renter_name = :renter_name, renter_email = :renter_email,
                    equipment_name = :equipment_name, total_amount = :total_amount, status = :status,
                    updated_at = NOW()
                WHERE id = :id';

        try {
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate rental code.');
            }

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM rentals WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function countByStatus(): array
    {
        $stmt = $this->db->query(
            'SELECT status, COUNT(*) AS total FROM rentals GROUP BY status'
        );

        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['status']] = (int) $row['total'];
        }

        return $result;
    }

    public function sumTotalAmount(): float
    {
        $stmt = $this->db->query('SELECT COALESCE(SUM(total_amount), 0) AS total FROM rentals');

        return (float) ($stmt->fetch()['total'] ?? 0);
    }
}
