<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <nav class="navbar">
        <div class="left">
            <div class="logo">
                <a href="../home.html">
                    <img src="logo.png" style="height: fit-content;">
                </a>
            </div>

            <ul class="menu">
                <li><a href="../home.html">Home</a></li>
                <li><a href="../about.html">About</a></li>
                <li><a href="../news.html">News</a></li>
                <li><a href="../games.html">Games</a></li>
            </ul>
        </div>

        <div class="account">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Hiển thị tên người dùng + nút logout -->
                <span style="color: white; margin-right: 10px;">
                    Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </span>
                <form action="../login/logout.php" method="POST" style="display:inline;">
                    <button type="submit" class="Login">Logout</button>
                </form>
            <?php else: ?>
                <!-- Nếu chưa đăng nhập -->
                <a href="../login/login.php"><button class="Login">Login</button></a>
                <a href="../login/register.php"><button class="Register">Register</button></a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>
