<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BayGorn1 - Tin Tức</title>
    <link rel="stylesheet" href="news.css">
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
    <script src="news.js"></script>
</body>
</html>