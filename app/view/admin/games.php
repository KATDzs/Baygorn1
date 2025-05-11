<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 3));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-games.css">
<main class="admin-games container my-5">
    <h1 class="mb-4">Quản lý game</h1>
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
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
                        <td><?= number_format($game['price']) ?> VNĐ</td>
                        <td><?= $game['stock'] ?></td>
                        <td>
                            <a href="/Baygorn1/index.php?url=admin/editGame/<?= $game['game_id'] ?? $game['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/Baygorn1/index.php?url=admin/deleteGame/<?= $game['game_id'] ?? $game['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa game này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Không có game nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
