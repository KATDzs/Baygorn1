document.addEventListener('DOMContentLoaded', function() {
    const games = window.SHOPGAMES_DATA || [];
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
        if (filteredGames.length === 0) {
            gameGrid.innerHTML = '<div class="no-game">Không tìm thấy game phù hợp.</div>';
            return;
        }
        gameGrid.innerHTML = filteredGames.map(game => {
            const imagePath = `/Baygorn1/asset/img/games/${game.image_url}`;
            const defaultImage = '/Baygorn1/asset/img/default-game.jpg';
            return `
                <div class="game-card">
                    <div class="game-card-image">
                        <img src="${imagePath}" 
                             alt="${game.title}"
                             loading="lazy"
                             onerror="this.src='${defaultImage}'">
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
                        <a href="/Baygorn1/game/game-detail?id=${game.game_id}" class="btn btn-outline">CHI TIẾT</a>
                        <button type="button" class="btn btn-secondary btn-add-cart" data-game-id="${game.game_id}">THÊM VÀO GIỎ HÀNG</button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Thêm nút tìm kiếm nếu chưa có
    let searchBtn = document.getElementById('gameSearchBtn');
    if (!searchBtn) {
        searchBtn = document.createElement('button');
        searchBtn.id = 'gameSearchBtn';
        searchBtn.type = 'button';
        searchBtn.className = 'btn btn-primary';
        searchBtn.textContent = 'Tìm kiếm';
        gameSearch.parentNode.insertBefore(searchBtn, gameSearch.nextSibling);
    }

    // Thêm nút clear (dấu X) cho ô nhập tìm kiếm
    let clearBtn = document.getElementById('gameSearchClearBtn');
    if (!clearBtn) {
        clearBtn = document.createElement('button');
        clearBtn.id = 'gameSearchClearBtn';
        clearBtn.type = 'button';
        clearBtn.className = 'btn btn-primary'; // giống nút tìm kiếm
        clearBtn.style.marginLeft = '16px';
        clearBtn.style.display = gameSearch.value ? 'inline-block' : 'none';
        clearBtn.style.height = searchBtn.offsetHeight + 'px';
        clearBtn.style.width = searchBtn.offsetHeight + 'px';
        clearBtn.style.fontSize = '22px';
        clearBtn.style.fontWeight = 'bold';
        clearBtn.innerHTML = '&times;';
        // Đặt sau nút tìm kiếm
        searchBtn.parentNode.insertBefore(clearBtn, searchBtn.nextSibling);
    }
    gameSearch.addEventListener('input', function() {
        clearBtn.style.display = this.value ? 'inline-block' : 'none';
    });
    clearBtn.onclick = function() {
        gameSearch.value = '';
        clearBtn.style.display = 'none';
        gameSearch.focus();
        // Reset filteredGames về toàn bộ games, sort lại, render lại
        filteredGames = [...games];
        sortGames(filteredGames, sortFilter.value);
        renderGames();
        if (gameCount) {
            gameCount.textContent = filteredGames.length;
        }
    };

    // Bỏ sự kiện input, chỉ lọc khi bấm nút hoặc nhấn Enter
    gameSearch.removeEventListener('input', filterGames);
    searchBtn.onclick = filterGames;
    gameSearch.onkeydown = function(e) {
        if (e.key === 'Enter') filterGames();
    };

    
    // Khi bấm nút tìm kiếm hoặc nhấn Enter mới lọc
    categoryFilter.onkeydown = function(e) { if (e.key === 'Enter') filterGames(); };
    platformFilter.onkeydown = function(e) { if (e.key === 'Enter') filterGames(); };
    priceFilter.onkeydown = function(e) { if (e.key === 'Enter') filterGames(); };
    sortFilter.onkeydown = function(e) { if (e.key === 'Enter') filterGames(); };

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentView = this.dataset.view;
            renderGames();
        });
    });

    // Toast notification function (bottom right)
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = 'toast-notification show';
        setTimeout(() => {
            toast.className = 'toast-notification';
        }, 2500);
    }

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
                showToast(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Đã xảy ra lỗi khi thêm vào giỏ hàng');
            });
        });
    });
});
