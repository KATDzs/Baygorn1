<?php
// No need to require files or initialize models here since it's handled by the controller
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
  <?php require_once ROOT_PATH . '/view/layout/header.php'; ?>
  
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
        <?php if (!empty($latestNews)): ?>
          <!-- Main Left Block -->
          <div class="featured-news">
            <a href="/Baygorn1/news/detail/<?php echo htmlspecialchars($latestNews[0]['news_id']); ?>" class="news-link">
              <div class="news-image">
                <img src="/Baygorn1/asset/img/news/<?php echo htmlspecialchars($latestNews[0]['image_url']); ?>" alt="<?php echo htmlspecialchars($latestNews[0]['title']); ?>">
              </div>
              <div class="news-overlay">
                <div class="news-content">
                  <h1><?php echo htmlspecialchars($latestNews[0]['title']); ?></h1>
                  <p class="news-date"><?php echo date('d/m/Y', strtotime($latestNews[0]['published_at'])); ?></p>
                  <p class="news-intro"><?php echo htmlspecialchars($latestNews[0]['content']); ?></p>
                  <span class="news-tag">TIN TỨC</span>
                </div>
              </div>
            </a>
          </div>

          <!-- Right Column -->
          <div class="news-list">
            <?php for($i = 1; $i < count($latestNews); $i++): ?>
              <a href="/Baygorn1/news/detail/<?php echo htmlspecialchars($latestNews[$i]['news_id']); ?>" class="news-item">
                <div class="news-item-image">
                  <img src="/Baygorn1/asset/img/news/<?php echo htmlspecialchars($latestNews[$i]['image_url']); ?>" alt="<?php echo htmlspecialchars($latestNews[$i]['title']); ?>">
                </div>
                <div class="news-item-content">
                  <h3><?php echo htmlspecialchars($latestNews[$i]['title']); ?></h3>
                  <span class="news-item-tag">TIN TỨC</span>
                </div>
              </a>
            <?php endfor; ?>
          </div>
        <?php else: ?>
          <p class="no-news">Chưa có tin tức mới.</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Games Section -->
    <section class="games-section">
      <div class="content-wrapper">
        <div class="section-header">
          <h2 class="section-title">Trò chơi của chúng tôi</h2>
          <a href="/Baygorn1/games" class="view-all-btn">
            Xem tất cả
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>

        <div class="games-grid">
          <?php if (!empty($latestGames)): ?>
            <?php foreach($latestGames as $game): ?>
              <a href="/Baygorn1/game/detail/<?php echo htmlspecialchars($game['game_id']); ?>" class="game-card">
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

  <script>
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Add scroll animation for header
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll <= 0) {
        navbar.classList.remove('scroll-up');
        return;
      }
      
      if (currentScroll > lastScroll && !navbar.classList.contains('scroll-down')) {
        navbar.classList.remove('scroll-up');
        navbar.classList.add('scroll-down');
      } else if (currentScroll < lastScroll && navbar.classList.contains('scroll-down')) {
        navbar.classList.remove('scroll-down');
        navbar.classList.add('scroll-up');
      }
      
      lastScroll = currentScroll;
    });
  </script>
</body>
</html>
    