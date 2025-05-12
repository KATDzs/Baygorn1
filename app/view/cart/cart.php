<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
$css_files = ['cart', 'header', 'footer'];
require_once ROOT_PATH . '/view/layout/header.php';
?>

<div class="cart-container">
    <h1>Your Cart</h1>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="/Baygorn1/game">Continue shopping</a>.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <?php
                            $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/Baygorn1/asset/img/games/' . $item['image_url'];
                            $imgUrl = '/Baygorn1/asset/img/games/' . htmlspecialchars($item['image_url']);
                            $defaultImg = '/Baygorn1/asset/img/default-game.jpg';
                            if (!file_exists($imgPath) || empty($item['image_url'])) {
                                $imgUrl = $defaultImg;
                            }
                            ?>
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="cart-item-image">
                        </td>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo number_format($item['price'], 2); ?> $</td>
                        <td>
                            <form method="POST" action="/Baygorn1/index.php?url=cart/update">
                                <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> $</td>
                        <td>
                            <form method="POST" action="/Baygorn1/index.php?url=cart/remove">
                                <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-total">
            <h3>Total: <?php echo number_format($total, 2); ?> $</h3>
        </div>
        <div class="cart-actions">
            <a href="/Baygorn1/index.php?url=order/checkout" class="checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>