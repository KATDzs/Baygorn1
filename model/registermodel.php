<?php
session_start();
include('db_connection.php'); // Kết nối tới cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form đăng ký
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra nếu người dùng đã tồn tại
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists.";
    } else {
        // Nếu không tồn tại, thêm người dùng vào cơ sở dữ liệu
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        echo "Registration successful!";
        header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
    }
}
?>