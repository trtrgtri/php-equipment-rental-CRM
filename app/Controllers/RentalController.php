<?php

class RentalController
{
    public function __construct(
        private RentalService $service,
        private RentalRepository $repo
    ) {}

    public function index(): void
    {
        require_login();

        $data = $this->service->getRentalList($_GET);
        render('rentals/index', ['title' => 'Quản lý phiếu thuê'] + $data);
    }

    public function create(): void
    {
        require_login();

        render('rentals/create', [
            'title' => 'Tạo phiếu thuê',
            'errors' => [],
            'old' => [],
            'statuses' => $this->service->getStatuses(),
        ]);
    }

    public function store(): void
    {
        require_login();

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            flash('error', 'Yêu cầu không hợp lệ (CSRF token thất bại).');
            redirect('/renters');
            return;
        }

        $result = $this->service->createRental($_POST);

        if (!$result['success']) {
            render('rentals/create', [
                'title' => 'Tạo phiếu thuê',
                'errors' => $result['errors'],
                'old' => $result['values'] ?? $_POST,
                'statuses' => $this->service->getStatuses(),
            ]);

            return;
        }

        flash('success', 'Phiếu thuê đã được tạo thành công.');
        redirect('/rentals');
    }

    public function edit(): void
    {
        require_login();

        $id = (int) ($_GET['id'] ?? 0);
        $rental = $this->repo->findById($id);

        if (!$rental) {
            flash('error', 'Phiếu thuê không tồn tại.');
            redirect('/rentals');
        }

        render('rentals/edit', [
            'title' => 'Sửa phiếu thuê',
            'rental' => $rental,
            'errors' => [],
            'statuses' => $this->service->getStatuses(),
        ]);
    }

    public function update(): void
    {
        require_login();
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            flash('error', 'Yêu cầu không hợp lệ (CSRF token thất bại).');
            redirect('/renters');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->updateRental($id, $_POST);

        if (!$result['success']) {
            $rental = $this->repo->findById($id) ?? ['id' => $id];

            render('rentals/edit', [
                'title' => 'Sửa phiếu thuê',
                'rental' => array_merge($rental, $result['values'] ?? $_POST),
                'errors' => $result['errors'],
                'statuses' => $this->service->getStatuses(),
            ]);

            return;
        }

        flash('success', 'Phiếu thuê đã được cập nhật.');
        redirect('/rentals');
    }

    public function delete(): void
    {
        require_admin();

        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->deleteRental($id);

        if (!$result['success']) {
            flash('error', $result['errors']['general'] ?? 'Không thể xóa phiếu thuê.');
        } else {
            flash('success', 'Phiếu thuê đã được xóa.');
        }

        redirect('/rentals');
    }
}
