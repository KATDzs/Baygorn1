<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/view/layout/header.php';
?>
<body>
    <main class="news-content">
        <article class="news-article">
            <h1>I AM LEGION - Đế chế bóng tối trong thế giới số</h1>
            <p class="news-date">Ngày 28 tháng 4 năm 2025</p>
            <p class="news-intro">"I AM LEGION" không chỉ là một câu khẩu hiệu, mà còn là tuyên ngôn của những chiến binh trong thế giới ảo. Khi công nghệ thống trị nhân loại, LEGION trỗi dậy như một thế lực không thể ngăn cản.</p>
            <div class="news-image-wrapper">
                <img src="../asset/img/games/game_i_am_legion.jpg" alt="I AM LEGION">
            </div>
            <section class="news-section">
                <h2>🕶️ LEGION - Biểu tượng của kỷ nguyên đen tối</h2>

                <h3>🌐 Thế giới ảo hoang tàn</h3>
                <p>LEGION tồn tại trong một thế giới nơi các thành phố đã bị nuốt chửng bởi mạng lưới siêu máy tính. Các tập đoàn khổng lồ kiểm soát từng nhịp thở của nhân loại. LEGION chiến đấu vì tự do trong bóng tối, giữa những đường dữ liệu chằng chịt.</p>

                <h3>💻 Hack để sinh tồn</h3>
                <p>Không còn chỗ cho những kẻ yếu. Trong LEGION, kỹ năng hack không chỉ để sống sót mà còn để tấn công, phá vỡ trật tự và giải phóng những tâm trí bị giam cầm.</p>

                <h3>🛡️ Chiến binh mạng</h3>
                <p>Mỗi thành viên LEGION là một chiến binh mang giáp ảo hóa, có thể chiến đấu trong không gian số lẫn thế giới thực. Công nghệ và bản lĩnh quyết định ai sẽ đứng vững.</p>
                <div class="news-image-wrapper">
                <img src="../img/iamlegion.jpg" alt="I AM LEGION">
            </div>
                <h3>🔴 Biểu tượng: I AM LEGION</h3>
                <p>"Chúng ta là nhiều, nhưng là một. Chúng ta không có khuôn mặt, không có tên tuổi, chỉ có sứ mệnh." - Đó là lời thề bất diệt của LEGION.</p>

                <h3>📢 LEGION trong cộng đồng</h3>
                <p>Ngày càng nhiều người gia nhập LEGION, tạo thành mạng lưới chống đối vô hình trên khắp thế giới. Dự án "I AM LEGION" không chỉ là game, mà còn là tuyên ngôn nghệ thuật về tự do cá nhân giữa thời đại kỹ thuật số áp bức.</p>

                <h3>🎮 Trải nghiệm LEGION</h3>
                <p>Dự án LEGION sắp ra mắt bản thử nghiệm mở rộng trong năm 2025, hứa hẹn một cuộc chiến mạng đỉnh cao, nơi mọi quyết định đều có thể thay đổi số phận thế giới.</p>

                <p>Hãy sẵn sàng. Chúng tôi là LEGION.</p>
            </section>
        </article>
    </main>
    <script>
    // Đảm bảo nội dung không bị menu che: tự động padding-top đúng chiều cao header
    function adjustNewsPadding() {
      var header = document.querySelector('.navbar');
      var main = document.querySelector('.news-main-container, .news-detail-main-container, main');
      if (header && main) {
        var headerHeight = header.offsetHeight;
        main.style.paddingTop = (headerHeight + 24) + 'px'; // +24 cho đẹp
      }
    }
    window.addEventListener('DOMContentLoaded', adjustNewsPadding);
    window.addEventListener('resize', adjustNewsPadding);
    </script>
<?php require_once ROOT_PATH . '/view/layout/footer.php'; ?>
</body>
</html>