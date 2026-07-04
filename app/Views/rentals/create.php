<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<form method="POST" action="/rentals/store" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="rental_code">Mã phiếu thuê *</label>
        <input type="text" id="rental_code" name="rental_code" value="<?= old('rental_code', $old) ?>" placeholder="VD: RNT-2026-0001">
        <?php if (!empty($errors['rental_code'])): ?>
            <span class="field-error"><?= e($errors['rental_code']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="renter_name">Tên khách thuê *</label>
        <input type="text" id="renter_name" name="renter_name" value="<?= old('renter_name', $old) ?>">
        <?php if (!empty($errors['renter_name'])): ?>
            <span class="field-error"><?= e($errors['renter_name']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="renter_email">Email khách thuê</label>
        <input type="email" id="renter_email" name="renter_email" value="<?= old('renter_email', $old) ?>">
        <?php if (!empty($errors['renter_email'])): ?>
            <span class="field-error"><?= e($errors['renter_email']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="equipment_name">Tên thiết bị *</label>
        <input type="text" id="equipment_name" name="equipment_name" value="<?= old('equipment_name', $old) ?>">
        <?php if (!empty($errors['equipment_name'])): ?>
            <span class="field-error"><?= e($errors['equipment_name']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="total_amount">Tổng tiền thuê (VNĐ) *</label>
        <input type="number" id="total_amount" name="total_amount" step="0.01" min="0" value="<?= old('total_amount', $old) ?>">
        <?php if (!empty($errors['total_amount'])): ?>
            <span class="field-error"><?= e($errors['total_amount']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <?php foreach ($statuses as $status): ?>
                <option value="<?= e($status) ?>" <?= (old('status', $old, 'pending') === $status) ? 'selected' : '' ?>>
                    <?= e($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['status'])): ?>
            <span class="field-error"><?= e($errors['status']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="/rentals" class="btn btn-secondary">Hủy</a>
    </div>
</form>