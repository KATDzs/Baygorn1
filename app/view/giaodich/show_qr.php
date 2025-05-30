<?php
session_start();
if (!isset($_SESSION['pending_payment']) || !isset($_GET['id'])) {
    header('Location: /Baygorn1/');
    exit;
}
require_once '../../core/db_connection.php';
require_once '../../model/GameModel.php';
$gameModel = new GameModel($conn);
$game = $gameModel->getGameById($_GET['id']);
if (!$game) {
    header('Location: /Baygorn1/');
    exit;
}
$amount = $game['price'];
$gameTitle = $game['title'];
// Thông tin QR mẫu (có thể thay bằng QR thật của ngân hàng/momo)
$qr_img = '/Baygorn1/asset/img/qr-demo.png'; // Đặt ảnh QR code thật ở đây
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quét mã QR để thanh toán</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
</head>
<body>
<?php include '../layout/header.php'; ?>
<div class="container">
    <div class="qr-payment-container">
        <h2>Quét mã QR để thanh toán</h2>
        <p>Game: <b><?= htmlspecialchars($gameTitle) ?></b></p>
        <p>Số tiền: <b style="color:#e60012;"><?= number_format($amount, 0, ',', '.') ?>đ</b></p>
        <img src="<?= $qr_img ?>" alt="QR Code" style="max-width:300px;width:100%;margin:24px auto;display:block;">
        <p style="margin-top:16px;">Sau khi thanh toán, vui lòng liên hệ admin để xác nhận hoặc chờ hệ thống tự động cập nhật.</p>
        <a href="/Baygorn1/" class="btn-preorder">Quay lại trang chủ</a>
    </div>
</div>
<?php include '../layout/footer.php'; ?>
</body>
</html>
