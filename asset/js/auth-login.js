// Hiển thị thông báo lỗi khi đăng nhập thất bại và tự động ẩn sau vài giây

document.addEventListener('DOMContentLoaded', function() {
    var errorBox = document.querySelector('.error-message');
    if (errorBox) {
        errorBox.style.display = 'block';
        errorBox.style.opacity = '1';
        // Tự động ẩn sau 4 giây
        setTimeout(function() {
            errorBox.style.transition = 'opacity 0.5s';
            errorBox.style.opacity = '0';
            setTimeout(function() {
                errorBox.style.display = 'none';
            }, 500);
        }, 4000);
    }
});
