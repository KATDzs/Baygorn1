<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['about', 'header', 'footer'];
require_once ROOT_PATH . '/view/layout/header.php';
?>
<main class="about-page container-fluid px-0" style="padding-top:calc(var(--navbar-height,80px) + 24px);">
    <!-- Hero Section -->
    <section class="hero-section row justify-content-center align-items-center text-center mx-0">
        <div class="hero-content col-12 col-md-10 col-lg-8">
            <div class="about-logo-wrap mb-3 d-flex flex-column align-items-center justify-content-center">
                <div class="about-logo-bg">
                    <img src="/Baygorn1/asset/img/logo.png" alt="Logo" class="about-hero-logo mb-1">
                </div>
            </div>
            <h1 class="about-hero-title">Dự án 1 - Baygorn Game Portal</h1>
            <p class="about-hero-sub"><i class="fa-solid fa-user-astronaut"></i> by <b>Vũ Quốc Bảo</b> & <b>Trần Nguyên Đức Bảo</b></p>
            <p class="about-hero-desc"><i class="fa-solid fa-gamepad"></i> Website quản lý, giới thiệu, mua bán game, cập nhật tin tức và trải nghiệm cộng đồng cho sinh viên và game thủ Việt Nam.</p>
        </div>
    </section>
    <!-- About Project Section -->
    <section class="mission-section row justify-content-center mx-0">
        <div class="container col-12 col-md-10 col-lg-8">
            <h2><i class="fa-solid fa-circle-info"></i> Về dự án</h2>
            <p>Baygorn là dự án web PHP MVC hoàn chỉnh, mô phỏng một cổng thông tin game hiện đại với các tính năng:</p>
            <ul class="about-feature-list ps-3">
                <li><i class="fa-solid fa-user-shield"></i> Đăng nhập/Đăng ký, quản lý tài khoản, phân quyền Admin/User</li>
                <li><i class="fa-solid fa-list"></i> Trang chủ, danh mục game, chi tiết game, giỏ hàng, thanh toán</li>
                <li><i class="fa-solid fa-newspaper"></i> Trang tin tức game mới nhất, cập nhật sự kiện, xu hướng</li>
                <li><i class="fa-solid fa-gears"></i> Quản trị nội dung (game, user, tin tức) cho Admin</li>
                <li><i class="fa-solid fa-mobile-screen"></i> Responsive UI, tối ưu cho desktop, tablet, mobile</li>
            </ul>
        </div>
    </section>

    <!-- Technology Section -->
    <section class="values-section row justify-content-center mx-0">
        <div class="container col-12 col-md-10 col-lg-8">
            <h2><i class="fa-solid fa-microchip"></i> Công nghệ sử dụng</h2>
            <div class="values-grid row g-3 mt-3">
                <div class="value-card col-12 col-sm-6 col-lg-4"><i class="fa-brands fa-php tech-icon"></i><h3>PHP 8.x</h3><p>Ngôn ngữ backend, mô hình MVC</p></div>
                <div class="value-card col-12 col-sm-6 col-lg-4"><i class="fa-solid fa-database tech-icon"></i><h3>MySQL</h3><p>Lưu trữ dữ liệu người dùng, game, đơn hàng</p></div>
                <div class="value-card col-12 col-sm-6 col-lg-4"><i class="fa-brands fa-html5 tech-icon"></i><h3>HTML5/CSS3/JS</h3><p>Giao diện hiện đại, responsive, hiệu ứng động</p></div>
                <div class="value-card col-12 col-sm-6 col-lg-4"><i class="fa-brands fa-bootstrap tech-icon"></i><h3>Bootstrap 5</h3><p>Hỗ trợ layout mobile/tablet nhanh chóng</p></div>
                <div class="value-card col-12 col-sm-6 col-lg-4"><i class="fa-solid fa-server tech-icon"></i><h3>XAMPP</h3><p>Môi trường phát triển local, server Apache</p></div>
            </div>
        </div>
    </section>

    <!-- Members Section -->
    <section class="team-section row justify-content-center mx-0">
        <div class="container col-12 col-md-10 col-lg-8">
            <h2><i class="fa-solid fa-users"></i> Thành viên thực hiện</h2>
            <div class="stats row g-3 mt-3 justify-content-center">
                <div class="stat-item col-12 col-sm-6 col-md-5 col-lg-4 d-flex flex-column align-items-center">
                    <img src="/Baygorn1/asset/img/avatar.jpg" alt="Vũ Quốc Bảo" class="about-member-avatar mb-2">
                    <h3 class="about-member-name"><i class="fa-solid fa-user-tie"></i> Vũ Quốc Bảo</h3>
                    <p><i class="fa-solid fa-database"></i> Backend, Database, Logic</p>
                </div>
                <div class="stat-item col-12 col-sm-6 col-md-5 col-lg-4 d-flex flex-column align-items-center">
                    <img src="/Baygorn1/asset/img/avatar.jpg" alt="Trần Nguyên Đức Bảo" class="about-member-avatar mb-2">
                    <h3 class="about-member-name"><i class="fa-solid fa-user-pen"></i> Trần Nguyên Đức Bảo</h3>
                    <p><i class="fa-solid fa-laptop-code"></i> Frontend, UI/UX, Responsive</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section row justify-content-center mx-0 mb-5">
        <div class="container col-12 col-md-10 col-lg-8">
            <h2><i class="fa-solid fa-address-book"></i> Liên hệ & Thông tin</h2>
            <div class="contact-info row g-3">
                <div class="contact-item col-12 col-md-4 text-center">
                    <h3><i class="fa-solid fa-envelope"></i> Email</h3>
                    <p>vuquocbao@example.com<br>tranducbao@example.com</p>
                </div>
                <div class="contact-item col-12 col-md-4 text-center">
                    <h3><i class="fa-brands fa-github"></i> Github</h3>
                    <p><a href="https://github.com/" target="_blank" class="about-contact-link">github.com/</a></p>
                </div>
                <div class="contact-item col-12 col-md-4 text-center">
                    <h3><i class="fa-brands fa-facebook"></i> Facebook</h3>
                    <p><a href="https://facebook.com/" target="_blank" class="about-contact-link">facebook.com/</a></p>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    // Đảm bảo padding-top cho main không bị menu đè
    function adjustAboutPadding() {
        var header = document.querySelector('.navbar');
        var main = document.querySelector('.about-page');
        if (header && main) {
            var headerHeight = header.offsetHeight;
            var extra = 0;
            if (window.innerWidth <= 700) extra = 8;
            else if (window.innerWidth <= 1024) extra = 16;
            else extra = 24;
            main.style.paddingTop = (headerHeight + extra) + 'px';
        }
    }
    window.addEventListener('DOMContentLoaded', adjustAboutPadding);
    window.addEventListener('resize', adjustAboutPadding);
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>