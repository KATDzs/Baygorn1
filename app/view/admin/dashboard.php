<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-dashboard.css">
<main class="admin-dashboard">
    <h1 style="font-size:2.2rem; margin-bottom:32px; letter-spacing:1px; color:#7ed6df; text-align:center;">Admin Dashboard</h1>
    <div class="admin-stats">
        <div class="stat">
            <h2>Người dùng</h2>
            <p><?= isset($stats['total_users']) ? $stats['total_users'] : 0 ?></p>
        </div>
        <div class="stat">
            <h2>Game</h2>
            <p><?= isset($stats['total_games']) ? $stats['total_games'] : 0 ?></p>
        </div>
        <div class="stat">
            <h2>Đơn hàng</h2>
            <p><?= isset($stats['total_orders']) ? $stats['total_orders'] : 0 ?></p>
        </div>
        <div class="stat">
            <h2>Doanh thu</h2>
            <p><?= isset($stats['total_revenue']) ? number_format($stats['total_revenue']) : 0 ?> VNĐ</p>
        </div>
    </div>
    <section class="recent-orders">
        <h2>Đơn hàng gần đây</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người mua</th>
                    <th>Ngày</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stats['recent_orders'])): ?>
                    <?php foreach ($stats['recent_orders'] as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= $order['username'] ?></td>
                            <td><?= $order['order_date'] ?></td>
                            <td><?= number_format($order['total_amount']) ?> VNĐ</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Không có đơn hàng gần đây.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <section class="top-games">
        <h2>Game bán chạy</h2>
        <ul>
            <?php if (!empty($stats['top_games'])): ?>
                <?php foreach ($stats['top_games'] as $game): ?>
                    <li><?= $game['title'] ?> (<?= $game['total_sales'] ?> lượt bán)</li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Chưa có dữ liệu.</li>
            <?php endif; ?>
        </ul>
    </section>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
