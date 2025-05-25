<?php
// Đã có session_start ở controller, không cần gọi lại ở đây
require_once APP_ROOT . '/app/view/helpers.php';
global $conn;

$isLoggedIn = isset($_SESSION['user_id']);
$userData = [
    'fullName' => '',
    'email' => '',
    'phone' => ''
];

// Nếu đã đăng nhập, lấy thông tin user từ database
if ($isLoggedIn) {
    require_once BASE_PATH . '/app/model/UserModel.php';
    $userModel = new UserModel($conn);
    $user = $userModel->getUserById($_SESSION['user_id']);
    if ($user) {
        $userData['fullName'] = $user['full_name'] ?? '';
        $userData['email'] = $user['email'] ?? '';
        $userData['phone'] = $user['phone'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán - BayGorn1</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
</head>
<body>
    <?php include BASE_PATH . '/app/view/layout/header.php'; ?>
    <div class="container">
        <?php if (isset(
$error) && $error): ?>
            <div style="color:red;font-weight:bold;margin-bottom:16px;">Lỗi: <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($cartItems)): ?>
            <h2>Thông tin đơn hàng</h2>
            <div class="cart-list">
                <?php foreach ($cartItems as $item): ?>
                    <div class="game-detail" style="display:flex;align-items:center;margin-bottom:18px;">
                        <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="game-image" style="width:100px;height:100px;object-fit:cover;margin-right:18px;">
                        <div class="game-info">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="game-description">Số lượng: <?php echo (int)$item['quantity']; ?></p>
                            <p class="game-price">Giá: <?php echo format_price($item['price']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="cart-total-modern" style="font-size:1.2rem;font-weight:bold;margin-bottom:18px;">Tổng cộng: <span class="cart-total-amount"><?php echo format_price($total); ?></span></div>
            </div>
            <div class="confirmation-container">
                <h2>Điền thông tin để thanh toán</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="fullName" required value="<?php echo htmlspecialchars($userData['fullName']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($userData['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" required value="<?php echo htmlspecialchars($userData['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Phương thức thanh toán</label>
                        <select name="paymentMethod" required>
                            <option value="bank">Chuyển khoản ngân hàng</option>
                            <option value="momo">Momo</option>
                            <option value="cod">Thanh toán khi nhận hàng</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-buy">XÁC NHẬN THANH TOÁN</button>
                </form>
            </div>
        <?php else: ?>
            <p>Giỏ hàng của bạn đang trống.</p>
        <?php endif; ?>
        <a href="/Baygorn1/" class="btn-preorder">Quay lại trang chủ</a>
    </div>
    <?php include BASE_PATH . '/app/view/layout/footer.php'; ?>
</body>
</html>