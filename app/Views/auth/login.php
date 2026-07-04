<h1><?= e($title) ?></h1>
<?php partial('flash'); ?>

<form method="POST" action="/login" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-error"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= old('email', $old) ?>">
    </div>

    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password">
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="remember" disabled>
            Remember me (demo — không lưu password vào cookie vì rủi ro bảo mật)
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>

<p class="hint">Tài khoản demo: admin@example.com / 123456</p>