<?php
// No need to require files or initialize models here since it's handled by the controller

// Define ROOT_PATH if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

// Check if variables are set
if (!isset($latestGames)) $latestGames = [];
if (!isset($latestNews)) $latestNews = [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang chủ Riot Games Việt Nam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Trang chủ chính thức của Riot Games Việt Nam">
  <link rel="stylesheet" href="<?= $asset('css/home.css') ?>">
  <link rel="stylesheet" href="<?= $asset('css/header.css') ?>">
  <link rel="stylesheet" href="<?= $asset('css/footer.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <main class="container-fluid px-0">
    <!-- Hero Section -->
    <section class="hero row gx-0">
      <div class="overlay"></div>
      <div class="hero-content col-12 col-md-10 mx-auto">
        <h1 class="hero-title">Chào mừng đến với Baygorn – Thế giới game trong tầm tay bạn!</h1>
        <p class="hero-subtitle">Khám phá, cập nhật và sở hữu những tựa game hot nhất ngay hôm nay!</p>
      </div>
    </section>

    <!-- News Section -->
    <section class="news-section container my-5">
      <div class="section-header row align-items-center mb-4">
        <div class="col">
          <h1 class="section-title">Chuyện gì đang xảy ra vậy?</h1>
        </div>
        <div class="col-auto">
          <a href="/Baygorn1/news" class="btn btn-danger view-all-btn d-flex align-items-center gap-2">
            Xem tất cả
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
              <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </div>
      <div class="news-grid row g-4">
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
        <!-- Main Left Block -->
        <div class="featured-news col-12 col-lg-8">
          <a href="<?php echo $news[0]['link']; ?>" class="news-link">
            <div class="news-image">
              <img src="<?php echo $news[0]['img']; ?>" alt="<?php echo htmlspecialchars($news[0]['title']); ?>" class="img-fluid rounded">
            </div>
            <div class="news-overlay">
              <div class="news-content">
                <h1><?php echo htmlspecialchars($news[0]['title']); ?></h1>
                <p class="news-intro"><?php echo htmlspecialchars($news[0]['description']); ?></p>
                <span class="news-tag">TIN TỨC</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Right Column -->
        <div class="news-list col-12 col-lg-4 d-flex flex-column gap-4">
          <?php for($i = 1; $i < count($news); $i++): ?>
            <a href="<?php echo $news[$i]['link']; ?>" class="news-item d-flex flex-row align-items-center bg-dark rounded-4 p-3 gap-3 shadow-sm h-100" style="min-height:110px;">
              <div class="news-item-image flex-shrink-0 rounded-3 overflow-hidden" style="width:110px; height:70px; background:#222; display:flex; align-items:center; justify-content:center;">
                <img src="<?php echo $news[$i]['img']; ?>" alt="<?php echo htmlspecialchars($news[$i]['title']); ?>" class="img-fluid w-100 h-100 object-fit-cover">
              </div>
              <div class="news-item-content flex-grow-1 d-flex flex-column justify-content-center">
                <div class="fw-bold text-white mb-1 text-truncate" style="font-size:1.1rem; max-width:220px;">
                  <?php echo htmlspecialchars($news[$i]['title']); ?>
                </div>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="news-item-tag text-secondary small fw-semibold">TIN TỨC</span>
                </div>
              </div>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    </section>

    <!-- Games Section -->
    <section class="games-section container my-5">
      <div class="content-wrapper">
        <div class="section-header row align-items-center mb-4">
          <div class="col">
            <h2 class="section-title">Trò chơi của chúng tôi</h2>
          </div>
          <div class="col-auto">
            <a href="/Baygorn1/game" class="btn btn-outline-danger view-all-btn d-flex align-items-center gap-2">
              Xem tất cả
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <div class="games-grid row g-4">
          <?php if (!empty($latestGames)): ?>
            <?php foreach($latestGames as $game): ?>
              <a href="/Baygorn1/app/view/game/game-detail.php?id=<?php echo htmlspecialchars($game['game_id']); ?>" class="game-card col-12 col-md-6 col-lg-4">
                <div class="game-image">
                  <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($game['image_url']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" class="img-fluid rounded">
                </div>
                <div class="game-overlay">
                  <div class="game-content">
                    <h3 class="game-title"><?php echo htmlspecialchars($game['title']); ?></h3>
                    <p class="game-description"><?php echo htmlspecialchars($game['description']); ?></p>
                    <span class="game-label">KHÁM PHÁ NGAY</span>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-games">Chưa có game nào.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <?php include ROOT_PATH . '/view/layout/footer.php'; ?>

  <script src="/Baygorn1/asset/js/home.js"></script>
</body>
</html>
