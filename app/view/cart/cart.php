<div class="cart-container container my-5">
    <h1>Your Cart</h1>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="/Baygorn1/game">Continue shopping</a>.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="cart-table table table-bordered align-middle">
                <thead class="table-light">
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
                                <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="cart-item-image img-thumbnail" style="max-width:60px;">
                            </td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?> $</td>
                            <td>
                                <form method="POST" action="/Baygorn1/index.php?url=cart/update" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input form-control form-control-sm" style="width:70px;">
                                    <button type="submit" class="update-btn btn btn-outline-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> $</td>
                            <td>
                                <form method="POST" action="/Baygorn1/index.php?url=cart/remove">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <button type="submit" class="remove-btn btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="cart-total">
            <h3>Total: <?php echo number_format($total, 2); ?> $</h3>
        </div>
        <div class="cart-actions">
            <a href="/Baygorn1/index.php?url=order/checkout" class="checkout-btn btn btn-success">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>