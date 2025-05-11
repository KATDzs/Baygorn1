<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BayGorn1 - Tin Tức</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/auth.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $config['baseURL'] . 'auth/login');
    exit();
}

// Include header
include_once BASE_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="<?php echo $config['baseURL']; ?>asset/css/auth.css">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-card-header">
            <h3 class="text-center" style="color:#ff4d4f;">Thông tin cá nhân</h3>
        </div>
        <div class="auth-card-body">
            <div class="form-group mb-4">
                <label class="form-label">Tên đăng nhập</label>
                <div class="form-control" readonly><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Email</label>
                <div class="form-control" readonly><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Họ và tên</label>
                <div class="form-control" readonly><?php echo htmlspecialchars($user['full_name']); ?></div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once BASE_PATH . '/app/view/layout/footer.php';
?> 
</body>
</html>