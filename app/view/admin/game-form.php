<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';

$editId = isset($game['game_id']) ? $game['game_id'] : (isset($game['id']) ? $game['id'] : (isset($id) ? $id : (isset($_POST['game_id']) ? $_POST['game_id'] : '')));
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-game-form.css">
<main class="admin-game-form">
    <h1><?= isset($game) ? 'Sửa game' : 'Thêm game mới' ?></h1>
    <form method="POST" action="<?= $editId ? '/Baygorn1/index.php?url=admin/editGame/' . $editId : '/Baygorn1/index.php?url=admin/addGame' ?>" enctype="multipart/form-data">
        <?php if ($editId): ?>
            <input type="hidden" name="game_id" value="<?= $editId ?>">
        <?php endif; ?>
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
        <label>Danh mục:</label>
        <select name="categories[]" multiple>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>" 
                    <?= isset($game['categories']) && in_array($category['category_id'], array_column($game['categories'], 'category_id')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Ảnh:</label>
        <input type="file" name="image" accept="image/*">
        <?php if (isset($game['image_url']) && $game['image_url']): ?>
            <img src="/Baygorn1/<?= $game['image_url'] ?>" alt="Game Image">
        <?php endif; ?>
        <button type="submit">Lưu</button>
    </form>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
