<?php
session_start(); // Khởi tạo session
session_unset(); // Hủy tất cả các session
session_destroy(); // Hủy session
header("Location: header.php"); // Chuyển hướng về trang chủ sau khi đăng xuất
exit();
?>
