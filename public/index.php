<?php

declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

$basePath = dirname(__DIR__);

require_once $basePath . '/app/Core/Database.php';
require_once $basePath . '/app/Core/Router.php';
require_once $basePath . '/app/Core/DuplicateRecordException.php';
require_once $basePath . '/app/Core/helpers.php';

spl_autoload_register(function (string $class) use ($basePath): void {
    $paths = [
        $basePath . '/app/Controllers/' . $class . '.php',
        $basePath . '/app/Services/' . $class . '.php',
        $basePath . '/app/Repositories/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

set_exception_handler(function (Throwable $e): void {
    log_error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);

    if (app_config('debug', false)) {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>' . e($e->getMessage()) . '</p>';
    } else {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.</p>';
    }
});

try {
    $dbConfig = require $basePath . '/config/database.php';
    $pdo = Database::connect($dbConfig);
} catch (Throwable $e) {
    log_error('Database connection failed: ' . $e->getMessage());
    http_response_code(500);

    if (app_config('debug', false)) {
        echo '<h1>Database Connection Error</h1>';
        echo '<p>' . e($e->getMessage()) . '</p>';
    } else {
        echo '<h1>Database Connection Error</h1>';
        echo '<p>Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra cấu hình.</p>';
    }
    exit;
}

$userRepository = new UserRepository($pdo);
$renterRepository = new RenterRepository($pdo);
$rentalRepository = new RentalRepository($pdo);

$authService = new AuthService($userRepository);
$renterService = new RenterService($renterRepository);
$rentalService = new RentalService($rentalRepository);
$publicRenterService = new PublicRenterService($renterRepository);

$container = [
    HomeController::class => new HomeController(),
    AuthController::class => new AuthController($authService),
    DashboardController::class => new DashboardController($renterRepository, $rentalService),
    PublicRenterController::class => new PublicRenterController($publicRenterService),
    RenterController::class => new RenterController($renterService, $renterRepository),
    RentalController::class => new RentalController($rentalService, $rentalRepository),
    HealthController::class => new HealthController($pdo),
];

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'handleLogin']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/public-renters/create', [PublicRenterController::class, 'create']);
$router->post('/public-renters', [PublicRenterController::class, 'store']);

$router->get('/renters', [RenterController::class, 'index']);
$router->get('/renters/create', [RenterController::class, 'create']);
$router->post('/renters/store', [RenterController::class, 'store']);
$router->get('/renters/edit', [RenterController::class, 'edit']);
$router->post('/renters/update', [RenterController::class, 'update']);
$router->post('/renters/delete', [RenterController::class, 'delete']);

$router->get('/rentals', [RentalController::class, 'index']);
$router->get('/rentals/create', [RentalController::class, 'create']);
$router->post('/rentals/store', [RentalController::class, 'store']);
$router->get('/rentals/edit', [RentalController::class, 'edit']);
$router->post('/rentals/update', [RentalController::class, 'update']);
$router->post('/rentals/delete', [RentalController::class, 'delete']);

$router->get('/health', [HealthController::class, 'index']);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$router->dispatch($method, $path, $container);
