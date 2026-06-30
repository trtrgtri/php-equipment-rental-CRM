<?php

class HealthController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $appStatus = 'ok';
        $dbStatus = 'ok';
        $message = 'Equipment Rental CRM is running.';

        try {
            $this->db->query('SELECT 1');
        } catch (Throwable $e) {
            $dbStatus = 'error';
            log_error('Health check DB error: ' . $e->getMessage());
            $message = app_config('debug', false)
                ? $e->getMessage()
                : 'Database connection failed.';
        }

        $httpCode = $dbStatus === 'ok' ? 200 : 503;
        http_response_code($httpCode);

        echo json_encode([
            'app' => app_config('name', 'Equipment Rental CRM'),
            'status' => $appStatus,
            'database' => $dbStatus,
            'message' => $message,
            'timestamp' => date('c'),
        ], JSON_UNESCAPED_UNICODE);
    }
}
