<?php

class AuthService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function login(string $email, string $password): array
    {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return [
                'success' => false,
                'errors' => ['general' => 'Email và mật khẩu không được để trống.'],
                'old' => ['email' => $email],
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'errors' => ['general' => 'Email không đúng định dạng.'],
                'old' => ['email' => $email],
            ];
        }

        $user = $this->userRepository->findActiveByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            log_error('Login failed for email: ' . $email);

            return [
                'success' => false,
                'errors' => ['general' => 'Email hoặc mật khẩu không đúng.'],
                'old' => ['email' => $email],
            ];
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        return ['success' => true];
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
