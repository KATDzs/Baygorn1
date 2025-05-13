<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['auth', 'header', 'footer'];
require_once ROOT_PATH . '/view/layout/header.php';
?>
<div class="main-content">
    <div class="login-container">
        <h2 class="login-title">Đăng nhập</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="<?= $url('auth/login') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group text-end">
                <a href="/Baygorn1/index.php?url=auth/forgot-password" class="forgot-password-link">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="login-btn">Đăng nhập</button>
            <div class="register-link">
                Chưa có tài khoản? <a href="/Baygorn1/index.php?url=auth/register">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>