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
