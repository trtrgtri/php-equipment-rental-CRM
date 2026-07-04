<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../Views/' . $layout . '.php';
}

function partial(string $name, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/partials/' . $name . '.php';
}

function flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function old(string $key, array $old = [], string $default = ''): string
{
    return e((string) ($old[$key] ?? $default));
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('error', 'Vui lòng đăng nhập để tiếp tục.');
        redirect('/login');
    }

    $timeout = app_config('session_timeout', 1800);
    $lastActivity = $_SESSION['last_activity'] ?? 0;

    if ($lastActivity > 0 && (time() - $lastActivity) > $timeout) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
        flash('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
        redirect('/login');
    }

    $_SESSION['last_activity'] = time();
}

function app_config(string $key, mixed $default = null): mixed
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../../config/app.php';
    }

    return $config[$key] ?? $default;
}

function log_error(string $message): void
{
    $logDir = __DIR__ . '/../../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $line = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
    file_put_contents($logDir . '/app.log', $line, FILE_APPEND);
}

function safe_db_error_message(Throwable $e): string
{
    log_error($e->getMessage());

    if (app_config('debug', false)) {
        return 'Lỗi hệ thống: ' . $e->getMessage();
    }

    return 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.';
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function has_role(string $role): bool
{
    return ($_SESSION['user_role'] ?? '') === $role;
}

function require_admin(): void
{
    require_login();
    if (!has_role('admin')) {
        flash('error', 'Bạn không có quyền thực hiện thao tác này.');
        redirect('/dashboard');
        exit;
    }
}
