<?php

class AuthController
{
    public function __construct(private AuthService $authService) {}

    public function login(): void
    {
        if (is_logged_in()) {
            redirect('/dashboard');
        }

        render('auth/login', [
            'title' => 'Đăng nhập',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function handleLogin(): void
    {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            flash('error', 'Yêu cầu không hợp lệ (CSRF token thất bại).');
            redirect('/renters');
            return;
        }
        $result = $this->authService->login(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );

        if (!$result['success']) {
            render('auth/login', [
                'title' => 'Đăng nhập',
                'errors' => $result['errors'],
                'old' => $result['old'] ?? [],
            ]);

            return;
        }

        flash('success', 'Đăng nhập thành công.');
        redirect('/dashboard');
    }

    public function logout(): void
    {
        $this->authService->logout();
        redirect('/login');
    }
}
