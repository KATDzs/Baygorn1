document.addEventListener('DOMContentLoaded', function() {
    const games = window.SHOPGAMES_DATA || [];
    let current = 0;
    const cardContainer = document.getElementById('sliderCardContainer');
    const blurBg = document.getElementById('sliderBlurBg');
    const heroBg = document.querySelector('.hero-bg-img');
    const heroTitle = document.getElementById('heroTitle');
    const heroDesc = document.getElementById('heroDesc');
    const heroPlatform = document.getElementById('heroPlatform');
    const heroPrice = document.getElementById('heroPrice');
    const buyNowBtn = document.getElementById('buyNowBtn');

    function renderCard(idx, direction = 1) {
        if (!games.length) return;
        const game = games[idx];
        const imagePath = `/Baygorn1/asset/img/games/${game.image_url}`;
        const defaultImage = '/Baygorn1/asset/img/default-game.jpg';
        // ...rest of JS logic from <script> in shoppage.php...
    }
    // ...rest of JS logic...
});
