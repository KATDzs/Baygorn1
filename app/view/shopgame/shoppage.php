<?php
// Get configuration
$config = require_once __DIR__ . '/../../../config.php';
?>

<!-- Main Container -->
<div class="main-container container-fluid px-0">
    <!-- Hero Section -->
    <section class="hero-zombie row gx-0">
        <div class="hero-bg-img col-12" style="background-image: url('/Baygorn1/asset/img/games/<?php echo $games[0]['image_url']; ?>')"></div>
        <div class="hero-zombie-content col-12 col-md-8 col-lg-6 mx-auto">
            <span class="hero-label" id="heroLabel">WHAT'S HOT</span>
            <h1 id="heroTitle"><?php echo htmlspecialchars($games[0]['title']); ?></h1>
            <p id="heroDesc"><?php echo htmlspecialchars($games[0]['description'] ?? ''); ?></p>
            <div class="hero-meta d-flex flex-wrap gap-3 mb-3">
                <span class="platform" id="heroPlatform"><?php echo htmlspecialchars($games[0]['platform']); ?></span>
                <span class="price" id="heroPrice"><?php echo number_format($games[0]['price']); ?> VNĐ</span>
            </div>
            <div class="hero-buttons d-flex flex-wrap gap-2">
                <a href="/Baygorn1/giaodich?id=<?php echo $games[0]['game_id']; ?>" class="btn btn-danger" id="buyNowBtn">MUA NGAY</a>
                <a href="/Baygorn1/game/game-detail?id=<?php echo $games[0]['game_id']; ?>" class="btn btn-outline-danger">CHI TIẾT</a>
                <button type="button" class="btn btn-secondary btn-add-cart" data-game-id="<?php echo $games[0]['game_id']; ?>">THÊM VÀO GIỎ HÀNG</button>
            </div>
        </div>
    </section>

    <!-- Featured Games Slider -->
    <section class="slider-section py-5">
        <div class="slider-blur-bg" id="sliderBlurBg" style="background-image: url('/Baygorn1/asset/img/games/<?php echo $games[0]['image_url']; ?>')"></div>
        <div class="container">
            <div class="slider-header row align-items-center mb-4">
                <div class="slider-title col">
                    <span class="dot"></span>
                    <h2>FEATURED GAMES</h2>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary select-all">Xem tất cả</button>
                </div>
            </div>
            <div class="slider-wrapper row align-items-center">
                <div class="col-auto">
                    <button class="slider-btn btn btn-light" id="prevBtn">&#60;</button>
                </div>
                <div class="col px-0">
                    <div class="slider-frame">
                        <div id="sliderCardContainer">
                            <!-- First game will be rendered here by JS -->
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="slider-btn btn btn-light" id="nextBtn">&#62;</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Game Grid Section -->
    <section class="game-grid-section py-5">
        <div class="container">
            <div class="section-header row align-items-center mb-4">
                <div class="col">
                    <h2>DANH SÁCH GAME</h2>
                </div>
                <div class="col-auto">
                    <div class="search-box">
                        <input type="text" id="gameSearch" class="form-control" placeholder="Nhập tên game bạn muốn tìm...">
                    </div>
                </div>
            </div>
            <div class="filter-groups row g-2 mb-4">
                <div class="col-6 col-md-3">
                    <select id="categoryFilter" class="form-select">
                        <option value="all">Tất cả thể loại</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select id="platformFilter" class="form-select">
                        <option value="all">Tất cả nền tảng</option>
                        <option value="PC">PC</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Console">Console</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select id="priceFilter" class="form-select">
                        <option value="all">Tất cả giá</option>
                        <option value="0-100000">Dưới 100.000đ</option>
                        <option value="100000-500000">100.000đ - 500.000đ</option>
                        <option value="500000-1000000">500.000đ - 1.000.000đ</option>
                        <option value="1000000+">Trên 1.000.000đ</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select id="sortFilter" class="form-select">
                        <option value="newest">Mới nhất</option>
                        <option value="price_asc">Giá tăng dần</option>
                        <option value="price_desc">Giá giảm dần</option>
                        <option value="name_asc">Tên A-Z</option>
                    </select>
                </div>
            </div>
            <div class="game-grid row g-4" id="gameGrid">
                <?php foreach ($games as $game): ?>
                <div class="game-card col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="game-card-image">
                        <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($game['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($game['title']); ?>" class="img-fluid rounded">
                    </div>
                    <div class="game-card-info">
                        <h3><?php echo $game['title']; ?></h3>
                        <div class="game-meta d-flex justify-content-between align-items-center mb-2">
                            <span class="game-platform badge bg-secondary"><?php echo $game['platform']; ?></span>
                            <span class="game-price fw-bold text-danger"><?php echo number_format($game['price']); ?> VNĐ</span>
                        </div>
                        <a href="/Baygorn1/game/game-detail?id=<?php echo $game['game_id']; ?>" class="btn btn-outline-primary btn-sm">CHI TIẾT</a>
                        <button type="button" class="btn btn-secondary btn-sm btn-add-cart" data-game-id="<?php echo $game['game_id']; ?>">THÊM VÀO GIỎ HÀNG</button>
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
<script>
window.SHOPGAMES_DATA = <?php echo json_encode(array_values($games), JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="/Baygorn1/asset/js/shoppage.js"></script>