document.getElementById('password_confirm').addEventListener('input', function() {
    if (this.value !== document.getElementById('password').value) {
        this.setCustomValidity('Mật khẩu xác nhận không khớp');
    } else {
        this.setCustomValidity('');
    }
});
