// Show/hide password toggle for login & register forms
// Add FontAwesome eye/eye-slash icon if not present

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-group').forEach(function(group) {
        var pwdInputs = group.querySelectorAll('input[type="password"]');
        pwdInputs.forEach(function(pwdInput) {
            // Nếu input chưa có wrapper, tạo wrapper
            let wrapper;
            if (pwdInput.parentElement.classList.contains('input-wrapper')) {
                wrapper = pwdInput.parentElement;
            } else {
                wrapper = document.createElement('div');
                wrapper.className = 'input-wrapper';
                wrapper.style.position = 'relative';
                pwdInput.parentNode.insertBefore(wrapper, pwdInput);
                wrapper.appendChild(pwdInput);
            }
            // Xóa toggle-password cũ nếu có
            let next = pwdInput.nextElementSibling;
            if (next && next.classList && next.classList.contains('toggle-password')) {
                next.remove();
            }
            // Thêm toggle mới
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'toggle-password';
            btn.tabIndex = -1;
            btn.innerHTML = '<i class="fa fa-eye"></i>';
            btn.style.position = 'absolute';
            btn.style.right = '12px';
            btn.style.top = '0';
            btn.style.bottom = '0';
            btn.style.margin = 'auto 0';
            btn.style.transform = '';
            btn.style.width = '40px';
            btn.style.height = '100%';
            btn.style.display = 'flex';
            btn.style.alignItems = 'center';
            btn.style.justifyContent = 'center';
            btn.style.background = 'none';
            btn.style.border = 'none';
            btn.style.cursor = 'pointer';
            btn.style.color = '#ff4655';
            btn.style.fontSize = '1.25em';
            btn.style.padding = '0';
            btn.style.outline = 'none';
            btn.setAttribute('aria-label', 'Hiện/ẩn mật khẩu');
            pwdInput.style.paddingRight = '2.5em';
            pwdInput.insertAdjacentElement('afterend', btn);
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (pwdInput.type === 'password') {
                    pwdInput.type = 'text';
                    btn.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    pwdInput.type = 'password';
                    btn.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });
    });
});
