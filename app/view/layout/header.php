<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Shop Game' ?></title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= $asset('css/styles.css') ?>">
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link rel="stylesheet" href="<?= $asset('css/' . $css . '.css') ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $asset('img/logo.png') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="left">
            <div class="logo">
                <a href="<?= $url('') ?>">
                    <img src="<?= $asset('img/logo.png') ?>" alt="Logo">
                </a>
            </div>
            <ul class="menu">
                <li><a href="<?= $url('') ?>">Home</a></li>
                <li><a href="<?= $url('shopgame') ?>">Shop Game</a></li>
                <li><a href="<?= $url('news') ?>">News</a></li>
                <li><a href="<?= $url('about') ?>">About</a></li>
            </ul>
        </div>
        <div class="account">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= $url('cart') ?>" class="menu-link">Cart</a>
                <a href="<?= $url('history') ?>" class="menu-link">History</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?= $url('admin') ?>" class="menu-link">Admin</a>
                <?php endif; ?>
                <a href="<?= $url('auth/logout') ?>" class="menu-link">Logout</a>
            <?php else: ?>
                <a href="<?= $url('auth/login') ?>" class="Login">Login</a>
                <a href="<?= $url('auth/register') ?>" class="Register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <main>
</body>
</html>