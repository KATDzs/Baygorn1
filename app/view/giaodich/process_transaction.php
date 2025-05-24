<?php
session_start();
require_once '../../Controller/GiaoDichController.php';
$giaoDichController = new GiaoDichController();
$game_id = $_GET['id'] ?? 0;
$game = $giaoDichController->getGameDetail($game_id);

$isLoggedIn = isset($_SESSION['user']);
$userData = [
    'fullName' => '',
    'email' => '',
    'phone' => ''
];

// Nếu đã đăng nhập, lấy thông tin user từ database
if ($isLoggedIn) {
    require_once '../../model/UserModel.php';
    $userModel = new UserModel();
    // Giả sử $_SESSION['user'] chứa user_id
    $user = $userModel->getUserById($_SESSION['user']['user_id']);
    if ($user) {
        $userData['fullName'] = $user['full_name'];
        $userData['email'] = $user['email'];
        $userData['phone'] = $user['phone'];
    }
}
require_once APP_ROOT . '/app/view/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán - BayGorn1</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
</head>
<body>
    <?php include '../layout/header.php'; ?>
    <div class="container">
        <?php if ($game): ?>
            <div class="game-detail">
                <img src="/Baygorn1/asset/img/<?php echo $game['image_url']; ?>" alt="<?php echo $game['title']; ?>" class="game-image">
                <div class="game-info">
                    <h2><?php echo $game['title']; ?></h2>
                    <p class="game-description"><?php echo $game['description']; ?></p>
                    <p class="game-price">Giá: <?php echo format_price($game['price']); ?></p>
                </div>
            </div>
            <div class="confirmation-container">
                <h2>Điền thông tin để thanh toán</h2>
                <form method="post" action="process_payment.php">
                    <input type="hidden" name="game_id" value="<?php echo $game['game_id']; ?>">
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
            <p>Không tìm thấy game để thanh toán.</p>
        <?php endif; ?>
        <a href="/Baygorn1/" class="btn-preorder">Quay lại trang chủ</a>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>