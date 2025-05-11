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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán - BayGorn1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
</head>
<body>
    <?php include '../layout/header.php'; ?>
    <div class="container py-5">
        <?php if ($game): ?>
            <div class="game-detail row g-4 align-items-center mb-4">
                <div class="col-12 col-md-5 text-center">
                    <img src="/Baygorn1/asset/img/<?php echo $game['image_url']; ?>" alt="<?php echo $game['title']; ?>" class="game-image img-fluid rounded shadow">
                </div>
                <div class="game-info col-12 col-md-7">
                    <h2><?php echo $game['title']; ?></h2>
                    <p class="game-description"><?php echo $game['description']; ?></p>
                    <p class="game-price">Giá: <?php echo number_format($game['price'], 0, ',', '.'); ?> VNĐ</p>
                </div>
            </div>
            <div class="confirmation-container mx-auto shadow rounded p-4 bg-light" style="max-width:500px;">
                <h2 class="mb-4 text-center">Điền thông tin để thanh toán</h2>
                <form method="post" action="process_payment.php">
                    <input type="hidden" name="game_id" value="<?php echo $game['game_id']; ?>">
                    <div class="form-group mb-3">
                        <label>Họ và tên</label>
                        <input type="text" name="fullName" required value="<?php echo htmlspecialchars($userData['fullName'] ?? ''); ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" required value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>" class="form-control">
                    </div>
                    <div class="form-group mb-4">
                        <label>Phương thức thanh toán</label>
                        <select name="paymentMethod" required class="form-select">
                            <option value="bank">Chuyển khoản ngân hàng</option>
                            <option value="momo">Momo</option>
                            <option value="cod">Thanh toán khi nhận hàng</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-buy btn btn-danger w-100 mb-3">XÁC NHẬN THANH TOÁN</button>
                </form>
            </div>
        <?php else: ?>
            <p>Không tìm thấy game để thanh toán.</p>
        <?php endif; ?>
        <a href="/Baygorn1/" class="btn-preorder btn btn-secondary mt-3">Quay lại trang chủ</a>
    </div>
    <?php include '../layout/footer.php'; ?>
</body>
</html>