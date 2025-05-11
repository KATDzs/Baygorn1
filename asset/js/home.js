// Add smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href && href.length > 1 && document.querySelector(href)) {
      e.preventDefault();
      document.querySelector(href).scrollIntoView({
        behavior: 'smooth'
      });
    }
  });
});

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
