<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 3));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<style>
    .admin-games {
        max-width: 1100px;
        margin: 40px auto;
        background: #181c24;
        border-radius: 18px;
        padding: 32px 40px 40px 40px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.18);
        color: #fff;
    }
    .admin-games h1 {
        font-size: 2rem;
        margin-bottom: 32px;
        color: #7ed6df;
        text-align: center;
        letter-spacing: 1px;
    }
    .admin-games table {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: #232a36;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    }
    .admin-games th, .admin-games td {
        padding: 14px 10px;
        text-align: center;
        font-size: 1.08rem;
    }
    .admin-games th {
        background: #202634;
        color: #7ed6df;
        font-weight: 600;
    }
    .admin-games tr:nth-child(even) {
        background: #232a36;
    }
    .admin-games tr:nth-child(odd) {
        background: #1b202a;
    }
    .admin-games td {
        color: #fff;
    }
    .admin-games .action-btn {
        background: #7ed6df;
        color: #232a36;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        margin: 0 4px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .admin-games .action-btn:hover {
        background: #22a6b3;
        color: #fff;
    }
    @media (max-width: 900px) {
        .admin-games { padding: 18px 4vw; }
    }
</style>
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
                        <td><?= number_format($game['price']) ?> VNĐ</td>
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
