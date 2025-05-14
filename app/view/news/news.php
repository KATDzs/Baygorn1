<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['news', 'footer', 'header'];
require_once ROOT_PATH . '/view/layout/header.php';
// Sample news data (replace with DB fetch in production)
$news = [
    [
        'title' => 'Minecraft Spring Update',
        'description' => 'Minecraft 1.21.5 "Spring to Life" chính thức ra mắt: Thế giới sống động hơn bao giờ hết!',
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
<main class="news-main-container">
    <section class="news-hero">
        <h1 class="news-title-main">Tin Tức Game Mới Nhất</h1>
        <p class="news-desc-main">Cập nhật nhanh các sự kiện, bản vá, và xu hướng hot nhất trong thế giới game!</p>
    </section>
    <section class="news-list-section">
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
            <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card">
                <div class="news-card-img-wrap">
                    <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy" class="news-card-img">
                </div>
                <div class="news-card-content">
                    <h2 class="news-card-title"><?= htmlspecialchars($item['title']) ?></h2>
                    <p class="news-card-desc"><?= htmlspecialchars($item['description']) ?></p>
                    <span class="news-card-link">Xem chi tiết &rarr;</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<script>
// Đảm bảo nội dung không bị menu che: tự động padding-top đúng chiều cao header
function adjustNewsPadding() {
  var header = document.querySelector('.navbar');
  var main = document.querySelector('.news-main-container, .news-detail-main-container, main');
  if (header && main) {
    var headerHeight = header.offsetHeight;
    // Chỉ cộng thêm 8px cho mobile, 16px cho tablet, 24px cho desktop để tránh bị cách quá xa
    var extra = 0;
    if (window.innerWidth <= 700) extra = 8;
    else if (window.innerWidth <= 1024) extra = 16;
    else extra = 24;
    main.style.paddingTop = (headerHeight + extra) + 'px';
  }
}
window.addEventListener('DOMContentLoaded', adjustNewsPadding);
window.addEventListener('resize', adjustNewsPadding);
</script>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>