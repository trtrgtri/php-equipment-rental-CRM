<?php

class RentalService
{
    private const STATUSES = ['pending', 'active', 'returned', 'overdue', 'cancelled'];
    private const SORTABLE = ['created_at', 'rental_code', 'renter_name', 'total_amount', 'status'];

    public function __construct(private RentalRepository $repo)
    {
    }

    public function getRentalList(array $query): array
    {
        $keyword = trim($query['q'] ?? '');
        $page = max(1, (int) ($query['page'] ?? 1));
        $perPage = 10;
        $sort = trim($query['sort'] ?? 'created_at');
        $direction = strtolower(trim($query['direction'] ?? 'desc'));

        if (!in_array($sort, self::SORTABLE, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $totalItems = $this->repo->countAll($keyword);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        return [
            'rentals' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction,
        ];
    }

    public function createRental(array $input): array
    {
        $validation = $this->validateRentalData($input);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors'], 'values' => $validation['values']];
        }

        try {
            $this->repo->create($validation['values']);

            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException) {
            return [
                'success' => false,
                'errors' => ['rental_code' => 'Mã phiếu thuê này đã tồn tại trong hệ thống.'],
                'values' => $validation['values'],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'errors' => ['general' => safe_db_error_message($e)],
                'values' => $validation['values'],
            ];
        }
    }

    public function updateRental(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Phiếu thuê không tồn tại.']];
        }

        $validation = $this->validateRentalData($input);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors'], 'values' => $validation['values']];
        }

        try {
            $this->repo->update($id, $validation['values']);

            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException) {
            return [
                'success' => false,
                'errors' => ['rental_code' => 'Mã phiếu thuê này đã tồn tại trong hệ thống.'],
                'values' => $validation['values'],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'errors' => ['general' => safe_db_error_message($e)],
                'values' => $validation['values'],
            ];
        }
    }

    public function deleteRental(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        }

        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Phiếu thuê không tồn tại.']];
        }

        try {
            $this->repo->delete($id);

            return ['success' => true, 'errors' => []];
        } catch (Throwable $e) {
            return ['success' => false, 'errors' => ['general' => safe_db_error_message($e)]];
        }
    }

    public function getStatuses(): array
    {
        return self::STATUSES;
    }

    public function getDashboardStats(): array
    {
        return [
            'rental_counts' => $this->repo->countByStatus(),
            'total_revenue' => $this->repo->sumTotalAmount(),
        ];
    }

    private function validateRentalData(array $input): array
    {
        $errors = [];
        $rentalCode = trim($input['rental_code'] ?? '');
        $renterName = trim($input['renter_name'] ?? '');
        $renterEmail = trim($input['renter_email'] ?? '');
        $equipmentName = trim($input['equipment_name'] ?? '');
        $totalAmount = trim($input['total_amount'] ?? '');
        $status = trim($input['status'] ?? 'pending');

        if ($rentalCode === '') {
            $errors['rental_code'] = 'Mã phiếu thuê không được để trống.';
        }

        if ($renterName === '') {
            $errors['renter_name'] = 'Tên khách thuê không được để trống.';
        }

        if ($renterEmail !== '' && !filter_var($renterEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['renter_email'] = 'Email khách thuê không đúng định dạng.';
        }

        if ($equipmentName === '') {
            $errors['equipment_name'] = 'Tên thiết bị không được để trống.';
        }

        if ($totalAmount === '' || !is_numeric($totalAmount)) {
            $errors['total_amount'] = 'Tổng tiền thuê phải là số hợp lệ.';
        } elseif ((float) $totalAmount < 0) {
            $errors['total_amount'] = 'Tổng tiền thuê không được âm.';
        }

        if (!in_array($status, self::STATUSES, true)) {
            $errors['status'] = 'Trạng thái phiếu thuê không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => [
                'rental_code' => $rentalCode,
                'renter_name' => $renterName,
                'renter_email' => $renterEmail,
                'equipment_name' => $equipmentName,
                'total_amount' => $totalAmount !== '' ? (float) $totalAmount : 0,
                'status' => $status,
            ],
        ];
    }
}
