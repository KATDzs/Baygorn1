<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', realpath(__DIR__ . '/../../../'));
}
$css_files = ['giaodich', 'header', 'footer'];
require_once APP_ROOT . '/app/view/layout/header.php';

require_once APP_ROOT . '/app/Controller/GiaoDichController.php';
require_once APP_ROOT . '/core/db_connection.php';

$giaoDichController = new GiaoDichController($conn);
$game = $giaoDichController->getGameDetail($_GET['id'] ?? 1);
?>
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
<?php include APP_ROOT . '/app/view/layout/footer.php'; ?>