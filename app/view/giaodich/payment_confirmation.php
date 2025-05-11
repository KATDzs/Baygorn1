<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /Baygorn1/app/view/auth/login.php");
    exit;
}
require_once '../../core/db_connection.php';
require_once '../../model/UserModel.php';
$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user']['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán - BayGorn1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
</head>
<body>
    <?php include '../layout/header.php'; ?>
    <div class="container py-5">
        <div class="confirmation-container mx-auto shadow rounded p-4 bg-light" style="max-width:500px;">
            <h2 class="mb-4 text-center">Điền thông tin để thanh toán</h2>
            <form method="post" action="/Baygorn1/index.php?controller=giaodich&action=processPayment">
                <div class="form-group mb-3">
                    <label>Họ và tên</label>
                    <input type="text" name="fullName" required value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" required value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label>Phương thức thanh toán</label>
                    <select name="paymentMethod" required class="form-select">
                        <option value="bank">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn-buy btn btn-danger w-100 mb-3">XÁC NHẬN THANH TOÁN</button>
            </form>
            <a href="/Baygorn1/" class="btn-preorder btn btn-outline-secondary w-100">Quay lại trang chủ</a>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>