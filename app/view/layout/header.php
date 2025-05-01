<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Shop Game' ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="/Baygorn1/asset/css/styles.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link rel="stylesheet" href="/Baygorn1/asset/css/<?= $css ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/Baygorn1/asset/img/logo.png">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="left">
            <div class="logo">
                <a href="/Baygorn1">
                    <img src="/Baygorn1/asset/img/logo.png" alt="Logo">
                </a>
            </div>
            <ul class="menu">
                <li><a href="/Baygorn1" class="menu-link">Home</a></li>
                <li><a href="/Baygorn1/game" class="menu-link">Game</a></li>
                <li><a href="/Baygorn1/news" class="menu-link">News</a></li>
                <li><a href="/Baygorn1/about" class="menu-link">About</a></li>
            </ul>
        </div>
        <div class="account">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/Baygorn1/cart" class="menu-link">Cart</a>
                <a href="/Baygorn1/history" class="menu-link">History</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/Baygorn1/admin" class="menu-link">Admin</a>
                <?php endif; ?>
                <a href="/Baygorn1/auth/logout" class="menu-link">Logout</a>
            <?php else: ?>
                <a href="/Baygorn1/auth/login" class="Login">Login</a>
                <a href="/Baygorn1/auth/register" class="Register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <main>
    <!-- Content will be injected here -->
</body>
</html>