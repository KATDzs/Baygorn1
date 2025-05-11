<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

$title = 'BayGorn1 - Chi tiết tin tức';
$css_files = ['news-detail'];

require_once ROOT_PATH . '/view/layout/header.php';
?>

<main class="news-content container my-5">
    <article class="news-article card p-4 shadow-lg">
        <h1 class="mb-3">Palworld bùng nổ! Game sinh tồn bắt quái vật đang làm mưa làm gió toàn cầu</h1>
        <p class="news-date text-muted">Ngày 28 tháng 4 năm 2025</p>
        <p class="news-intro">Tựa game sinh tồn thế giới mở Palworld, kết hợp giữa bắt quái vật và xây dựng căn cứ, đang gây bão toàn cầu với lối chơi sáng tạo, đồ họa ấn tượng và những "Pal" dễ thương nhưng đầy sức mạnh!</p>
        <div class="news-image-wrapper text-center my-4">
            <img src="../asset/img/games/game_palworld.jpeg" alt="palworld" class="img-fluid rounded shadow">
        </div>
        <section class="news-section mt-4">
            <h2>🔥 Những điểm nổi bật của Palworld</h2>
            <h3>🐉 Hệ thống Pal đa dạng</h3>
            <p>Palworld mang đến hơn 100 loại Pal với thiết kế độc đáo. Mỗi Pal có khả năng riêng như chiến đấu, khai thác, nông trại hoặc chế tạo. Người chơi có thể huấn luyện, nâng cấp, lai tạo để tạo ra các Pal mới mạnh mẽ hơn.</p>
            <h3>🏠 Xây dựng căn cứ</h3>
            <p>Không chỉ bắt Pal, bạn còn có thể xây dựng căn cứ, thiết kế nhà máy tự động hóa với sự trợ giúp của Pal. Các công việc như canh tác, khai thác mỏ hay bảo vệ căn cứ đều có thể giao cho các Pal thực hiện.</p>
            <h3>⚔️ Chiến đấu sinh tồn</h3>
            <p>Palworld không chỉ đơn thuần là game dễ thương, mà còn có yếu tố sinh tồn khốc liệt. Người chơi phải đối mặt với những sinh vật nguy hiểm, kẻ địch săn lùng và cả những trận boss khổng lồ. Vũ khí trong game đa dạng từ súng ống, kiếm đến cung tên.</p>
            <h3>🌍 Thế giới mở rộng lớn</h3>
            <p>Game sở hữu thế giới mở tuyệt đẹp với các hệ sinh thái khác nhau: từ đồng cỏ, núi tuyết, sa mạc cho đến những hòn đảo bay trên trời. Mỗi khu vực đều ẩn chứa những loài Pal đặc biệt để khám phá.</p>
            <h3>🤝 Chế độ co-op nhiều người chơi</h3>
            <p>Palworld hỗ trợ chế độ co-op, cho phép bạn chơi cùng bạn bè, săn bắt Pal và xây dựng đế chế cùng nhau. Đồng thời cũng có chế độ PvP nếu bạn thích tranh tài cùng người chơi khác.</p>
            <h3>🎮 Lối chơi tự do</h3>
            <p>Palworld cho phép bạn tự do lựa chọn phong cách chơi: trở thành nhà huấn luyện Pal, thương nhân Pal, kẻ săn lùng Pal hiếm hoặc đơn giản là một nông dân chăn nuôi Pal.</p>
            <h3>📥 Cách tải và chơi Palworld</h3>
            <p>Palworld hiện đang phát hành trên Steam và Xbox (bao gồm Game Pass). Game vẫn trong giai đoạn Early Access, nhưng đã đạt hơn 10 triệu bản bán ra chỉ trong vòng vài tháng!</p>
            <p>Để trải nghiệm Palworld, bạn có thể tải ngay trên Steam hoặc Xbox Store. Game liên tục được cập nhật với nhiều tính năng, Pal mới và sự kiện hấp dẫn.</p>
            <p>Đừng bỏ lỡ chuyến phiêu lưu kỳ diệu cùng những người bạn Pal đáng yêu nhưng cực kỳ "bá đạo" này!</p>
        </section>
    </article>
</main>

<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>