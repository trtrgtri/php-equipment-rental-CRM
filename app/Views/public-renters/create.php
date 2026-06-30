<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<form method="POST" action="/public-renters" class="form-card">
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="name">Họ tên *</label>
        <input type="text" id="name" name="name" value="<?= old('name', $old) ?>">
        <?php if (!empty($errors['name'])): ?>
            <span class="field-error"><?= e($errors['name']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= old('email', $old) ?>">
        <?php if (!empty($errors['email'])): ?>
            <span class="field-error"><?= e($errors['email']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="phone">Số điện thoại *</label>
        <input type="text" id="phone" name="phone" value="<?= old('phone', $old) ?>">
        <?php if (!empty($errors['phone'])): ?>
            <span class="field-error"><?= e($errors['phone']) ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="note">Ghi chú thiết bị cần thuê</label>
        <textarea id="note" name="note" rows="3"><?= old('note', $old) ?></textarea>
    </div>

    <!-- Honeypot field - hidden from users -->
    <div class="hp-field" aria-hidden="true">
        <label for="website">Website</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <button type="submit" class="btn btn-primary">Gửi đăng ký</button>
</form>

<p class="hint">Form có honeypot và rate limit 5 giây/lần theo session.</p>
