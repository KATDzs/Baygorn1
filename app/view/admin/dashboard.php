<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<style>
    .admin-dashboard {
        max-width: 1200px;
        margin: 40px auto;
        background: #181c24;
        border-radius: 18px;
        padding: 32px 40px 40px 40px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.18);
        color: #fff;
    }
    .admin-stats {
        display: flex;
        gap: 32px;
        margin-bottom: 40px;
        justify-content: space-between;
    }
    .admin-stats .stat {
        flex: 1;
        background: linear-gradient(135deg, #2d3a4b 60%, #3e4a5e 100%);
        border-radius: 16px;
        padding: 32px 0 24px 0;
        text-align: center;
        box-shadow: 0 2px 16px rgba(0,0,0,0.12);
        transition: transform 0.2s;
        position: relative;
    }
    .admin-stats .stat:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    .admin-stats .stat h2 {
        font-size: 1.3rem;
        margin-bottom: 12px;
        color: #7ed6df;
        letter-spacing: 1px;
    }
    .admin-stats .stat p {
        font-size: 2.8rem;
        font-weight: bold;
        color: #fff;
        margin: 0;
    }
    .recent-orders {
        margin-bottom: 36px;
    }
    .recent-orders h2, .top-games h2 {
        font-size: 1.5rem;
        margin-bottom: 18px;
        color: #7ed6df;
        letter-spacing: 1px;
    }
    .recent-orders table {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: #232a36;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    }
    .recent-orders th, .recent-orders td {
        padding: 14px 10px;
        text-align: center;
        font-size: 1.08rem;
    }
    .recent-orders th {
        background: #202634;
        color: #7ed6df;
        font-weight: 600;
    }
    .recent-orders tr:nth-child(even) {
        background: #232a36;
    }
    .recent-orders tr:nth-child(odd) {
        background: #1b202a;
    }
    .recent-orders td {
        color: #fff;
    }
    .top-games ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .top-games li {
        font-size: 1.15rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
    }
    .top-games li:before {
        content: '\1F3AE'; /* gamepad emoji */
        font-size: 1.2em;
        margin-right: 8px;
        color: #7ed6df;
    }
    @media (max-width: 900px) {
        .admin-stats { flex-direction: column; gap: 18px; }
        .admin-dashboard { padding: 18px 4vw; }
    }
</style>
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
