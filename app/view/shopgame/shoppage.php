<!-- Hero Section -->
<section class="hero-zombie">
    <div class="hero-bg-img"></div>
    <div class="hero-zombie-content">
        <h2 id="heroSub">What's that</h2>
        <h1 id="heroTitle"></h1>
        <p id="heroDesc"></p>
    </div>
</section>

<!-- Slider Section -->
<section class="slider-section">
    <div class="slider-blur-bg" id="sliderBlurBg"></div>
    <div class="slider-header">
        <div class="slider-title-custom">GAME</div>
    </div>
    <div class="slider-wrapper">
        <button class="slider-btn" id="prevBtn">&#60;</button>
        <div class="slider-frame">
            <div id="sliderCardContainer">
                <!-- JS sẽ render card ở đây -->
            </div>
        </div>
        <button class="slider-btn" id="nextBtn">&#62;</button>
    </div>
</section>

<!-- Detail Section -->
<section class="detail-section" id="detailSection">
    <div class="detail-bg-img"></div>
    <div class="detail-info">
        <div class="category" id="detailCategory"></div>
        <h2 id="detailTitle"></h2>
        <div class="meta" id="detailMeta"></div>
        <div class="desc" id="detailDesc"></div>
        <div class="detail-btns">
            <span class="detail-price" id="detailPrice"></span>
            <div class="detail-btns-row">
                <a href="#" class="btn btn-order-now" id="buyNowBtn">MUA NGAY</a>
                <a href="#" class="btn btn-preorder">ĐẶT HÀNG</a>
            </div>
        </div>
    </div>
</section>

<script>
    // Lấy dữ liệu game từ PHP
    const games = <?php echo json_encode(array_values($games), JSON_UNESCAPED_UNICODE); ?>;
    let current = 0;
    const cardContainer = document.getElementById('sliderCardContainer');
    const blurBg = document.getElementById('sliderBlurBg');
    // Detail elements
    const detailCategory = document.getElementById('detailCategory');
    const detailTitle = document.getElementById('detailTitle');
    const detailMeta = document.getElementById('detailMeta');
    const detailDesc = document.getElementById('detailDesc');
    const detailPrice = document.getElementById('detailPrice');
    const heroTitle = document.getElementById('heroTitle');
    const heroSub = document.getElementById('heroSub');
    const heroDesc = document.getElementById('heroDesc');

    function renderCard(idx, direction = 1) {
        if (!games.length) return;
        if (cardContainer.firstChild && cardContainer.firstChild.classList) {
            cardContainer.firstChild.classList.add('fade-out');
            setTimeout(() => {
                actuallyRenderCard(idx, direction);
            }, 350);
        } else {
            actuallyRenderCard(idx, direction);
        }
    }

    function actuallyRenderCard(idx, direction = 1) {
        const game = games[idx];
        cardContainer.innerHTML = `
            <div class="slider-card fade-in">
                <img src="/Baygorn1/asset/img/${game.image_url}" alt="${game.title}">
                <div class="slider-card-info">
                    <div class="title">${game.title}</div>
                    <div class="price">${Number(game.price).toLocaleString()} VNĐ</div>
                </div>
            </div>
        `;
        blurBg.style.backgroundImage = `url('/Baygorn1/asset/img/${game.image_url}')`;
        // Đồng bộ detail
        detailCategory.textContent = game.platform || '';
        detailTitle.textContent = game.title;
        detailMeta.textContent = `Release: ${game.created_at ? game.created_at.substring(0, 10) : ''}`;
        detailDesc.innerHTML = `<b>SHORT DESCRIPTION</b><br>${game.description}`;
        detailPrice.textContent = Number(game.price).toLocaleString() + ' VNĐ';
        // Hero section
        heroTitle.textContent = game.title;
        heroDesc.textContent = game.description;
        // Cập nhật URL cho nút Mua Ngay
        document.getElementById('buyNowBtn').href = `/Baygorn1/app/view/giaodich/giaodich.php?id=${game.game_id}`;
        // Xóa hiệu ứng fade-in sau khi xong
        setTimeout(() => {
            const card = cardContainer.querySelector('.slider-card');
            if(card) card.classList.remove('fade-in');
        }, 350);
    }

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

    document.addEventListener('DOMContentLoaded', function() {
        if (games.length) renderCard(current);
    });
</script> 