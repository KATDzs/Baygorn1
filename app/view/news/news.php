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
            'img' => '/Baygorn1/asset/img/games/minecraftupdate.jpg',
            'link' => '/Baygorn1/news/news1'
        ],
        [
            'title' => 'Roblox Brainrot Evolution Update',
            'description' => 'Cập nhật và tối ưu các tính năng cùng nhiều phần quà hấp dẫn đang đợi bạn tới chơi!',
            'img' => '/Baygorn1/asset/img/games/brainrotevolution.webp',
            'link' => '/Baygorn1/news/news2'
        ],
        [
            'title' => 'ĐTCL 14.2: Cân Bằng Meta, Hack bị điều chỉnh và buff tướng reroll',
            'description' => 'Bản cập nhật 14.2 mang tính cân bằng cao, cùng sự tối ưu hệ thống mới cho game thủ.',
            'img' => '/Baygorn1/asset/img/games/tftupdate.avif',
            'link' => '/Baygorn1/news/news3'
        ],
        [
            'title' => 'Palworld bùng nổ! Game sinh tồn bắt quái vật đang làm mưa làm gió toàn cầu',
            'description' => 'Tựa game sinh tồn thế giới mở Palworld, kết hợp giữa bắt quái vật và xây dựng căn cứ, đang gây bão toàn cầu với lối chơi sáng tạo!',
            'img' => '/Baygorn1/asset/img/games/game_palworld.jpeg',
            'link' => '/Baygorn1/news/news4'
        ],
        [
            'title' => 'LEGION - Biểu tượng của kỷ nguyên đen tối',
            'description' => 'LEGION tồn tại trong một thế giới nơi các thành phố đã bị nuốt chửng bởi mạng lưới siêu máy tính. Các tập đoàn khổng lồ kiểm soát từng nhịp thở của nhân loại.',
            'img' => '/Baygorn1/asset/img/games/game_i_am_legion.jpg',
            'link' => '/Baygorn1/news/news5'
        ],
    ];
    ?>

    <main class="tin-tuc container my-5">
        <h2 class="mb-4">Tin tức</h2>

        <div class="slider-wrapper row align-items-center mb-4">
            <div class="col-auto">
                <button class="nav-button prev-button btn btn-light" onclick="scrollSlider(-1)" aria-label="Previous slide">&#x276e;</button>
            </div>
            <div class="slider-container col px-0" id="slider">
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <?php foreach ($news as $item): ?>
                        <div class="col">
                            <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card card h-100">
                                <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy" class="card-img-top">
                                <div class="news-content card-body">
                                    <h3 class="card-title h5"><?= htmlspecialchars($item['title']) ?></h3>
                                    <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-auto">
                <button class="nav-button next-button btn btn-light" onclick="scrollSlider(1)" aria-label="Next slide">&#x276f;</button>
            </div>
        </div>
        
        <div class="dots d-flex justify-content-center gap-2 mb-4" id="dots" role="tablist">
            <?php foreach ($news as $index => $item): ?>
                <button class="dot btn btn-sm <?= $index === 0 ? 'active btn-danger' : 'btn-outline-secondary' ?>" onclick="goToSlide(<?= $index ?>)" role="tab" aria-label="Go to slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>
    <script src="/Baygorn1/asset/js/news.js"></script>
</body>
</html>