<?php
// Define APP_ROOT if not already defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', realpath(__DIR__ . '/../../../'));
}

// Include necessary files
require_once APP_ROOT . '/app/Controller/GiaoDichController.php';
require_once APP_ROOT . '/core/db_connection.php';

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
    <?php include APP_ROOT . '/app/view/layout/header.php'; ?>
  <div class="container py-5">
    <div class="game-detail row g-4 align-items-center">
      <?php if ($game): ?>
        <div class="col-12 col-md-5 text-center">
          <img src="/Baygorn1/asset/img/<?php echo $game['image_url']; ?>" alt="<?php echo $game['title']; ?>" class="game-image img-fluid rounded shadow">
        </div>
        <div class="game-info col-12 col-md-7">
          <h1><?php echo $game['title']; ?></h1>
          <p class="game-description"><?php echo $game['description']; ?></p>
          <p class="game-price">Giá: <?php echo number_format($game['price'], 0, ',', '.'); ?> VNĐ</p>
          <div class="buttons d-flex flex-wrap gap-3 mt-3">
            <a href="/Baygorn1/index.php?url=giaodich/redirectPayment&id=<?php echo $game['game_id']; ?>" class="btn-buy btn btn-danger">MUA NGAY</a>
            <a href="/Baygorn1/app/view/giaodich/pre_order.php?id=<?php echo $game['game_id']; ?>" class="btn-preorder btn btn-outline-primary">ĐẶT HÀNG</a>
          </div>
        </div>
      <?php else: ?>
        <p>Không tìm thấy game</p>
      <?php endif; ?>
    </div>
  </div>
  <?php include APP_ROOT . '/app/view/layout/footer.php'; ?>
</body>
</html>