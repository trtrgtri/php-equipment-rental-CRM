<?php

class PublicRenterController
{
    public function __construct(private PublicRenterService $service) {}

    public function create(): void
    {
        render('public-renters/create', [
            'title' => 'Đăng ký thuê thiết bị',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function store(): void
    {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            flash('error', 'Yêu cầu không hợp lệ (CSRF token thất bại).');
            redirect('/renters');
            return;
        }
        $result = $this->service->store($_POST);

        if (!$result['success']) {
            render('public-renters/create', [
                'title' => 'Đăng ký thuê thiết bị',
                'errors' => $result['errors'],
                'old' => $result['values'] ?? $_POST,
            ]);

            return;
        }

        flash('success', 'Đăng ký thuê thiết bị thành công. Chúng tôi sẽ liên hệ sớm.');
        redirect('/public-renters/create');
    }
}
