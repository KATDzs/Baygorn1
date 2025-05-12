<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Shop Game' ?></title>
    
    <!-- Bootstrap CSS chỉ áp dụng cho mobile/tablet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" media="(max-width: 1024px)">
    <!-- CSS ghi đè Bootstrap, đảm bảo load sau Bootstrap để ưu tiên -->
    <link rel="stylesheet" href="/Baygorn1/asset/css/styles.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/bootstrap-overwrite.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link rel="stylesheet" href="/Baygorn1/asset/css/<?= $css ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <link rel="stylesheet" href="/Baygorn1/asset/css/header-admin.css">
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
            <!-- Hamburger button for mobile/tablet -->
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="toggleMobileMenu()">
                <span class="navbar-toggler-icon" style="font-size:2rem;">&#9776;</span>
            </button>
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
                <?php endif; ?>
            </ul>
            <ul class="menu menu-mobile" id="mobileMenu" style="display:none;">
                <li><a href="/Baygorn1" class="menu-link">Home</a></li>
                <li><a href="/Baygorn1/game" class="menu-link">Game</a></li>
                <li><a href="/Baygorn1/news" class="menu-link">News</a></li>
                <li><a href="/Baygorn1/about" class="menu-link">About</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li class="admin-dropdown-parent-mobile" style="position:relative;">
                        <a href="#" class="menu-link" style="color:purple;font-weight:bold;">Admin &#9662;</a>
                        <ul id="adminMobileDropdown" class="admin-dropdown-mobile">
                            <li><a href="/Baygorn1/index.php?url=admin" class="menu-link" style="font-size:1rem;">Dashboard</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/users" class="menu-link" style="font-size:1rem;">Quản lý người dùng</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/games" class="menu-link" style="font-size:1rem;">Quản lý game</a></li>
                            <li><a href="/Baygorn1/index.php?url=admin/addGame" class="menu-link" style="font-size:1rem;">Thêm game mới</a></li>
                        </ul>
                    </li>
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

    // Toggle mobile menu
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const adminDropdown = document.getElementById('adminMobileDropdown');
        if (menu.style.display === 'flex') {
            menu.style.display = 'none';
            if(adminDropdown) adminDropdown.classList.remove('show');
        } else {
            menu.style.display = 'flex';
            if(adminDropdown) adminDropdown.classList.remove('show'); // luôn ẩn menu con admin khi mở burger
        }
    }

    // Đảm bảo menu admin mobile không bị đóng khi click vào
    (function(){
      const adminBtn = document.querySelector('.admin-dropdown-parent-mobile > .menu-link');
      const adminDropdown = document.getElementById('adminMobileDropdown');
      const mobileMenu = document.getElementById('mobileMenu');
      if(adminBtn && adminDropdown && mobileMenu) {
        adminDropdown.classList.remove('show');
        // Toggle menu con admin khi click vào Admin
        adminBtn.addEventListener('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          // Toggle show
          if(adminDropdown.classList.contains('show')) {
            adminDropdown.classList.remove('show');
          } else {
            adminDropdown.classList.add('show');
          }
          return false;
        });
        // Ngăn click vào menu con admin lan ra ngoài
        adminDropdown.addEventListener('click', function(e){
          e.stopPropagation();
        });
        // Đóng menu con admin khi click ra ngoài (không đóng burger)
        document.addEventListener('click', function(e){
          if(adminDropdown.classList.contains('show') && !adminDropdown.contains(e.target) && e.target !== adminBtn) {
            adminDropdown.classList.remove('show');
          }
        });
        // Đóng cả menu burger và menu con admin khi click vào link trong menu mobile (trừ nút Admin)
        mobileMenu.querySelectorAll('a.menu-link').forEach(function(link){
          if(link !== adminBtn) {
            link.addEventListener('click', function(ev){
              mobileMenu.style.display = 'none';
              adminDropdown.classList.remove('show');
            });
          }
        });
      }
    })();

    // Đóng menu mobile khi click ra ngoài (trừ khi click vào menu burger hoặc menu con admin hoặc nút Admin)
    document.addEventListener('mousedown', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const toggler = document.querySelector('.navbar-toggler');
        const adminDropdown = document.getElementById('adminMobileDropdown');
        const adminBtn = document.querySelector('.admin-dropdown-parent-mobile > .menu-link');
        if (mobileMenu && toggler && mobileMenu.style.display === 'flex') {
            if (
                !mobileMenu.contains(event.target) &&
                !toggler.contains(event.target) &&
                (!adminDropdown || !adminDropdown.contains(event.target)) &&
                event.target !== adminBtn
            ) {
                mobileMenu.style.display = 'none';
                if(adminDropdown) adminDropdown.classList.remove('show');
            }
        }
    }, true); // Sử dụng capture phase để ưu tiên bắt sự kiện trước các handler khác
    </script>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <script src="/Baygorn1/asset/js/header-admin.js"></script>
    <?php endif; ?>
</body>
</html>