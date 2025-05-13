<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['auth', 'header', 'footer'];
require_once ROOT_PATH . '/view/layout/header.php';
?>
<div class="main-content">
    <div class="login-container">
        <h2 class="login-title">Đăng ký tài khoản</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="/Baygorn1/index.php?url=auth/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required minlength="2" maxlength="100">
                <div class="password-requirements">Họ và tên phải từ 2-100 ký tự</div>
            </div>
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-control" required minlength="3" maxlength="20">
                <div class="password-requirements">Tên đăng nhập phải từ 3-20 ký tự</div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="6">
                <div class="password-requirements">Mật khẩu phải có ít nhất 6 ký tự</div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="login-btn">Đăng ký</button>
            <div class="register-link">
                Đã có tài khoản? <a href="/Baygorn1/index.php?url=auth/login">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>
<script src="/Baygorn1/asset/js/auth-register.js"></script>