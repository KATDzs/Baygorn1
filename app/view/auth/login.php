<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Baygorn Games</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/auth.css">
</head>
<body>
    <?php include 'view/layout/header.php'; ?>

    <div class="login-container">
        <h2 class="login-title">Đăng nhập</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="/Baygorn1/auth/login" method="POST">
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
                Chưa có tài khoản? <a href="/Baygorn1/auth/register">Đăng ký ngay</a>
            </div>
        </form>
    </div>

    <?php include 'view/layout/footer.php'; ?>
</body>
</html> 