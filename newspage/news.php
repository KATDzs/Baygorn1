<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BayGorn1 - Tin Tức</title>
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="news.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../header/header.html'; ?>

    <?php
    $news = [
        [
            'title' => 'Minecraft Spring Update',
            'description' => '​Minecraft 1.21.5 "Spring to Life" chính thức ra mắt: Thế giới sống động hơn bao giờ hết!​',
            'img' => '../img/minecraftupdate.png',
            'link' => '../newspage/news1.php'
        ],
        [
            'title' => 'Tin tức 2',
            'description' => 'Mô tả tin tức 2',
            'img' => 'https://via.placeholder.com/300x200',
            'link' => '#'
        ],
        [
            'title' => 'Tin tức 3',
            'description' => 'Mô tả tin tức 3',
            'img' => 'https://via.placeholder.com/300x200',
            'link' => '#'
        ]
    ];
    ?>

    <section class="tin-tuc">
        <h2>Tin tức</h2>

        <button class="nav-button prev-button" onclick="scrollSlider(-1)">❮</button>
        <button class="nav-button next-button" onclick="scrollSlider(1)">❯</button>
        <div class="slider-wrapper">
            <div class="slider-container" id="slider">
                <?php foreach ($news as $item): ?>
                    <a href="<?= htmlspecialchars($item['link']) ?>" class="news-card">
                        <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
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

    <?php include '../footer/footer.html'; ?>
    <script src="news.js"></script>
</body>
</html>