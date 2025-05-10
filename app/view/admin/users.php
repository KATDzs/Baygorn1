<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/app/view/layout/header.php';
?>
<link rel="stylesheet" href="/Baygorn1/asset/css/admin.css">
<style>
    .admin-users {
        max-width: 1100px;
        margin: 40px auto;
        background: #181c24;
        border-radius: 18px;
        padding: 32px 40px 40px 40px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.18);
        color: #fff;
    }
    .admin-users h1 {
        font-size: 2rem;
        margin-bottom: 32px;
        color: #7ed6df;
        text-align: center;
        letter-spacing: 1px;
    }
    .admin-users table {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: #232a36;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    }
    .admin-users th, .admin-users td {
        padding: 14px 10px;
        text-align: center;
        font-size: 1.08rem;
    }
    .admin-users th {
        background: #202634;
        color: #7ed6df;
        font-weight: 600;
    }
    .admin-users tr:nth-child(even) {
        background: #232a36;
    }
    .admin-users tr:nth-child(odd) {
        background: #1b202a;
    }
    .admin-users td {
        color: #fff;
    }
    @media (max-width: 900px) {
        .admin-users { padding: 18px 4vw; }
    }
</style>
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
