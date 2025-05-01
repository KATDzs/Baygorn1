<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

$title = 'BayGorn1 - Chi tiết tin tức';
$css_files = ['news-detail'];

require_once ROOT_PATH . '/view/layout/header.php';
?>

<div class="news-content">
    <article class="news-article">
        <div class="news-image-wrapper">
            <img src="/Baygorn1/asset/img/news/news4.jpg" alt="Tin tức 4">
        </div>
        <h1>Tin tức 4</h1>
        <div class="news-date">Ngày đăng: 20/03/2024</div>
        <p class="news-intro">
            Nội dung tin tức 4...
        </p>
        <div class="news-section">
            <h2>Chi tiết</h2>
            <p>Nội dung chi tiết tin tức 4...</p>
        </div>
    </article>
</div>

<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?> 