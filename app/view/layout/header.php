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
    
    <style>
        body {
            padding-top: 80px; /* Đảm bảo khoảng cách giữa menu và nội dung */
        }
    </style>
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

        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="header-avatar-menu">
            <div class="avatar" onclick="toggleDropdown()">
                <img src="/Baygorn1/asset/img/avatar.jpg" alt="User Avatar" class="avatar-img">
            </div>
            <div id="dropdown-menu" class="dropdown-menu">
                <a href="/Baygorn1/index.php?url=user/profile" class="dropdown-item">Profile</a>
                <a href="/Baygorn1/index.php?url=user/history" class="dropdown-item">History</a>
                <a href="/Baygorn1/index.php?url=cart" class="dropdown-item">Cart</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="/Baygorn1/index.php?url=admin" class="dropdown-item">Admin</a>
                <?php endif; ?>
                <a href="/Baygorn1/index.php?url=auth/logout" class="dropdown-item">Logout</a>
            </div>
        </div>
        <?php else: ?>
        <div class="header-avatar-menu">
            <a href="/Baygorn1/index.php?url=auth/login" class="menu-link Login">Login</a>
            <a href="/Baygorn1/index.php?url=auth/register" class="menu-link Register">Register</a>
        </div>
        <?php endif; ?>
    </nav>
   
    

    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown-menu');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown-menu');
        const avatar = document.querySelector('.avatar');
        if (!avatar.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    </script>
</body>
</html>