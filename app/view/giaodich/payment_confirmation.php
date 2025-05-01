<?php
session_start();

// Kiểm tra xem có thông tin giao dịch trong session không
if (!isset($_SESSION['transaction'])) {
    header("Location: giaodich.php");
    exit();
}

$transaction = $_SESSION['transaction'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .confirmation-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .transaction-details {
            margin: 20px 0;
        }
        .transaction-details p {
            margin: 10px 0;
        }
        .btn-back {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Thanh toán thành công!</h1>
    
    <div class="confirmation-container">
        <h2>Thông tin giao dịch</h2>
        <div class="transaction-details">
            <p><strong>Tên game:</strong> <?php echo htmlspecialchars($transaction['gameName']); ?></p>
            <p><strong>Giá:</strong> <?php echo htmlspecialchars($transaction['gamePrice']); ?></p>
            <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($transaction['fullName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($transaction['email']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($transaction['phone']); ?></p>
            <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($transaction['paymentMethod']); ?></p>
            <p><strong>Thời gian giao dịch:</strong> <?php echo htmlspecialchars($transaction['transactionDate']); ?></p>
        </div>
        
        <p>Cảm ơn bạn đã mua hàng! Chúng tôi sẽ gửi thông tin chi tiết về game và hướng dẫn cài đặt qua email của bạn.</p>
        
        <a href="giaodich.php" class="btn-back">Quay lại trang chủ</a>
    </div>
</body>
</html> 