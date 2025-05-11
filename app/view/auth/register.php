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
    <div class="main-content container py-5">
        <div class="register-container mx-auto shadow rounded p-4 bg-dark text-light" style="max-width:400px;">
            <h2 class="register-title mb-4 text-center">Đăng ký tài khoản</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="/Baygorn1/index.php?url=auth/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="form-group mb-3">
                    <label for="full_name">Họ và tên</label>
                    <input type="text" id="full_name" name="full_name" required minlength="2" maxlength="100" class="form-control">
                    <div class="form-text">Họ và tên phải từ 2-100 ký tự</div>
                </div>
                <div class="form-group mb-3">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" required minlength="3" maxlength="20" class="form-control">
                    <div class="form-text">Tên đăng nhập phải từ 3-20 ký tự</div>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required minlength="6" class="form-control">
                    <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                </div>
                <div class="form-group mb-4">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="form-control">
                </div>
                <button type="submit" class="register-btn btn btn-danger w-100 mb-3">Đăng ký</button>
                <div class="login-link text-center">
                    Đã có tài khoản? <a href="/Baygorn1/index.php?url=auth/login">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>