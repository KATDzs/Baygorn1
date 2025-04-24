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
      </div>

      <div class="news-grid">
        <!-- Main Left Block -->
        <div class="featured-news">
          <a href="#" class="news-link">
            <div class="news-image">
              <img src="../img/white.png" alt="Main Image">
            </div>
            <div class="news-overlay">
              <div class="news-content">
                <h3>Ngước Lên Cao! | Trailer Ryze x Faker - Liên Minh Huyền Thoại: TC</h3>
                <span class="news-tag">TIN TỨC</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Right Column -->
        <div class="news-list">
          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/white.png" alt="News 1">
            </div>
            <div class="news-item-content">
              <h4>Hoa Linh Lục Địa, Loạn Đấu & Nội Dung Khác...</h4>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/white.png" alt="News 2">
            </div>
            <div class="news-item-content">
              <h4>Here, Tomorrow (ft Lilas, Kevin Penkin) - Phim Ngắn Mùa 2 2025...</h4>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/white.png" alt="News 3">
            </div>
            <div class="news-item-content">
              <h4>Ván Cược Đen Tối | Phim Ngắn Mùa 1 năm 2025 - Liên Minh Huyền Thoại</h4>
              <span class="news-item-tag">TIN TỨC</span>
            </div>
          </a>

          <a href="#" class="news-item">
            <div class="news-item-image">
              <img src="../img/white.png" alt="News 4">
            </div>
            <div class="news-item-content">
              <h4>Lịch Trình Phát Triển ĐTCL: Thành Phố Công Nghệ | Video Từ Đội Ngũ...</h4>
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
        </div>

        <div class="games-grid">
          <a href="#" class="game-card">
            <div class="game-image">
              <img src="../img/white.png" alt="League of Legends">
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
