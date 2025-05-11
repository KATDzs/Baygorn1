document.addEventListener('DOMContentLoaded', function() {
    var parent = document.querySelector('.admin-dropdown-parent');
    if (parent) {
        var dropdown = parent.querySelector('.admin-navbar-dropdown');
        parent.addEventListener('mouseenter', function() {
            dropdown.style.display = 'block';
        });
        parent.addEventListener('mouseleave', function() {
            dropdown.style.display = 'none';
        });
    }

    // Đóng menu mobile khi bấm vào link
    function closeMobileMenu() {
        var mobileMenu = document.querySelector('.menu.menu-mobile');
        if (mobileMenu) {
            mobileMenu.style.display = 'none';
            // Nếu bạn dùng toggle class để mở/đóng menu, hãy thay bằng:
            // mobileMenu.classList.remove('menu-mobile-active');
        }
    }

    // Gắn sự kiện cho tất cả link trong menu mobile
    var mobileLinks = document.querySelectorAll('.menu.menu-mobile .menu-link');
    mobileLinks.forEach(function(link) {
        link.addEventListener('click', closeMobileMenu);
    });
});

function toggleDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown-menu');
    const avatar = document.querySelector('.avatar');
    if (dropdown && avatar && !avatar.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});
