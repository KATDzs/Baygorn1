

<h2>🛒 Giỏ hàng của bạn</h2>

<?php if (empty($cartItems)): ?>
    <p>Chưa có sản phẩm nào trong giỏ hàng.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= $item['Name'] ?></td>
                    <td><?= number_format($item['Price'], 0) ?> VNĐ</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['Price'] * $item['quantity'], 0) ?> VNĐ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
