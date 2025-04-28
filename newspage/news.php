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
            'img' => '../img/minecraftupdate.jpg',
            'link' => '../newspage/news1.php'
        ],
        [
            'title' => 'Roblox Update 2025',
            'description' => 'Cập nhật và tối ưu các tính năng cùng nhiều phần quà hấp dẫn đang đợi bạn tới chơi!',
            'img' => '../img/brainrotevolution.webp',
            'link' => '../newspage/news2.php'
        ],
        [
            'title' => 'ĐTCL 14.2: Cân Bằng Meta, Hack bị điều chỉnh và buff tướng reroll',
            'description' => 'Bản cập nhật 14.2 mang tính cân bằng cao, cùng sự tối ưu hệ thống mới cho game thủ.',
            'img' => '../img/tftupdate.avif',
            'link' => '../newspage/news3.php'
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