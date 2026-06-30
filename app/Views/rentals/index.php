<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<div class="toolbar">
    <form method="GET" action="/rentals" class="search-form">
        <input name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm theo mã phiếu, tên, email, thiết bị">
        <input type="hidden" name="sort" value="<?= e($sort ?? 'created_at') ?>">
        <input type="hidden" name="direction" value="<?= e($direction ?? 'desc') ?>">
        <button type="submit" class="btn btn-secondary">Tìm kiếm</button>
    </form>
    <a href="/rentals/create" class="btn btn-primary">+ Tạo phiếu thuê</a>
</div>

<p class="meta">Tổng: <?= e((string) ($totalItems ?? 0)) ?> phiếu thuê | Trang <?= e((string) ($page ?? 1)) ?>/<?= e((string) ($totalPages ?? 1)) ?></p>

<table class="data-table">
    <thead>
        <tr>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=rental_code&direction=asc">Mã phiếu</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=renter_name&direction=asc">Khách thuê</a></th>
            <th>Email</th>
            <th>Thiết bị</th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=total_amount&direction=desc">Tổng tiền</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=status&direction=asc">Trạng thái</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=created_at&direction=desc">Ngày tạo</a></th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rentals)): ?>
            <tr><td colspan="8" class="empty">Không có dữ liệu.</td></tr>
        <?php else: ?>
            <?php foreach ($rentals as $rental): ?>
                <tr>
                    <td><?= e($rental['rental_code']) ?></td>
                    <td><?= e($rental['renter_name']) ?></td>
                    <td><?= e($rental['renter_email'] ?? '') ?></td>
                    <td><?= e($rental['equipment_name']) ?></td>
                    <td><?= e(number_format((float) $rental['total_amount'], 0, ',', '.')) ?> đ</td>
                    <td><span class="badge"><?= e($rental['status']) ?></span></td>
                    <td><?= e($rental['created_at']) ?></td>
                    <td class="actions">
                        <a href="/rentals/edit?id=<?= e((string) $rental['id']) ?>" class="btn btn-sm">Sửa</a>
                        <form method="POST" action="/rentals/delete" class="inline-form" onsubmit="return confirm('Xóa phiếu thuê này?');">
                            <input type="hidden" name="id" value="<?= e((string) $rental['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if (($totalPages ?? 1) > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?q=<?= e($keyword ?? '') ?>&page=<?= $i ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>"
               class="<?= ($page ?? 1) === $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
