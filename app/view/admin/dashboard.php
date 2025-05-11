<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-dashboard.css">
<main class="admin-dashboard container my-5">
    <h1 class="text-center mb-4" style="font-size:2.2rem; letter-spacing:1px; color:#7ed6df;">Admin Dashboard</h1>
    <div class="admin-stats row g-4 mb-5">
        <div class="stat col-6 col-md-3">
            <h2>Người dùng</h2>
            <p><?= isset($stats['total_users']) ? $stats['total_users'] : 0 ?></p>
        </div>
        <div class="stat col-6 col-md-3">
            <h2>Game</h2>
            <p><?= isset($stats['total_games']) ? $stats['total_games'] : 0 ?></p>
        </div>
        <div class="stat col-6 col-md-3">
            <h2>Đơn hàng</h2>
            <p><?= isset($stats['total_orders']) ? $stats['total_orders'] : 0 ?></p>
        </div>
        <div class="stat col-6 col-md-3">
            <h2>Doanh thu</h2>
            <p><?= isset($stats['total_revenue']) ? number_format($stats['total_revenue']) : 0 ?> VNĐ</p>
        </div>
    </div>
    <section class="recent-orders mb-5">
        <h2>Đơn hàng gần đây</h2>
        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
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
        </div>
    </section>
    <section class="top-games mb-5">
        <h2>Game bán chạy</h2>
        <ul class="list-group">
            <?php if (!empty($stats['top_games'])): ?>
                <?php foreach ($stats['top_games'] as $game): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $game['title'] ?>
                        <span class="badge bg-primary rounded-pill"><?= $game['total_sales'] ?> lượt bán</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">Chưa có dữ liệu.</li>
            <?php endif; ?>
        </ul>
    </section>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
