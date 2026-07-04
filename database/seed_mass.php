<?php

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}", $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for ($i = 1; $i <= 200; $i++) {
    $name = "Khách hàng $i";
    $email = "khach$i@example.com";
    $phone = "0909000" . str_pad($i, 3, '0', STR_PAD_LEFT);
    $statuses = ['new', 'contacted', 'approved', 'inactive'];
    $status = $statuses[array_rand($statuses)];
    $created = date('Y-m-d H:i:s', strtotime("-" . rand(1, 180) . " days"));
    $stmt = $pdo->prepare("INSERT INTO renters (name, email, phone, status, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $status, $created]);
}

for ($i = 1; $i <= 300; $i++) {
    $code = 'RNT-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT);
    $renter_name = "Khách hàng " . rand(1, 200);
    $renter_email = "khach" . rand(1, 200) . "@example.com";
    $equipment_list = ['Máy khoan', 'Máy cắt cỏ', 'Máy phát điện', 'Máy nén khí', 'Máy hàn'];
    $equipment = $equipment_list[array_rand($equipment_list)];
    $amount = rand(200000, 5000000);
    $statuses = ['pending', 'active', 'returned', 'overdue', 'cancelled'];
    $status = $statuses[array_rand($statuses)];
    $created = date('Y-m-d H:i:s', strtotime("-" . rand(1, 180) . " days"));
    $stmt = $pdo->prepare("INSERT INTO rentals (rental_code, renter_name, renter_email, equipment_name, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$code, $renter_name, $renter_email, $equipment, $amount, $status, $created]);
}

echo "Đã thêm dữ liệu thành công.\n";
