<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-game-form.css">
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
