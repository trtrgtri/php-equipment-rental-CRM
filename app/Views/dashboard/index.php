<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Tổng khách thuê</h3>
        <p class="stat-number"><?= e((string) $totalRenters) ?></p>
    </div>
    <div class="stat-card">
        <h3>Tổng doanh thu thuê</h3>
        <p class="stat-number"><?= e(number_format($totalRevenue, 0, ',', '.')) ?> đ</p>
    </div>
    <div class="stat-card">
        <h3>Phiếu đang active</h3>
        <p class="stat-number"><?= e((string) ($rentalCounts['active'] ?? 0)) ?></p>
    </div>
    <div class="stat-card">
        <h3>Phiếu pending</h3>
        <p class="stat-number"><?= e((string) ($rentalCounts['pending'] ?? 0)) ?></p>
    </div>
</div>

<div class="quick-links">
    <a href="/renters" class="btn btn-primary">Quản lý khách thuê</a>
    <a href="/rentals" class="btn btn-secondary">Quản lý phiếu thuê</a>
    <a href="/public-renters/create" class="btn btn-secondary">Form đăng ký công khai</a>
</div>
