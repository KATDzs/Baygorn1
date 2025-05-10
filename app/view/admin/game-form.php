<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<style>
    .admin-game-form {
        max-width: 600px;
        margin: 40px auto;
        background: #181c24;
        border-radius: 18px;
        padding: 32px 40px 40px 40px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.18);
        color: #fff;
    }
    .admin-game-form h1 {
        font-size: 2rem;
        margin-bottom: 32px;
        color: #7ed6df;
        text-align: center;
        letter-spacing: 1px;
    }
    .admin-game-form form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .admin-game-form label {
        font-weight: 600;
        color: #7ed6df;
        margin-bottom: 4px;
    }
    .admin-game-form input[type="text"],
    .admin-game-form input[type="number"],
    .admin-game-form textarea,
    .admin-game-form select {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 1.08rem;
        background: #232a36;
        color: #fff;
        margin-bottom: 8px;
        outline: none;
        transition: box-shadow 0.2s;
    }
    .admin-game-form input[type="text"]:focus,
    .admin-game-form input[type="number"]:focus,
    .admin-game-form textarea:focus,
    .admin-game-form select:focus {
        box-shadow: 0 0 0 2px #7ed6df;
    }
    .admin-game-form button[type="submit"] {
        background: #7ed6df;
        color: #232a36;
        border: none;
        border-radius: 8px;
        padding: 12px 0;
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 10px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .admin-game-form button[type="submit"]:hover {
        background: #22a6b3;
        color: #fff;
    }
    .admin-game-form img {
        max-width: 120px;
        border-radius: 8px;
        margin-top: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    @media (max-width: 700px) {
        .admin-game-form { padding: 18px 4vw; }
    }
</style>
<main class="admin-game-form">
    <h1><?= isset($game) ? 'Sửa game' : 'Thêm game mới' ?></h1>
    <form method="post" enctype="multipart/form-data">
        <label>Tên game:</label>
        <input type="text" name="title" value="<?= isset($game['title']) ? htmlspecialchars($game['title']) : '' ?>" required>
        <label>Mô tả:</label>
        <textarea name="description" required><?= isset($game['description']) ? htmlspecialchars($game['description']) : '' ?></textarea>
        <label>Chi tiết:</label>
        <textarea name="detail_desc"><?= isset($game['detail_desc']) ? htmlspecialchars($game['detail_desc']) : '' ?></textarea>
        <label>Nền tảng:</label>
        <input type="text" name="platform" value="<?= isset($game['platform']) ? htmlspecialchars($game['platform']) : '' ?>">
        <label>Giá:</label>
        <input type="number" name="price" value="<?= isset($game['price']) ? $game['price'] : 0 ?>" min="0" required>
        <label>Số lượng:</label>
        <input type="number" name="stock" value="<?= isset($game['stock']) ? $game['stock'] : 0 ?>" min="0" required>
        <label>Ảnh:</label>
        <input type="file" name="image">
        <?php if (isset($game['image_url']) && $game['image_url']): ?>
            <img src="/Baygorn1/<?= $game['image_url'] ?>" alt="Game Image">
        <?php endif; ?>
        <button type="submit">Lưu</button>
    </form>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
