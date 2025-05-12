<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['news', 'footer', 'header'];
require_once ROOT_PATH . '/view/layout/header.php';
?>
<body>
    <?php
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
    <main class="tin-tuc container">
        <h2 class="text-center mb-4">Tin tức</h2>

        <div class="slider-wrapper row flex-nowrap flex-md-wrap overflow-auto overflow-md-visible px-0 px-md-3">
            <button class="nav-button prev-button col-auto align-self-center" onclick="scrollSlider(-1)" aria-label="Previous slide">❮</button>
            <div class="slider-container d-flex flex-row flex-md-row gap-3 gap-md-4 col p-0" id="slider">
                <?php foreach ($news as $item): ?>
                    <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card card border-0 shadow-sm mb-3 mb-md-0" style="min-width: 260px; max-width: 320px; flex: 0 0 80vw; flex-basis: 80vw; flex-grow: 0; flex-shrink: 0;">
                        <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy" class="card-img-top img-fluid rounded-top">
                        <div class="news-content card-body p-3">
                            <h3 class="card-title h6 mb-2 text-truncate"><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="card-text small text-secondary mb-0 text-truncate" style="max-height: 2.8em; overflow: hidden;"><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <button class="nav-button next-button col-auto align-self-center" onclick="scrollSlider(1)" aria-label="Next slide">❯</button>
        </div>
        
        <div class="dots d-flex justify-content-center mt-3" id="dots" role="tablist">
            <?php foreach ($news as $index => $item): ?>
                <button class="dot <?= $index === 0 ? 'active' : '' ?> mx-1" onclick="goToSlide(<?= $index ?>)" role="tab" aria-label="Go to slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
    </main>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>
<script src="/Baygorn1/asset/js/news.js"></script>
</body>
</html>