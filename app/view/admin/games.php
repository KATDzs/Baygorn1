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
    <?php if (isset($_GET['msg'])): ?>
        <div class="admin-alert" style="margin-bottom:16px;">
            <?php
            switch ($_GET['msg']) {
                case 'delete_success':
                    echo '<span style="color:green;">Xóa game thành công!</span>';
                    break;
                case 'delete_fail':
                    echo '<span style="color:red;">Xóa game thất bại!</span>';
                    break;
                case 'invalid_id':
                    echo '<span style="color:red;">ID game không hợp lệ!</span>';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên game</th>
                <th>Giá</th>
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
                        <td>
                            <a href="/Baygorn1/index.php?url=admin/editGame/<?= isset($game['game_id']) ? $game['game_id'] : $game['id'] ?>" class="action-btn">Sửa</a>
                            <a href="/Baygorn1/index.php?url=admin/deleteGame/<?= isset($game['game_id']) ? $game['game_id'] : $game['id'] ?>" class="action-btn" onclick="return confirm('Bạn có chắc muốn xóa game này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Không có game nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
