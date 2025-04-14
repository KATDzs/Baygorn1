<?php
session_start();
include('db_connection.php'); // Kết nối tới cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form đăng nhập
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu có người dùng, lưu thông tin vào session
        $_SESSION['user_id'] = $username; // Lưu ID người dùng vào session
        header("Location: header.php"); // Chuyển hướng về trang chủ
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!-- HTML form đăng nhập -->
<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
