<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<div class="main-content">
    <div class="login-container">
        <h2 class="login-title">Đăng nhập</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?= $url('auth/login') ?>" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Đăng nhập</button>

            <div class="register-link">
                Chưa có tài khoản? <a href="<?= $url('auth/register') ?>">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div> 