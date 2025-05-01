<?php
session_start();

// Kiểm tra xem có thông tin giao dịch trong session không
if (!isset($_SESSION['transaction'])) {
    header("Location: " . BASE_URL . "/giaodich");
    exit();
}

$transaction = $_SESSION['transaction'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán thành công - BayGorn1</title>
    <link rel="stylesheet" href="/asset/css/giaodich.css">
    <link rel="stylesheet" href="/asset/css/header.css">
    <link rel="stylesheet" href="/asset/css/footer.css">
</head>
<body>
    <?php include APP_ROOT . '/app/view/layout/header.php'; ?>

    <div class="container">
        <div class="confirmation-container">
            <h1>Thanh toán thành công!</h1>
            
            <div class="transaction-details">
                <h2>Thông tin giao dịch</h2>
                <p><strong>Tên game:</strong> <?php echo htmlspecialchars($transaction['gameName']); ?></p>
                <p><strong>Giá:</strong> <?php echo htmlspecialchars($transaction['gamePrice']); ?></p>
                <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($transaction['fullName']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($transaction['email']); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($transaction['phone']); ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($transaction['paymentMethod']); ?></p>
                <p><strong>Thời gian giao dịch:</strong> <?php echo htmlspecialchars($transaction['transactionDate']); ?></p>
            </div>
            
            <p class="thank-you-message">Cảm ơn bạn đã mua hàng! Chúng tôi sẽ gửi thông tin chi tiết về game và hướng dẫn cài đặt qua email của bạn.</p>
            
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay lại trang chủ</a>
        </div>
    </div>

    <?php include APP_ROOT . '/app/view/layout/footer.php'; ?>
</body>
</html> 