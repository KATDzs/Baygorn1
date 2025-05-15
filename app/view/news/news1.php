<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}
require_once ROOT_PATH . '/view/layout/header.php';
?>
<body>
    <main class="news-content">
        <article class="news-article">
            <h1>Minecraft 1.21.5 "Spring to Life" chính thức ra mắt: Thế giới sống động hơn bao giờ hết!</h1>
            <p class="news-date">Ngày 25 tháng 3 năm 2025</p>
            <p class="news-intro">Mojang đã phát hành bản cập nhật Minecraft Java Edition 1.21.5 với tên gọi "Spring to Life" - bản cập nhật mùa xuân đầu tiên của năm, mang đến nhiều thay đổi hấp dẫn về sinh vật, thực vật và âm thanh môi trường, giúp thế giới Minecraft trở nên sống động và chân thực hơn bao giờ hết.</p>
            <div class="news-image">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQnZGBjyObGnI0KLWwspraVEUKVOjeFGW96Q&s" alt="Minecraft 1.21.5">
            </div>
            <section class="news-section">
                <h2>🌿 Những điểm nổi bật trong bản cập nhật</h2>
                
                <h3>🐄 Biến thể động vật theo vùng khí hậu</h3>
                <p>Lần đầu tiên, các loài vật nuôi như lợn, bò và gà có thêm các biến thể "ấm" và "lạnh", tùy thuộc vào biome mà chúng sinh sống. Ví dụ, ở vùng lạnh, bạn sẽ thấy gà xám và bò lông dài, trong khi ở vùng ấm, gà nâu và lợn có màu sắc khác biệt. Ngoài ra, gà còn có thể đẻ trứng màu xanh (biến thể lạnh) và nâu (biến thể ấm).</p>
                
                <h3>🐺 Âm thanh sói đa dạng</h3>
                <p>Sói giờ đây có 7 biến thể âm thanh khác nhau như "Big", "Cute", "Puglin", "Angry", "Grumpy", "Sad" và "Classic", mang đến trải nghiệm âm thanh phong phú và độc đáo cho mỗi con sói trong game.</p>
                
                <h3>🌳 Cây đổ và hiệu ứng lá rơi</h3>
                <p>Cây đổ xuất hiện như một yếu tố trang trí mới, có thể được tìm thấy ở các biome tương ứng với loại gỗ của chúng. Ngoài ra, tất cả các loại lá cây giờ đây có hiệu ứng hạt lá rơi, tăng thêm sự sống động cho môi trường.</p>
                
                <h3>Thực vật mới</h3>
                <p>Bản cập nhật bổ sung nhiều loại thực vật mới như:</p>
                <ul>
                    <li>Firefly Bush: Xuất hiện gần nước ở các biome như Swamp, Mangrove Swamp và Badlands, phát sáng vào ban đêm.</li>
                    <li>Leaf Litter: Lớp lá rụng trang trí, có thể tìm thấy trong Forests, Dark Forests và Wooded Badlands.</li>
                    <li>Wildflowers: Hoa dại mọc ở Birch Forests, Old Growth Birch Forests và Meadows.</li>
                    <li>Bush: Bụi cây nhỏ xuất hiện ở nhiều biome khác nhau.</li>
                    <li>Short Dry Grass và Tall Dry Grass: Cỏ khô ngắn và cao, mọc ở Desert và Badlands.</li>
                    <li>Cactus Flower: Hoa mọc trên xương rồng, có thể dùng để chế tạo thuốc nhuộm hồng.</li>
                </ul>
                
                <h3>🔊 Âm thanh môi trường mới</h3>
                <p>Desert và Badlands giờ đây có âm thanh môi trường mới như tiếng gió thổi qua cát và đất sét, tăng cường cảm giác chân thực khi khám phá các vùng đất này.</p>
                
                <h3>⚙️ Cải tiến kỹ thuật và chất lượng cuộc sống</h3>
                <ul>
                    <li>Trứng sinh vật (Spawn Eggs): Được thiết kế lại với hình dạng và màu sắc phản ánh đặc điểm của từng sinh vật, giúp người chơi dễ dàng nhận biết hơn.</li>
                    <li>Beacon: Tia sáng của beacon giờ đây có thể hiển thị xa hơn (lên đến 2048 khối) và dày hơn khi nhìn từ xa, giúp người chơi dễ dàng xác định vị trí.</li>
                    <li>Lodestone: Có công thức chế tạo mới và có thể tìm thấy trong các Ruined Portals.</li>
                    <li>Bundles: Giờ đây có thể tìm thấy trong một số rương ở các ngôi làng.</li>
                    <li>Cập nhật giao dịch: Các giao dịch của Cartographer và Wandering Trader đã được cập nhật, mang đến nhiều lựa chọn mới cho người chơi.</li>
                </ul>
                
                <h3>📥 Cách trải nghiệm bản cập nhật</h3>
                <p>Minecraft 1.21.5 đã chính thức phát hành cho Java Edition. Người chơi có thể cập nhật qua Minecraft Launcher. Đối với Bedrock Edition, bản cập nhật tương ứng là 1.21.70.</p>
                <p>Để biết thêm chi tiết và hướng dẫn cụ thể, bạn có thể truy cập trang chính thức của Mojang tại Minecraft.net.</p>
                <p>Bản cập nhật "Spring to Life" hứa hẹn mang đến một thế giới Minecraft tươi mới và sống động hơn bao giờ hết. Hãy cùng khám phá và tận hưởng những điều thú vị mà bản cập nhật này mang lại!</p>
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