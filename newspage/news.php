<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BayGorn1 - Tin Tức</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #111;
            color: #fff;
        }

        .tin-tuc {
            padding: 40px 20px;
            max-width: 1200px;
            margin: auto;
            position: relative;
        }

        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            border-left: 5px solid #ff4655;
            padding-left: 15px;
        }

        .slider-wrapper {
            position: relative;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            gap: 20px;
            overflow-x: hidden;
            padding-bottom: 10px;
        }

        .news-card {
            display: block;
            min-width: 300px;
            background: #222;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .news-card:hover {
            transform: scale(1.03);
        }

        .news-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .news-content {
            padding: 15px;
        }

        .news-content h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #ff4655;
        }

        .news-content p {
            font-size: 0.95em;
            color: #ccc;
        }

        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
            color: white;
            padding: 10px 15px;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            border-radius: 5px;
        }

        .nav-button:hover {
            background-color: rgba(255, 70, 85, 0.8);
        }

        .prev-button {
            left: -10px;
        }

        .next-button {
            right: -10px;
        }

        .dots {
            text-align: center;
            margin-top: 15px;
        }

        .dot {
            display: inline-block;
            height: 10px;
            width: 10px;
            margin: 0 5px;
            background-color: #555;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dot.active {
            background-color: #ff4655;
        }

        @media (max-width: 768px) {
            .nav-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <section class="tin-tuc">
        <h2>Tin tức</h2>

        <button class="nav-button prev-button" onclick="scrollSlider(-1)">❮</button>
        <button class="nav-button next-button" onclick="scrollSlider(1)">❯</button>
            <div class="slider-wrapper">
                <div class="slider-container" id="slider">
                    <?php foreach ($news as $item): ?>
                        <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card">
                            <img src="<?= htmlspecialchars($item['img']) ?>">
                            <div class="news-content">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <p><?= htmlspecialchars($item['description']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <div class="dots" id="dots">
            <?php foreach ($news as $index => $item): ?>
                <span class="dot <?= $index === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $index ?>)"></span>
            <?php endforeach; ?>
        </div>
    </section>
    <script>
        const slider = document.getElementById("slider");
        const dots = document.querySelectorAll(".dot");
        function getCardWidth() {
            const card = document.querySelector(".news-card");
            return card ? card.offsetWidth + 20 : 320;
        }
        function scrollSlider(direction) {
            const cardWidth = getCardWidth();
            let currentIndex = Math.round(slider.scrollLeft / cardWidth);
            let nextIndex = currentIndex + direction;
            nextIndex = Math.max(0, Math.min(nextIndex, dots.length - 1));
            goToSlide(nextIndex);
        }

        function goToSlide(index) {
            const cardWidth = getCardWidth();
            slider.scrollTo({
                left: index * cardWidth,
                behavior: "smooth"
            });
            updateDots(index);
        }

        function updateDots(activeIndex) {
            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i === activeIndex);
            });
        }

        slider.addEventListener("scroll", () => {
            const cardWidth = getCardWidth();
            const index = Math.round(slider.scrollLeft / cardWidth);
            updateDots(index);
        });
    </script>
</body>
</html>
