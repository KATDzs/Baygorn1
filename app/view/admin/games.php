<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 3));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
require_once APP_ROOT . '/app/view/helpers.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-games.css">
<main class="admin-games">
    <h1>Quản lý game</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên game</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $game): ?>
                    <tr>
                        <td><?= $game['game_id'] ?? $game['id'] ?></td>
                        <td><?= $game['title'] ?></td>
                        <td><?= format_price($game['price']) ?></td>
                        <td><?= $game['stock'] ?></td>
                        <td>
                            <a href="/Baygorn1/index.php?url=admin/editGame/<?= $game['game_id'] ?? $game['id'] ?>" class="action-btn">Sửa</a>
                            <a href="/Baygorn1/index.php?url=admin/deleteGame/<?= $game['game_id'] ?? $game['id'] ?>" class="action-btn" onclick="return confirm('Bạn có chắc muốn xóa game này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Không có game nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
