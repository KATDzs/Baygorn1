<?php
?>

<main class="about-page">
    <section class="hero-section">
        <div class="hero-content">
            <h1>Về Baygorn Games</h1>
            <p>Nơi những trải nghiệm game tuyệt vời bắt đầu</p>
        </div>
    </section>

    <section class="mission-section">
        <div class="container">
            <h2>Sứ mệnh của chúng tôi</h2>
            <p>Tại Baygorn Games, chúng tôi tin rằng trò chơi là cầu nối văn hóa và là phương tiện giải trí tuyệt vời. Sứ mệnh của chúng tôi là mang đến những trải nghiệm game chất lượng cao, đa dạng và phù hợp với mọi người chơi.</p>
        </div>
    </section>

    <section class="values-section">
        <div class="container">
            <h2>Giá trị cốt lõi</h2>
            <div class="values-grid">
                <div class="value-card">
                    <h3>Chất lượng</h3>
                    <p>Cam kết mang đến những sản phẩm game chất lượng cao nhất</p>
                </div>
                <div class="value-card">
                    <h3>Sáng tạo</h3>
                    <p>Không ngừng đổi mới và sáng tạo trong phát triển game</p>
                </div>
                <div class="value-card">
                    <h3>Cộng đồng</h3>
                    <p>Xây dựng cộng đồng game thủ văn minh và thân thiện</p>
                </div>
                <div class="value-card">
                    <h3>Trách nhiệm</h3>
                    <p>Phát triển game có trách nhiệm với xã hội</p>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <h2>Đội ngũ của chúng tôi</h2>
            <p>Baygorn Games tự hào có đội ngũ nhân viên tài năng và nhiệt huyết, luôn nỗ lực không ngừng để mang đến những trải nghiệm game tốt nhất cho người chơi.</p>
            <div class="stats">
                <div class="stat-item">
                    <h3>50+</h3>
                    <p>Nhân viên</p>
                </div>
                <div class="stat-item">
                    <h3>10+</h3>
                    <p>Năm kinh nghiệm</p>
                </div>
                <div class="stat-item">
                    <h3>100+</h3>
                    <p>Dự án game</p>
                </div>
                <div class="stat-item">
                    <h3>1M+</h3>
                    <p>Người chơi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <h2>Liên hệ với chúng tôi</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Địa chỉ</h3>
                    <p>123 Đường ABC, Quận XYZ, TP.HCM</p>
                </div>
                <div class="contact-item">
                    <h3>Email</h3>
                    <p>contact@baygorngames.com</p>
                </div>
                <div class="contact-item">
                    <h3>Điện thoại</h3>
                    <p>(+84) 123 456 789</p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Add scroll animation for header
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            navbar.classList.remove('scroll-up');
            return;
        }
        
        if (currentScroll > lastScroll && !navbar.classList.contains('scroll-down')) {
            navbar.classList.remove('scroll-up');
            navbar.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && navbar.classList.contains('scroll-down')) {
            navbar.classList.remove('scroll-down');
            navbar.classList.add('scroll-up');
        }
        
        lastScroll = currentScroll;
    });
</script>