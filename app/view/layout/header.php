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
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li class="admin-dropdown-parent" style="position:relative;">
                        <a href="/Baygorn1/index.php?url=admin" class="menu-link" style="color:purple;font-weight:bold;">Admin</a>
                        <ul class="admin-navbar-dropdown" style="display:none; position:absolute; background:#232a36; border-radius:10px; min-width:200px; top:100%; left:0; box-shadow:0 2px 12px rgba(0,0,0,0.12); z-index:1000;">
                            <li><a href="/Baygorn1/index.php?url=admin" class="dropdown-link">Dashboard</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/users" class="dropdown-link">Quản lý người dùng</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/games" class="dropdown-link">Quản lý game</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/addGame" class="dropdown-link">Thêm game mới</a></li>
                        </ul>
                    </li>
                    <style>
                    .admin-navbar-dropdown li { list-style:none; }
                    .admin-navbar-dropdown .dropdown-link {
                        display:block; padding:12px 18px; color:#7ed6df; text-decoration:none; font-weight:500;
                        border-bottom:1px solid #232a36; transition:background 0.2s, color 0.2s;
                    }
                    .admin-navbar-dropdown .dropdown-link:hover {
                        background:#202634; color:#fff;
                    }
                    .admin-navbar-dropdown li:last-child .dropdown-link { border-bottom:none; }
                    </style>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var parent = document.querySelector('.admin-dropdown-parent');
                        var dropdown = parent.querySelector('.admin-navbar-dropdown');
                        parent.addEventListener('mouseenter', function() {
                            dropdown.style.display = 'block';
                        });
                        parent.addEventListener('mouseleave', function() {
                            dropdown.style.display = 'none';
                        });
                    });
                    </script>
                <?php endif; ?>
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
                    <a href="/Baygorn1/index.php?url=admin" class="dropdown-item" id="admin-link">Admin</a>
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