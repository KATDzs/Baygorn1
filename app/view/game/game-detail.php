<?php
// Get configuration
$config = require_once __DIR__ . '/../../../config.php';

// Get database connection
$conn = require_once __DIR__ . '/../../../core/db_connection.php';

// Get game ID from URL parameter
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prepare and execute query to get game details with categories
$stmt = $conn->prepare("
    SELECT g.*, GROUP_CONCAT(c.name) as categories
    FROM games g
    LEFT JOIN game_categories gc ON g.game_id = gc.game_id
    LEFT JOIN categories c ON gc.category_id = c.category_id
    WHERE g.game_id = ?
    GROUP BY g.game_id
");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

// If game not found, redirect to shop page
if (!$game) {
    header("Location: " . $config['base'] . "app/view/shopgame/shoppage.php");
    exit();
}

// Convert categories string to array
$categories = explode(',', $game['categories']);

// Decode meta information
$meta = json_decode($game['meta'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['title']); ?> - Baygorn Game Shop</title>
    <link rel="stylesheet" href="<?php echo $config['assets']; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $config['assets']; ?>css/game-detail.css">
</head>
<body>
    <div class="game-detail-container">
        <div class="game-hero" style="background-image: url('<?php echo $config['assets']; ?>img/games/<?php echo htmlspecialchars($game['image_url']); ?>')">
        </div>

        <div class="game-info">
            <h1 class="game-title"><?php echo htmlspecialchars($game['title']); ?></h1>
            
            <div class="game-meta">
                <div class="meta-item">
                    <i class="fas fa-desktop"></i> <?php echo htmlspecialchars($game['platform']); ?>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($meta['release_date'] ?? 'N/A'); ?>
                </div>
                <div class="meta-item">
                    <i class="fas fa-building"></i> <?php echo htmlspecialchars($meta['publisher'] ?? 'N/A'); ?>
                </div>
            </div>

            <div class="game-categories">
                <?php foreach ($categories as $category): ?>
                    <span class="category-tag"><?php echo htmlspecialchars(trim($category)); ?></span>
                <?php endforeach; ?>
            </div>

            <p class="game-description"><?php echo nl2br(htmlspecialchars($game['detail_desc'])); ?></p>

            <div class="game-actions">
                <div class="price-tag">
                    <i class="fas fa-tag"></i>
                    <span><?php echo number_format($game['price']); ?> VNĐ</span>
                </div>
                <a href="<?php echo $config['base']; ?>app/view/giaodich/giaodich.php?id=<?php echo $game['game_id']; ?>" class="buy-button">
                    MUA NGAY
                </a>
            </div>
        </div>

        <div class="game-details">
            <h2>Thông tin chi tiết</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Thể loại</div>
                    <div class="detail-value"><?php echo htmlspecialchars(implode(', ', $categories)); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nền tảng</div>
                    <div class="detail-value"><?php echo htmlspecialchars($game['platform']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nhà phát hành</div>
                    <div class="detail-value"><?php echo htmlspecialchars($meta['publisher'] ?? 'N/A'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Ngày phát hành</div>
                    <div class="detail-value"><?php echo htmlspecialchars($meta['release_date'] ?? 'N/A'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tình trạng</div>
                    <div class="detail-value"><?php echo htmlspecialchars($game['status']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Số lượng còn lại</div>
                    <div class="detail-value"><?php echo htmlspecialchars($game['stock']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html> 