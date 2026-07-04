<?php

class RenterService
{
    private const STATUSES = ['new', 'contacted', 'approved', 'inactive'];
    private const SORTABLE = ['created_at', 'name', 'email', 'status'];

    public function __construct(private RenterRepository $repo) {}

    public function getRenterList(array $query): array
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
            'renters' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction,
        ];
    }

    public function createRenter(array $input): array
    {
        $validation = $this->validateRenterData($input);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors'], 'values' => $validation['values']];
        }

        try {
            $this->repo->create($validation['values']);

            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email khách thuê này đã tồn tại trong hệ thống.'],
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

    public function updateRenter(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Khách thuê không tồn tại.']];
        }

        $validation = $this->validateRenterData($input);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors'], 'values' => $validation['values']];
        }

        try {
            $this->repo->update($id, $validation['values']);

            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email khách thuê này đã tồn tại trong hệ thống.'],
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

    public function deleteRenter(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        }

        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Khách thuê không tồn tại.']];
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

    private function validateRenterData(array $input): array
    {
        $errors = [];
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $status = trim($input['status'] ?? 'new');
        $note = trim($input['note'] ?? '');

        if ($name === '') {
            $errors['name'] = 'Tên khách thuê không được để trống.';
        }

        if ($email === '') {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if (!in_array($status, self::STATUSES, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => compact('name', 'email', 'phone', 'status', 'note'),
        ];
    }
}
