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

            <form action="/Baygorn1/auth/register" method="POST">
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
                    Đã có tài khoản? <a href="/Baygorn1/auth/login">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script>
        // Kiểm tra mật khẩu khớp nhau
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const form = document.querySelector('form');

        form.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
            }
        });
    </script>
</body>
</html> 