<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['cart', 'header', 'footer'];
require_once ROOT_PATH . '/view/layout/header.php';
require_once APP_ROOT . '/app/view/helpers.php';
?>

<div class="cart-container">
    <h1 class="cart-title-gradient">🛒 Giỏ Hàng Của Bạn</h1>
    <?php if (empty($cartItems)): ?>
        <div class="cart-empty">
            <img src="/Baygorn1/asset/img/empty-cart.png" alt="Empty Cart" class="cart-empty-img">
            <p>Giỏ hàng của bạn đang trống.<br><a href="/Baygorn1/game" class="cart-link">Tiếp tục mua sắm</a></p>
        </div>
    <?php else: ?>
        <div class="cart-list">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-card">
                    <div class="cart-card-img-wrap">
                        <?php
                        $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/Baygorn1/asset/img/games/' . $item['image_url'];
                        $imgUrl = '/Baygorn1/asset/img/games/' . htmlspecialchars($item['image_url']);
                        $defaultImg = '/Baygorn1/asset/img/default-game.jpg';
                        if (!file_exists($imgPath) || empty($item['image_url'])) {
                            $imgUrl = $defaultImg;
                        }
                        ?>
                        <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="cart-card-img">
                    </div>
                    <div class="cart-card-info">
                        <div class="cart-card-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="cart-card-desc"><?php echo htmlspecialchars($item['description'] ?? ''); ?></div>
                        <div class="cart-card-price">
                            <span><?php echo format_price($item['price']); ?></span>
                        </div>
                    </div>
                    <form method="POST" action="/Baygorn1/index.php?url=cart/remove" class="cart-card-remove-form">
                        <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                        <button type="submit" class="cart-card-remove-btn">Xóa</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-total-modern">
            <span>Tổng cộng:</span>
            <span class="cart-total-amount"><?php echo format_price($total); ?></span>
        </div>
        <div class="cart-actions-modern">
            <a href="/Baygorn1/index.php?url=cart/checkout" class="cart-checkout-btn">Thanh toán ngay</a>
        </div>
    <?php endif; ?>
</div>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>