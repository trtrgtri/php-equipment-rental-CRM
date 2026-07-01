<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<div class="toolbar">
    <form method="GET" action="/renters" class="search-form">
        <input name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm theo tên, email, SĐT">
        <input type="hidden" name="sort" value="<?= e($sort ?? 'created_at') ?>">
        <input type="hidden" name="direction" value="<?= e($direction ?? 'desc') ?>">
        <button type="submit" class="btn btn-secondary">Tìm kiếm</button>
    </form>
    <a href="/renters/create" class="btn btn-primary">+ Thêm khách thuê</a>
</div>

<p class="meta">Tổng: <?= e((string) ($totalItems ?? 0)) ?> khách thuê | Trang <?= e((string) ($page ?? 1)) ?>/<?= e((string) ($totalPages ?? 1)) ?></p>

<table class="data-table">
    <thead>
        <tr>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=id&direction=asc">ID</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=name&direction=asc">Tên</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=email&direction=asc">Email</a></th>
            <th>SĐT</th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=status&direction=asc">Trạng thái</a></th>
            <th><a href="?q=<?= e($keyword ?? '') ?>&sort=created_at&direction=desc">Ngày tạo</a></th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($renters)): ?>
            <tr>
                <td colspan="7" class="empty">Không có dữ liệu.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($renters as $renter): ?>
                <tr>
                    <td><?= e((string) $renter['id']) ?></td>
                    <td><?= e($renter['name']) ?></td>
                    <td><?= e($renter['email']) ?></td>
                    <td><?= e($renter['phone'] ?? '') ?></td>
                    <td><span class="badge"><?= e($renter['status']) ?></span></td>
                    <td><?= e($renter['created_at']) ?></td>
                    <td class="actions">
                        <a href="/renters/edit?id=<?= e((string) $renter['id']) ?>" class="btn btn-sm">Sửa</a>
                        <form method="POST" action="/renters/delete" class="inline-form" onsubmit="return confirm('Xóa khách thuê này?');">
                            <input type="hidden" name="id" value="<?= e((string) $renter['id']) ?>">
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
            <a href="?q=<?= e($keyword ?? '') ?>&page=<?= e((string) $i) ?>&sort=<?= e($sort ?? 'created_at') ?>&direction=<?= e($direction ?? 'desc') ?>"
                class="<?= ($page ?? 1) === $i ? 'active' : '' ?>"><?= e((string) $i) ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>