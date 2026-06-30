<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<div class="hero">
    <p>Hệ thống quản lý khách thuê thiết bị và phiếu thuê nội bộ.</p>
    <div class="hero-actions">
        <a href="/public-renters/create" class="btn btn-primary">Đăng ký thuê thiết bị</a>
        <a href="/login" class="btn btn-secondary">Đăng nhập Admin</a>
    </div>
</div>

<section class="features">
    <div class="card">
        <h3>Quản lý khách thuê</h3>
        <p>CRUD khách thuê với email unique, search, pagination và sort an toàn.</p>
    </div>
    <div class="card">
        <h3>Quản lý phiếu thuê</h3>
        <p>Tạo phiếu thuê thiết bị với mã phiếu không trùng và theo dõi trạng thái.</p>
    </div>
    <div class="card">
        <h3>Bảo mật</h3>
        <p>Session an toàn, PDO prepared statements, PRG pattern và anti-spam form công khai.</p>
    </div>
</section>
