<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<link rel="stylesheet" href="/Baygorn1/asset/css/admin-users.css">
<main class="admin-users">
    <h1>Quản lý người dùng</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Quyền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['full_name'] ?></td>
                        <td><?= $user['is_admin'] ? '<span style="color:#f6e58d;font-weight:bold">Admin</span>' : 'User' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Không có người dùng nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php require_once ROOT_PATH . '/app/view/layout/footer.php'; ?>
