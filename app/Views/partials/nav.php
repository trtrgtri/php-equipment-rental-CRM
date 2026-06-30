<nav class="navbar">
    <div class="nav-brand">
        <a href="/">Equipment Rental CRM</a>
    </div>
    <ul class="nav-links">
        <?php if (is_logged_in()): ?>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/renters">Khách thuê</a></li>
            <li><a href="/rentals">Phiếu thuê</a></li>
            <li class="nav-user">Xin chào, <?= e($_SESSION['user_name'] ?? 'User') ?></li>
            <li>
                <form method="POST" action="/logout" class="inline-form">
                    <button type="submit" class="btn btn-link">Đăng xuất</button>
                </form>
            </li>
        <?php else: ?>
            <li><a href="/public-renters/create">Đăng ký thuê</a></li>
            <li><a href="/login">Đăng nhập</a></li>
        <?php endif; ?>
    </ul>
</nav>
