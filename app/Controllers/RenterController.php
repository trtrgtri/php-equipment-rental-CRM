<?php

class RenterController
{
    public function __construct(
        private RenterService $service,
        private RenterRepository $repo
    ) {}

    public function index(): void
    {
        require_login();

        $data = $this->service->getRenterList($_GET);
        render('renters/index', ['title' => 'Quản lý khách thuê'] + $data);
    }

    public function create(): void
    {
        require_login();

        render('renters/create', [
            'title' => 'Thêm khách thuê',
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

        $result = $this->service->createRenter($_POST);

        if (!$result['success']) {
            render('renters/create', [
                'title' => 'Thêm khách thuê',
                'errors' => $result['errors'],
                'old' => $result['values'] ?? $_POST,
                'statuses' => $this->service->getStatuses(),
            ]);

            return;
        }

        flash('success', 'Khách thuê đã được tạo thành công.');
        redirect('/renters');
    }

    public function edit(): void
    {
        require_login();

        $id = (int) ($_GET['id'] ?? 0);
        $renter = $this->repo->findById($id);

        if (!$renter) {
            flash('error', 'Khách thuê không tồn tại.');
            redirect('/renters');
        }

        render('renters/edit', [
            'title' => 'Sửa khách thuê',
            'renter' => $renter,
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
        $result = $this->service->updateRenter($id, $_POST);

        if (!$result['success']) {
            $renter = $this->repo->findById($id) ?? ['id' => $id];

            render('renters/edit', [
                'title' => 'Sửa khách thuê',
                'renter' => array_merge($renter, $result['values'] ?? $_POST),
                'errors' => $result['errors'],
                'statuses' => $this->service->getStatuses(),
            ]);

            return;
        }

        flash('success', 'Khách thuê đã được cập nhật.');
        redirect('/renters');
    }

    public function delete(): void
    {
        require_login();
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            flash('error', 'Yêu cầu không hợp lệ (CSRF token thất bại).');
            redirect('/renters');
            return;
        }
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->service->deleteRenter($id);

        if (!$result['success']) {
            flash('error', $result['errors']['general'] ?? 'Không thể xóa khách thuê.');
        } else {
            flash('success', 'Khách thuê đã được xóa.');
        }

        redirect('/renters');
    }
}
