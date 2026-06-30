<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Equipment Rental CRM') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php partial('nav'); ?>
    <main class="container">
        <?= $content ?>
    </main>
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Equipment Rental CRM — Quản lý khách thuê &amp; phiếu thuê thiết bị</p>
    </footer>
</body>
</html>
