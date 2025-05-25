<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin cá nhân - BayGorn1</title>
    <link rel="stylesheet" href="/Baygorn1/asset/css/header.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/auth.css">
    <link rel="stylesheet" href="/Baygorn1/asset/css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .profile-main {
            max-width: 1100px;
            margin: 40px auto 60px auto;
            padding: 0 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2.5rem;
        }
        .profile-sidebar {
            flex: 0 0 320px;
            background: #18191c;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0002;
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 260px;
        }
        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.2rem;
            border: 3px solid #ff4655;
            background: #23232b;
        }
        .profile-username {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff4655;
            margin-bottom: 0.5rem;
        }
        .profile-role {
            font-size: 1.05rem;
            color: #aaa;
            margin-bottom: 1.2rem;
        }
        .profile-info-list {
            width: 100%;
            margin-bottom: 2rem;
        }
        .profile-info-list .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 0;
            border-bottom: 1px solid #23232b;
        }
        .profile-info-list .info-label {
            color: #aaa;
            font-size: 1rem;
        }
        .profile-info-list .info-value {
            color: #fff;
            font-weight: 500;
            font-size: 1rem;
        }
        .profile-edit-btn {
            display: inline-block;
            background: #ff4655;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.7rem 2.2rem;
            font-size: 1.08rem;
            font-weight: 600;
            margin-top: 1.2rem;
            transition: background 0.2s;
            text-decoration: none;
        }
        .profile-edit-btn:hover {
            background: #e60012;
        }
        .profile-content {
            flex: 1 1 0;
            min-width: 320px;
            background: #1f1f22;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0002;
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            display: flex;
            flex-direction: column;
        }
        .profile-section-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #4caf50;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }        .purchased-games-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            width: 100%;
            align-items: stretch;
            overflow-x: unset;
            padding-bottom: 0;
        }
        .purchased-game-item {
            background: #18191c;
            padding: 18px 24px 16px 24px;
            border-radius: 12px;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            text-align: left;
            box-shadow: 0 2px 8px #0002;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 24px;
        }
        .purchased-game-item img {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            margin-bottom: 0;
            object-fit: cover;
            background: #23232b;
        }
        .purchased-game-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .purchased-game-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
            color: #ff4d4f;
        }
        .purchased-game-platform {
            font-size: 0.98rem;
            color: #aaa;
            margin-bottom: 2px;
        }
        .btn-download-game {
            margin-left: auto;
            font-size: 1rem;
            padding: 8px 18px;
        }
        @media (max-width: 700px) {
            .purchased-game-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 8px;
            }
            .purchased-game-item img {
                width: 60px;
                height: 44px;
            }
            .btn-download-game {
                width: 100%;
                margin-left: 0;
            }
        }
        @media (max-width: 900px) {
            .profile-main { flex-direction: column; gap: 1.5rem; }
            .profile-sidebar, .profile-content { max-width: 100%; min-width: 0; }
            .profile-content { padding: 1.5rem 1rem; }
        }
    </style>
</head>
<body>
<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $config['baseURL'] . 'auth/login');
    exit();
}

// Include header
include_once BASE_PATH . '/app/view/layout/header.php';
?>
<div class="profile-main">
    <aside class="profile-sidebar">
        <img src="/Baygorn1/asset/img/avatar.jpg" alt="Avatar" class="profile-avatar">
        <div class="profile-username"><?php echo htmlspecialchars($user['username']); ?></div>
        <div class="profile-role"><?php echo htmlspecialchars($user['role'] ?? 'Người dùng'); ?></div>
        <div class="profile-info-list">
            <div class="info-row">
                <span class="info-label">Họ và tên</span>
                <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày tạo</span>
                <span class="info-value"><?php echo isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'N/A'; ?></span>
            </div>
        </div>
        <a href="<?php echo $config['baseURL']; ?>auth/edit-profile" class="profile-edit-btn">Chỉnh sửa thông tin</a>
    </aside>
    <section class="profile-content">
        <div class="profile-section-title">Game đã mua</div>
        <?php 
        // Ưu tiên lấy dữ liệu từ $purchasedGames, nếu rỗng thì lấy từ $history (nếu controller truyền sang)
        $gamesToShow = !empty($purchasedGames) ? $purchasedGames : ($history ?? []);
        if (!empty($gamesToShow)): ?>
        <div class="purchased-games-list">
            <?php foreach ($gamesToShow as $game): ?>
                <div class="purchased-game-item">
                    <img src="/Baygorn1/asset/img/games/<?php echo htmlspecialchars($game['image_url']); ?>" alt="<?php echo htmlspecialchars($game['game_title'] ?? $game['title']); ?>">
                    <div class="purchased-game-info">
                        <div class="purchased-game-title"> <?php echo htmlspecialchars($game['game_title'] ?? $game['title']); ?> </div>
                        <div class="purchased-game-platform"> <?php echo htmlspecialchars($game['platform'] ?? ''); ?> </div>
                    </div>
                    <button type="button" class="btn btn-success btn-download-game" data-title="<?php echo htmlspecialchars($game['game_title'] ?? $game['title']); ?>">Tải game về</button>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div style="color:#aaa;font-size:1.08rem;">Bạn chưa mua game nào.</div>
        <?php endif; ?>
    </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-download-game').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Bạn sẽ nhận được link tải game sau khi xác nhận!');
        });
    });
});
</script>
<?php include_once BASE_PATH . '/app/view/layout/footer.php'; ?>
</body>
</html>