<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', realpath(__DIR__ . '/../../../'));
}
require_once APP_ROOT . '/app/view/layout/header.php';
require_once APP_ROOT . '/app/view/helpers.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/history-custom.css">
<div class="history-section">
    <div class="history-title">Lịch sử mua hàng</div>
    <?php if (!empty($history)): ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Game</th>
                    <th>Ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Ngày mua</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $i => $item): ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td style="font-weight:600; color:#7ed6df; letter-spacing:0.5px;"> <?= htmlspecialchars($item['game_title']) ?> </td>
                        <td><img src="/Baygorn1/asset/img/games/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['game_title']) ?>"></td>
                        <td><?= $item['quantity'] ?></td>
                        <td style="color:#e17055; font-weight:600;"> <?= format_price($item['price']) ?> </td>
                        <td><?= date('d/m/Y H:i', strtotime($item['created_at'] ?? $item['purchased_at'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($totalPages > 1): ?>
            <nav class="history-pagination" aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item<?= $p == $currentPage ? ' active' : '' ?>">
                            <a class="page-link" href="?page=<?= $p ?>">Trang <?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="history-empty">Bạn chưa có giao dịch nào.</div>
    <?php endif; ?>
</div>
<?php require_once APP_ROOT . '/app/view/layout/footer.php'; ?>
