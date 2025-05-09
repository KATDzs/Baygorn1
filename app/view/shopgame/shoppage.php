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
                <a href="<?php echo $config['base']; ?>app/view/game/game-detail.php?id=<?php echo $games[0]['game_id']; ?>" class="btn btn-outline">CHI TIẾT</a>
                <a href="/Baygorn1/index.php?url=cart/add&game_id=<?php echo $games[0]['game_id']; ?>" class="btn btn-secondary" data-game-id="<?php echo $games[0]['game_id']; ?>">THÊM VÀO GIỎ HÀNG</a>
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
                <a href="/Baygorn1/giaodich?id=<?php echo $game['game_id']; ?>" class="game-card">
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
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const games = <?php echo json_encode(array_values($games), JSON_UNESCAPED_UNICODE); ?>;
    let current = 0;
    const cardContainer = document.getElementById('sliderCardContainer');
    const blurBg = document.getElementById('sliderBlurBg');
    const heroBg = document.querySelector('.hero-bg-img');
    const heroTitle = document.getElementById('heroTitle');
    const heroDesc = document.getElementById('heroDesc');
    const heroPlatform = document.getElementById('heroPlatform');
    const heroPrice = document.getElementById('heroPrice');
    const buyNowBtn = document.getElementById('buyNowBtn');

    function renderCard(idx, direction = 1) {
        if (!games.length) return;
        const game = games[idx];
        const imagePath = `/Baygorn1/asset/img/games/${game.image_url}`;
        const defaultImage = '/Baygorn1/asset/img/default-game.jpg';

        // Update slider card
        cardContainer.innerHTML = `
            <div class="slider-card fade-in">
                <a href="/Baygorn1/giaodich?id=${game.game_id}">
                    <img src="${imagePath}" alt="${game.title}">
                    <div class="slider-card-info">
                        <div class="title">${game.title}</div>
                        <div class="price">${Number(game.price).toLocaleString()} VNĐ</div>
                    </div>
                </a>
            </div>
        `;

        // Update hero section
        heroBg.style.backgroundImage = `url('${imagePath}')`;
        blurBg.style.backgroundImage = `url('${imagePath}')`;
        heroTitle.textContent = game.title;
        heroDesc.textContent = game.description || '';
        heroPlatform.textContent = game.platform;
        heroPrice.textContent = `${Number(game.price).toLocaleString()} VNĐ`;
        buyNowBtn.href = `/Baygorn1/giaodich?id=${game.game_id}`;
    }

    // Initial render
    renderCard(current);

    // Auto slider
    setInterval(() => {
        current = (current + 1) % games.length;
        renderCard(current);
    }, 5000);

    // Button controls
    document.getElementById('prevBtn').onclick = function() {
        if (!games.length) return;
        current = (current - 1 + games.length) % games.length;
        renderCard(current, -1);
    };

    document.getElementById('nextBtn').onclick = function() {
        if (!games.length) return;
        current = (current + 1) % games.length;
        renderCard(current, 1);
    };

    // New filtering and view switching functionality
    const gameGrid = document.getElementById('gameGrid');
    const gameSearch = document.getElementById('gameSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const platformFilter = document.getElementById('platformFilter');
    const priceFilter = document.getElementById('priceFilter');
    const sortFilter = document.getElementById('sortFilter');
    const viewBtns = document.querySelectorAll('.view-btn');
    const gameCount = document.getElementById('gameCount')?.querySelector('span');
    
    let filteredGames = [...games];
    let currentView = 'grid';

    function filterGames() {
        const searchTerm = gameSearch.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedPlatform = platformFilter.value;
        const priceRange = priceFilter.value;
        const sortBy = sortFilter.value;

        filteredGames = games.filter(game => {
            const matchesSearch = game.title.toLowerCase().includes(searchTerm);
            const matchesCategory = selectedCategory === 'all' || 
                game.categories.some(cat => cat.category_id.toString() === selectedCategory);
            const matchesPlatform = selectedPlatform === 'all' || 
                game.platform.includes(selectedPlatform);
            const matchesPrice = priceRange === 'all' || checkPriceRange(game.price, priceRange);
            
            return matchesSearch && matchesCategory && matchesPlatform && matchesPrice;
        });

        sortGames(filteredGames, sortBy);
        renderGames();
        if (gameCount) {
            gameCount.textContent = filteredGames.length;
        }
    }

    function checkPriceRange(price, range) {
        if (range === 'all') return true;
        const [min, max] = range.split('-').map(Number);
        if (range.endsWith('+')) {
            return price >= min;
        }
        return price >= min && price <= max;
    }

    function sortGames(games, sortBy) {
        switch (sortBy) {
            case 'newest':
                games.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'price_asc':
                games.sort((a, b) => a.price - b.price);
                break;
            case 'price_desc':
                games.sort((a, b) => b.price - a.price);
                break;
            case 'name_asc':
                games.sort((a, b) => a.title.localeCompare(b.title));
                break;
        }
    }

    function renderGames() {
        gameGrid.className = `game-${currentView}`;
        gameGrid.innerHTML = filteredGames.map(game => {
            const imagePath = `/Baygorn1/asset/img/games/${game.image_url}`;
            const defaultImage = '/Baygorn1/asset/img/default-game.jpg';
            
            return `
                <a href="/Baygorn1/giaodich?id=${game.game_id}" 
                   class="game-card" 
                   data-categories="${game.categories.map(cat => cat.category_id).join(',')}"
                   data-platform="${game.platform}"
                   data-price="${game.price}"
                   data-date="${game.created_at}"
                   data-name="${game.title}">
                    <div class="game-card-image">
                        <img src="${imagePath}" 
                             alt="${game.title}"
                             loading="lazy"
                             onerror="this.src='${defaultImage}'">
                        <div class="game-card-overlay">
                            <div class="overlay-content">
                                <span class="view-details">XEM CHI TIẾT</span>
                            </div>
                        </div>
                    </div>
                    <div class="game-card-info">
                        <div class="game-card-header">
                            <h3>${game.title}</h3>
                            <div class="game-platform">${game.platform}</div>
                        </div>
                        <div class="game-card-meta">
                            <div class="game-categories">
                                ${game.categories.map(cat => `
                                    <span class="game-category">${cat.name}</span>
                                `).join('')}
                            </div>
                            <div class="game-price">${Number(game.price).toLocaleString()} VNĐ</div>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    }

    // Event listeners
    gameSearch.addEventListener('input', filterGames);
    categoryFilter.addEventListener('change', filterGames);
    platformFilter.addEventListener('change', filterGames);
    priceFilter.addEventListener('change', filterGames);
    sortFilter.addEventListener('change', filterGames);

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentView = this.dataset.view;
            renderGames();
        });
    });

    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const gameId = this.dataset.gameId;

            fetch('/Baygorn1/index.php?url=cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `game_id=${gameId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Optionally, refresh the cart view dynamically
                    window.location.href = '/Baygorn1/index.php?url=cart';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart');
            });
        });
    });
});
</script>