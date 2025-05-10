<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Baygorn Games</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/auth.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="main-content">
        <div class="register-container">
            <h2 class="register-title">Đăng ký tài khoản</h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php error_log("CSRF Token in Form: " . ($csrf_token ?? 'null')); ?>

            <form action="/Baygorn1/index.php?url=auth/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="form-group">
                    <label for="full_name">Họ và tên</label>
                    <input type="text" id="full_name" name="full_name" required minlength="2" maxlength="100">
                    <div class="password-requirements">Họ và tên phải từ 2-100 ký tự</div>
                </div>
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" required minlength="3" maxlength="20">
                    <div class="password-requirements">Tên đăng nhập phải từ 3-20 ký tự</div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <div class="password-requirements">Mật khẩu phải có ít nhất 6 ký tự</div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="register-btn">Đăng ký</button>

                <div class="login-link">
                    Đã có tài khoản? <a href="/Baygorn1/index.php?url=auth/login">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script src="/Baygorn1/asset/js/auth-register.js"></script>
</body>
</html>