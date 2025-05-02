<?php
// Define APP_ROOT if not already defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(dirname(__FILE__))));
}

// Include necessary files
require_once '../../Controller/GiaoDichController.php';
require_once __DIR__ . '/../../../core/db_connection.php';

$giaoDichController = new GiaoDichController($conn);
$game = $giaoDichController->getGameDetail($_GET['id'] ?? 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BayGorn1 - Mua game</title>
  <link rel="stylesheet" href="/Baygorn1/asset/css/giaodich.css">
  <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
  <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
</head>
<body>
    <?php include '../layout/header.php'; ?>
  <div class="container">
    <div class="game-detail">
      <?php if ($game): ?>
        <img src="/Baygorn1/asset/img/<?php echo $game['image_url']; ?>" alt="<?php echo $game['title']; ?>" class="game-image">
        <div class="game-info">
          <h1><?php echo $game['title']; ?></h1>
          <p class="game-description"><?php echo $game['description']; ?></p>
          <p class="game-price">Giá: <?php echo number_format($game['price'], 0, ',', '.'); ?> VNĐ</p>
          <div class="buttons">
            <a href="/Baygorn1/index.php?url=giaodich/redirectPayment&id=<?php echo $game['game_id']; ?>" class="btn-buy">MUA NGAY</a>
            <a href="/Baygorn1/app/view/giaodich/pre_order.php?id=<?php echo $game['game_id']; ?>" class="btn-preorder">ĐẶT HÀNG</a>
          </div>
        </div>
      <?php else: ?>
        <p>Không tìm thấy game</p>
      <?php endif; ?>
    </div>
  </div>
  <?php include '../layout/footer.php'; ?>
</body>
</html>