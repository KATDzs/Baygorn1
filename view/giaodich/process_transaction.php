<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form
    $gameName = $_POST['gameName'];
    $gamePrice = $_POST['gamePrice'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $paymentMethod = $_POST['paymentMethod'];

    // Validate dữ liệu
    $errors = [];
    
    if (empty($fullName)) {
        $errors[] = "Vui lòng nhập họ và tên";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Vui lòng nhập email hợp lệ";
    }
    
    if (empty($phone) || !preg_match("/^[0-9]{10,11}$/", $phone)) {
        $errors[] = "Vui lòng nhập số điện thoại hợp lệ";
    }
    
    if (empty($paymentMethod)) {
        $errors[] = "Vui lòng chọn phương thức thanh toán";
    }

    if (empty($errors)) {
        // Lưu thông tin giao dịch vào session
        $_SESSION['transaction'] = [
            'gameName' => $gameName,
            'gamePrice' => $gamePrice,
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'paymentMethod' => $paymentMethod,
            'transactionDate' => date('Y-m-d H:i:s')
        ];

        // Chuyển hướng đến trang xác nhận thanh toán
        header("Location: payment_confirmation.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận giao dịch</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .transaction-details {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Xác nhận giao dịch</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="giaodich.php">Quay lại trang giao dịch</a>
    <?php endif; ?>
</body>
</html> 