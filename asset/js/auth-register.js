// Kiểm tra mật khẩu khớp nhau
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm_password');
const form = document.querySelector('form');

form.addEventListener('submit', function(e) {
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
    }
});
