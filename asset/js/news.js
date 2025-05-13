const slider = document.getElementById("slider");
const dots = document.querySelectorAll(".dot");

function getCardWidth() {
    const card = document.querySelector(".news-card");
    return card ? card.offsetWidth : 320;
}

function scrollSlider(direction) {
    const cardWidth = getCardWidth();
    let currentIndex = Math.round(slider.scrollLeft / cardWidth);
    let nextIndex = currentIndex + direction;
    nextIndex = Math.max(0, Math.min(nextIndex, dots.length - 1));
    goToSlide(nextIndex);
}

function goToSlide(index) {
    const cardWidth = getCardWidth();
    slider.scrollTo({
        left: index * cardWidth,
        behavior: "smooth"
    });
    updateDots(index);
}

function updateDots(activeIndex) {
    dots.forEach((dot, idx) => {
        dot.classList.toggle("active", idx === activeIndex);
    });
}

// Chỉ áp dụng hiệu ứng này trên mobile
function isMobile() {
    return window.innerWidth <= 600;
}

slider.addEventListener("scroll", () => {
    if (!isMobile()) return; // chỉ xử lý trên mobile
    const cardWidth = getCardWidth();
    const index = Math.round(slider.scrollLeft / cardWidth);
    updateDots(index);
});

// Đảm bảo khi resize về mobile thì scroll về đúng vị trí card
window.addEventListener("resize", () => {
    if (isMobile()) {
        const cardWidth = getCardWidth();
        const activeDot = document.querySelector(".dot.active");
        const index = Array.from(dots).indexOf(activeDot);
        slider.scrollTo({
            left: index * cardWidth,
            behavior: "auto"
        });
    }
});
