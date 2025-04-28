<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang chủ Riot Games Việt Nam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Trang chủ chính thức của Riot Games Việt Nam">
  <link rel="stylesheet" href="home.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../header/header.css">
  <link rel="stylesheet" href="../footer/footer.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php include '../header/header.html'; ?>
  
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="overlay"></div>
      <div class="hero-content">
        <h1 class="hero-title">Hãy ngước nhìn lên những Tinh Tú Thăng Hoa</h1>
        <p class="hero-subtitle">Kiếm tìm và chiến đấu cho huyền thoại đích thực của bạn từ ngày 17/04!</p>
        <a href="#" class="hero-cta">CHƠI NGAY</a>
      </div>
    </section>

    <!-- News Section -->
    <section class="news-section">
      <div class="section-header">
        <h1 class="section-title">Chuyện gì đang xảy ra vậy?</h1>
        <a href="../newspage/news.php" class="view-all-btn">
          Xem tất cả
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>

      <div class="news-grid">
        <!-- Main Left Block -->
        <div class="featured-news">
          <a href="../newspage/news1.php" class="news-link">
            <div class="news-image">
              <img src="../img/minecraft.jpg" alt="new_update_minecraft">
            </div>
            <div class="news-overlay">
              <div class="news-content">
                <h1>Minecraft 1.21.5 "Spring to Life" chính thức ra mắt: Thế giới sống động hơn bao giờ hết!</h1>
                  <p class="news-date">Ngày 25 tháng 3 năm 2025</p>
                  <p class="news-intro">Mojang đã phát hành bản cập nhật Minecraft Java Edition 1.21.5 với tên gọi "Spring to Life" - bản cập nhật mùa xuân đầu tiên của năm, mang đến nhiều thay đổi hấp dẫn về sinh vật, thực vật và âm thanh môi trường, giúp thế giới Minecraft trở nên sống động và chân thực hơn bao giờ hết.</p>
                <span class="news-tag">TIN TỨC</span>
                  
              </div>
            </div>
          </a>
        </div>

        <!-- Right Column -->
        <div class="news-list">
          <a href="../newspage/news2.php" class="news-item">
            <div class="news-item-image">
              <img src="../img/roblox.jpg" alt="news_Brainrot Evolution">
            </div>
            <div class="news-item-content">
              <h3>Brainrot Evolution - Khi Roblox không còn là chính nó</h3>
              
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="../newspage/news3.php" class="news-item">
            <div class="news-item-image">
              <img src="../img/tftupdate.avif" alt="game_TFT">
            </div>
            <div class="news-item-content">
              <h3>ĐTCL 14.2: Cân Bằng Meta, Hack bị điều chỉnh và buff tướng reroll</h3>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/news/van-cuoc.jpg" alt="Ván Cược Đen Tối">
            </div>
            <div class="news-item-content">
              <h4>Ván Cược Đen Tối | Phim Ngắn Mùa 1 năm 2025 - Liên Minh Huyền Thoại</h4>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/news/dtcl.jpg" alt="ĐTCL">
            </div>
            <div class="news-item-content">
              <h4>Lịch Trình Phát Triển ĐTCL: Thành Phố Công Nghệ | Video Từ Đội Ngũ</h4>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>
        </div>
      </div>
    </section>

    <!-- Games Section -->
    <section class="games-section">
      <div class="content-wrapper">
        <div class="section-header">
          <h2 class="section-title">Trò chơi của chúng tôi</h2>
          <a href="../shoppage/shoppage.php" class="view-all-btn">
            Xem tất cả
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>

        <div class="games-grid">
          <a href="#" class="game-card">
            <div class="game-image">
              <img src="../img/game_tft_1.jpg" alt="League of Legends">
            </div>
            <div class="game-overlay">
              <div class="game-content">
                <h3 class="game-title">League of Legends</h3>
                <p class="game-description">Trò chơi MOBA đỉnh cao với hơn 150 tướng</p>
                <span class="game-label">KHÁM PHÁ NGAY</span>
              </div>
            </div>
          </a>

          <a href="#" class="game-card">
            <div class="game-image">
              <img src="../img/white.png" alt="Valorant">
            </div>
            <div class="game-overlay">
              <div class="game-content">
                <h3 class="game-title">Valorant</h3>
                <p class="game-description">Game bắn súng chiến thuật 5v5</p>
                <span class="game-label">KHÁM PHÁ NGAY</span>
              </div>
            </div>
          </a>

          <a href="#" class="game-card">
            <div class="game-image">
              <img src="../img/white.png" alt="Teamfight Tactics">
            </div>
            <div class="game-overlay">
              <div class="game-content">
                <h3 class="game-title">Teamfight Tactics</h3>
                <p class="game-description">Game chiến thuật tự động</p>
                <span class="game-label">KHÁM PHÁ NGAY</span>
              </div>
            </div>
          </a>

          <a href="#" class="game-card">
            <div class="game-image">
              <img src="../img/white.png" alt="Wild Rift">
            </div>
            <div class="game-overlay">
              <div class="game-content">
                <h3 class="game-title">Wild Rift</h3>
                <p class="game-description">MOBA trên di động</p>
                <span class="game-label">KHÁM PHÁ NGAY</span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </section>
  </main>

  <?php include '../footer/footer.html'; ?>

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
    