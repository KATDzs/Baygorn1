<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-game-form.css">
<main class="admin-game-form container my-5">
    <h1 class="mb-4"><?= isset($game) ? 'Sửa game' : 'Thêm game mới' ?></h1>
    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label">Tên game:</label>
            <input type="text" name="title" value="<?= isset($game['title']) ? htmlspecialchars($game['title']) : '' ?>" required class="form-control">
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Nền tảng:</label>
            <input type="text" name="platform" value="<?= isset($game['platform']) ? htmlspecialchars($game['platform']) : '' ?>" class="form-control">
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả:</label>
            <textarea name="description" required class="form-control" rows="2"><?= isset($game['description']) ? htmlspecialchars($game['description']) : '' ?></textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Chi tiết:</label>
            <textarea name="detail_desc" class="form-control" rows="3"><?= isset($game['detail_desc']) ? htmlspecialchars($game['detail_desc']) : '' ?></textarea>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label">Giá:</label>
            <input type="number" name="price" value="<?= isset($game['price']) ? $game['price'] : 0 ?>" min="0" required class="form-control">
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label">Số lượng:</label>
            <input type="number" name="stock" value="<?= isset($game['stock']) ? $game['stock'] : 0 ?>" min="0" required class="form-control">
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Ảnh:</label>
            <input type="file" name="image" class="form-control">
            <?php if (isset($game['image_url']) && $game['image_url']): ?>
                <img src="/Baygorn1/<?= $game['image_url'] ?>" alt="Game Image" class="img-fluid mt-2" style="max-width:120px;">
            <?php endif; ?>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Lưu</button>
        </div>
    </form>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
