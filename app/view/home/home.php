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
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="overlay"></div>
      <div class="hero-content">
        <h1 class="hero-title">Hãy ngước nhìn lên những Tinh Tú Thăng Hoa</h1>
        <p class="hero-subtitle">Kiếm tìm và chiến đấu cho huyền thoại đích thực của bạn từ ngày 17/04!</p>
      </div>
    </section>

    <!-- News Section -->
    <section class="news-section">
      <div class="section-header">
        <h1 class="section-title">Chuyện gì đang xảy ra vậy?</h1>
        <a href="/Baygorn1/news" class="view-all-btn">
          Xem tất cả
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>

      <div class="news-grid">
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
        <div class="featured-news">
          <a href="<?php echo $news[0]['link']; ?>" class="news-link">
            <div class="news-image">
              <img src="<?php echo $news[0]['img']; ?>" alt="<?php echo htmlspecialchars($news[0]['title']); ?>">
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
        <div class="news-list">
          <?php for($i = 1; $i < count($news); $i++): ?>
            <a href="<?php echo $news[$i]['link']; ?>" class="news-item">
              <div class="news-item-image">
                <img src="<?php echo $news[$i]['img']; ?>" alt="<?php echo htmlspecialchars($news[$i]['title']); ?>">
              </div>
              <div class="news-item-content">
                <h3><?php echo htmlspecialchars($news[$i]['title']); ?></h3>
                <p class="news-description"><?php echo htmlspecialchars($news[$i]['description']); ?></p>
                <span class="news-item-tag">TIN TỨC</span>
              </div>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    </section>

    <!-- Games Section -->
    <section class="games-section">
      <div class="content-wrapper">
        <div class="section-header">
          <h2 class="section-title">Trò chơi của chúng tôi</h2>
          <a href="/Baygorn1/game" class="view-all-btn">
            Xem tất cả
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>

        <div class="games-grid">
          <?php if (!empty($latestGames)): ?>
            <?php foreach($latestGames as $game): ?>
              <a href="/Baygorn1/app/view/game/game-detail.php?id=<?php echo htmlspecialchars($game['game_id']); ?>" class="game-card">
                <div class="game-image">
                  <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($game['image_url']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
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
