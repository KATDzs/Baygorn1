<?php
// Include any necessary PHP configurations here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Shop - Baygorn Games</title>
    <link rel="stylesheet" href="../asset/css/header.css">
    <link rel="stylesheet" href="../asset/css/footer.css">
    <link rel="stylesheet" href="../asset/css/shoppage.css">
</head>
<body>
    <?php include '../layout/header.html'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-zombie">
            <div class="hero-bg-img"></div>
            <div class="hero-zombie-content">
                <h2>What's that</h2>
                <h1>ZOMBIES</h1>
                <p>Zombi (kata tidak baku: Jombi) adalah istilah yang digunakan untuk makhluk hidup dalam film horor, game, ataupun film fantasi. Zombi digambarkan sebagai mayat yang tidak berpikiran dan bernafsu memangsa manusia, khususnya otak manusia yang dijadikan target santapan utamanya.</p>
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
                <div class="category" id="detailCategory">HOROR MOVIE</div>
                <h2 id="detailTitle">SWEET HOME</h2>
                <div class="meta" id="detailMeta">Tahun Rilis: 2020 Rating IMDb: 7.3</div>
                <div class="desc" id="detailDesc"><b>SHORT DESCRIPTION</b><br>Seorang murid sekolah SMA yang bernama Hyun, telah kehilangan keluarganya dalam kecelakaan yang mengerikan, dia pun terpaksa meninggalkan rumahnya. Alhasil, Hyun harus melawan kenyataan baru yaitu berjuang menyelamatkan apa mà tersisa từ umat manusia trước khi quá muộn.</div>
                <div class="detail-btns">
                    <span class="detail-price" id="detailPrice">$19.99</span>
                    <div class="detail-btns-row">
                        <a href="#" class="btn btn-order-now">MUA NGAY</a>
                        <a href="#" class="btn btn-preorder">ĐẶT HÀNG</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const games = [
            {
                title: 'ROBLOX',
                heroTitle: 'ROBLOX',
                heroDesc: 'Roblox là nền tảng game sáng tạo cho phép bạn xây dựng thế giới và chơi hàng triệu game do cộng đồng tạo ra.',
                detailTitle: 'ROBLOX - SANDBOX',
                detailDesc: 'Roblox là một vũ trụ ảo nơi bạn có thể sáng tạo, chia sẻ trải nghiệm và phiêu lưu cùng bạn bè.',
                price: '$19.99',
                img: '../asset/img/game_roblox.jpg',
                category: 'SANDBOX',
                meta: 'Release: 2006 | Genre: Sandbox'
            },
            {
                title: 'MINECRAFT',
                heroTitle: 'MINECRAFT',
                heroDesc: 'Minecraft là game xây dựng thế giới mở nổi tiếng, nơi bạn có thể khai thác, chế tạo và phiêu lưu không giới hạn.',
                detailTitle: 'MINECRAFT - SANDBOX',
                detailDesc: 'Khám phá, xây dựng và sinh tồn trong thế giới khối vuông vô tận của Minecraft.',
                price: '$29.99',
                img: '../asset/img/minecraft.jpg',
                category: 'SANDBOX',
                meta: 'Release: 2011 | Genre: Sandbox'
            },
            {
                title: 'TFT',
                heroTitle: 'TEAMFIGHT TACTICS',
                heroDesc: 'Teamfight Tactics (TFT) là game chiến thuật đấu tướng tự động, nơi bạn xây dựng đội hình và đối đầu với người chơi khác.',
                detailTitle: 'TFT - AUTO CHESS',
                detailDesc: 'Xây dựng đội hình mạnh mẽ, chiến đấu và leo rank trong TFT.',
                price: '$9.99',
                img: '../asset/img/game_tft_1.jpg',
                category: 'AUTO CHESS',
                meta: 'Release: 2019 | Genre: Auto Chess'
            },
            {
                title: 'I AM LEGION STAND',
                heroTitle: 'I AM LEGION STAND',
                heroDesc: 'I Am Legion Stand là game hành động sinh tồn với lối chơi kịch tính, nơi bạn phải chiến đấu chống lại thế lực bí ẩn.',
                detailTitle: 'I AM LEGION STAND - ACTION',
                detailDesc: 'Sinh tồn, chiến đấu và khám phá bí ẩn trong thế giới hậu tận thế.',
                price: '$39.99',
                img: '../asset/img/game_i_am_legion.jpg',
                category: 'ACTION',
                meta: 'Release: 2023 | Genre: Action'
            },
            {
                title: 'PALWORLD',
                heroTitle: 'PALWORLD',
                heroDesc: 'Palworld là game phiêu lưu thế giới mở, nơi bạn bắt và huấn luyện sinh vật kỳ lạ, kết hợp sinh tồn và xây dựng.',
                detailTitle: 'PALWORLD - ADVENTURE',
                detailDesc: 'Bắt, huấn luyện Pal và khám phá thế giới rộng lớn đầy thử thách.',
                price: '$49.99',
                img: '../asset/img/game_palworld.jpeg',
                category: 'ADVENTURE',
                meta: 'Release: 2024 | Genre: Adventure'
            }
        ];
        let current = 0;
        const cardContainer = document.getElementById('sliderCardContainer');
        const blurBg = document.getElementById('sliderBlurBg');
        // Detail elements
        const detailCategory = document.getElementById('detailCategory');
        const detailTitle = document.getElementById('detailTitle');
        const detailMeta = document.getElementById('detailMeta');
        const detailDesc = document.getElementById('detailDesc');
        const detailThumb = document.getElementById('detailThumb');
        function renderCard(idx, direction = 1) {
            // Hiệu ứng fade-out nếu đã có card
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
                    <img src="${game.img}" alt="${game.title}">
                    <div class="slider-card-info">
                        <div class="title">${game.title}</div>
                        <div class="price">${game.price}</div>
                </div>
                </div>
            `;
            blurBg.style.backgroundImage = `url('${game.img}')`;
            // Đồng bộ detail
            detailCategory.textContent = game.category;
            detailTitle.textContent = game.detailTitle;
            detailMeta.textContent = game.meta;
            detailDesc.innerHTML = `<b>SHORT DESCRIPTION</b><br>${game.detailDesc}`;
            if (detailThumb) {
                detailThumb.src = game.img;
                detailThumb.alt = game.title;
            }
            // Đồng bộ background hero-zombie
            document.querySelector('.hero-bg-img').style.backgroundImage = `url('${game.img}')`;
            // Đồng bộ background detail-section
            document.querySelector('.detail-bg-img').style.backgroundImage = `url('${game.img}')`;
            // Đồng bộ nội dung hero-zombie-content
            const heroTitle = document.querySelector('.hero-zombie-content h1');
            const heroSub = document.querySelector('.hero-zombie-content h2');
            const heroDesc = document.querySelector('.hero-zombie-content p');
            console.log('DEBUG heroTitle:', heroTitle, 'heroSub:', heroSub, 'heroDesc:', heroDesc);
            if (heroTitle) heroTitle.textContent = game.heroTitle;
            if (heroSub) heroSub.textContent = "What's that";
            if (heroDesc) heroDesc.textContent = game.heroDesc;
            // Xóa hiệu ứng fade-in sau khi xong
            setTimeout(() => {
                const card = cardContainer.querySelector('.slider-card');
                if(card) card.classList.remove('fade-in');
            }, 350);
            document.getElementById('detailPrice').textContent = game.price;
        }
        document.getElementById('prevBtn').onclick = function() {
            current = (current - 1 + games.length) % games.length;
            renderCard(current, -1);
        };
        document.getElementById('nextBtn').onclick = function() {
            current = (current + 1) % games.length;
            renderCard(current, 1);
        };
        document.addEventListener('DOMContentLoaded', function() {
            renderCard(current);
        });
    </script>

    <?php include '../layout/footer.html'; ?>
</body>
</html> 