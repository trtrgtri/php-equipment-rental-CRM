<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<form method="POST" action="/renters/update" class="form-card">
    <input type="hidden" name="id" value="<?= e((string) $renter['id']) ?>">

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="name">Tên khách thuê *</label>
        <input type="text" id="name" name="name" value="<?= e($renter['name'] ?? '') ?>">
        <?php if (!empty($errors['name'])): ?>
            <span class="field-error"><?= e($errors['name']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= e($renter['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?>
            <span class="field-error"><?= e($errors['email']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" name="phone" value="<?= e($renter['phone'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <?php foreach ($statuses as $status): ?>
                <option value="<?= e($status) ?>" <?= (($renter['status'] ?? '') === $status) ? 'selected' : '' ?>>
                    <?= e($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['status'])): ?>
            <span class="field-error"><?= e($errors['status']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="note">Ghi chú</label>
        <textarea id="note" name="note" rows="3"><?= e($renter['note'] ?? '') ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="/renters" class="btn btn-secondary">Hủy</a>
    </div>
</form>
