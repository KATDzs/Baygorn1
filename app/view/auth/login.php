<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/auth.css">
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="main-content container py-5">
    <div class="login-container mx-auto shadow rounded p-4 bg-dark text-light" style="max-width:400px;">
        <h2 class="login-title mb-4 text-center">Đăng nhập</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?= $url('auth/login') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            
            <div class="form-group mb-3">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>

            <div class="form-group text-end mb-3">
                <a href="/Baygorn1/index.php?url=auth/forgot-password" class="forgot-password-link">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="login-btn btn btn-danger w-100 mb-3">Đăng nhập</button>

            <div class="register-link text-center">
                Chưa có tài khoản? <a href="/Baygorn1/index.php?url=auth/register">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>