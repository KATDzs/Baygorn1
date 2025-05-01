<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BayGorn1 - Tin Tức</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/news.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
    }

    // Set page title and CSS files
    $title = 'BayGorn1 - Tin Tức';
    $css_files = ['news', 'footer'];

    require_once ROOT_PATH . '/view/layout/header.php';

    $news = [
        [
            'title' => 'Minecraft Spring Update',
            'description' => '​Minecraft 1.21.5 "Spring to Life" chính thức ra mắt: Thế giới sống động hơn bao giờ hết!​',
            'img' => '/Baygorn1/asset/img/minecraftupdate.jpg',
            'link' => '/Baygorn1/news/news1'
        ],
        [
            'title' => 'Roblox Update 2025',
            'description' => 'Cập nhật và tối ưu các tính năng cùng nhiều phần quà hấp dẫn đang đợi bạn tới chơi!',
            'img' => '/Baygorn1/asset/img/brainrotevolution.webp',
            'link' => '/Baygorn1/news/news2'
        ],
        [
            'title' => 'ĐTCL 14.2: Cân Bằng Meta',
            'description' => 'Bản cập nhật 14.2 mang tính cân bằng cao, cùng sự tối ưu hệ thống mới cho game thủ.',
            'img' => '/Baygorn1/asset/img/tftupdate.avif',
            'link' => '/Baygorn1/news/news3'
        ],
        [
            'title' => 'Tin tức 4',
            'description' => 'Mô tả ngắn gọn về tin tức 4...',
            'img' => '/Baygorn1/asset/img/news/news4.jpg',
            'link' => '/Baygorn1/news/news4'
        ],
        [
            'title' => 'Tin tức 5',
            'description' => 'Mô tả ngắn gọn về tin tức 5...',
            'img' => '/Baygorn1/asset/img/news/news5.jpg',
            'link' => '/Baygorn1/news/news5'
        ]
    ];
    ?>

    <section class="tin-tuc">
        <h2>Tin tức</h2>

        <button class="nav-button prev-button" onclick="scrollSlider(-1)">❮</button>
        <button class="nav-button next-button" onclick="scrollSlider(1)">❯</button>
        <div class="slider-wrapper">
            <div class="slider-container" id="slider">
                <?php foreach ($news as $item): ?>
                    <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card">
                        <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="news-content">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="dots" id="dots">
            <?php foreach ($news as $index => $item): ?>
                <span class="dot <?= $index === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $index ?>)"></span>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>
    <script src="/Baygorn1/asset/js/news.js"></script>
</body>
</html>