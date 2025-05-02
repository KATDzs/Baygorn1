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
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
</head>
<body>
    <?php include '../layout/header.php'; ?>
    <div class="container">
        <div class="confirmation-container">
            <h2>Điền thông tin để thanh toán</h2>
            <form method="post" action="/Baygorn1/index.php?controller=giaodich&action=processPayment">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullName" required value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" required value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Phương thức thanh toán</label>
                    <select name="paymentMethod" required>
                        <option value="bank">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn-buy">XÁC NHẬN THANH TOÁN</button>
            </form>
        </div>
        <a href="/Baygorn1/" class="btn-preorder">Quay lại trang chủ</a>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>