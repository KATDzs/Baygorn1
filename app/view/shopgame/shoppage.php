<?php
// Get configuration
$config = require_once __DIR__ . '/../../../config.php';
?>

<!-- Main Container -->
<div class="main-container">
    <!-- Hero Section -->
    <section class="hero-zombie">
        <div class="hero-bg-img" style="background-image: url('/Baygorn1/asset/img/games/<?php echo $games[0]['image_url']; ?>')"></div>
        <div class="hero-zombie-content">
            <span class="hero-label" id="heroLabel">WHAT'S HOT</span>
            <h1 id="heroTitle"><?php echo htmlspecialchars($games[0]['title']); ?></h1>
            <p id="heroDesc"><?php echo htmlspecialchars($games[0]['description'] ?? ''); ?></p>
            <div class="hero-meta">
                <span class="platform" id="heroPlatform"><?php echo htmlspecialchars($games[0]['platform']); ?></span>
                <span class="price" id="heroPrice"><?php echo number_format($games[0]['price']); ?> VNĐ</span>
            </div>
            <div class="hero-buttons">
                <a href="/Baygorn1/giaodich?id=<?php echo $games[0]['game_id']; ?>" class="btn btn-primary" id="buyNowBtn">MUA NGAY</a>
                <a href="/Baygorn1/game/game-detail?id=<?php echo $games[0]['game_id']; ?>" class="btn btn-outline">CHI TIẾT</a>
                <button type="button" class="btn btn-secondary btn-add-cart" data-game-id="<?php echo $games[0]['game_id']; ?>">THÊM VÀO GIỎ HÀNG</button>
            </div>
        </div>
    </section>

    <!-- Featured Games Slider -->
    <section class="slider-section">
        <div class="slider-blur-bg" id="sliderBlurBg" style="background-image: url('/Baygorn1/asset/img/games/<?php echo $games[0]['image_url']; ?>')"></div>
        <div class="container">
            <div class="slider-header">
                <div class="slider-title">
                    <span class="dot"></span>
                    <h2>FEATURED GAMES</h2>
                </div>
                <button class="select-all">Xem tất cả</button>
            </div>
            <div class="slider-wrapper">
                <button class="slider-btn" id="prevBtn">&#60;</button>
                <div class="slider-frame">
                    <div id="sliderCardContainer">
                        <!-- First game will be rendered here by JS -->
                    </div>
                </div>
                <button class="slider-btn" id="nextBtn">&#62;</button>
            </div>
        </div>
    </section>

    <!-- Game Grid Section -->
    <section class="game-grid-section">
        <div class="container">
            <div class="section-header">
                <h2>DANH SÁCH GAME</h2>
                <div class="search-box">
                    <input type="text" id="gameSearch" placeholder="Nhập tên game bạn muốn tìm...">
                </div>
                <div class="filter-groups">
                    <select id="categoryFilter">
                        <option value="all">Tất cả thể loại</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="platformFilter">
                        <option value="all">Tất cả nền tảng</option>
                        <option value="PC">PC</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Console">Console</option>
                    </select>
                    <select id="priceFilter">
                        <option value="all">Tất cả giá</option>
                        <option value="0-100000">Dưới 100.000đ</option>
                        <option value="100000-500000">100.000đ - 500.000đ</option>
                        <option value="500000-1000000">500.000đ - 1.000.000đ</option>
                        <option value="1000000+">Trên 1.000.000đ</option>
                    </select>
                    <select id="sortFilter">
                        <option value="newest">Mới nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                        <option value="name_asc">Tên A-Z</option>
                    </select>
                </div>
            </div>

            <div class="game-grid" id="gameGrid">
                <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <div class="game-card-image">
                        <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($game['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($game['title']); ?>">
                    </div>
                    <div class="game-card-info">
                        <h3><?php echo $game['title']; ?></h3>
                        <div class="game-meta">
                            <span class="game-platform"><?php echo $game['platform']; ?></span>
                            <span class="game-price"><?php echo number_format($game['price']); ?> VNĐ</span>
                        </div>
                        <a href="/Baygorn1/game/game-detail?id=<?php echo $game['game_id']; ?>" class="btn btn-outline">CHI TIẾT</a>
                        <button type="button" class="btn btn-secondary btn-add-cart" data-game-id="<?php echo $game['game_id']; ?>">THÊM VÀO GIỎ HÀNG</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast-notification"></div>

<link rel="stylesheet" href="/Baygorn1/asset/css/shoppage.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.SHOPGAMES_DATA = <?php echo json_encode(array_values($games), JSON_UNESCAPED_UNICODE); ?>;

document.querySelectorAll('.btn-add-cart').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var gameId = this.getAttribute('data-game-id');
        Swal.fire({
            title: 'Đang xử lý...',
            text: 'Vui lòng đợi trong giây lát',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/Baygorn1/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'game_id=' + gameId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Lỗi!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi thêm vào giỏ hàng',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
<script src="/Baygorn1/asset/js/shoppage.js"></script>