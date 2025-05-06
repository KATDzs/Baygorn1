const slider = document.getElementById("slider");
const dots = document.querySelectorAll(".dot");
function getCardWidth() {
    const card = document.querySelector(".news-card");
    return card ? card.offsetWidth + 20 : 320;
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
    dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === activeIndex);
    });
}

slider.addEventListener("scroll", () => {
    const cardWidth = getCardWidth();
    const index = Math.round(slider.scrollLeft / cardWidth);
    updateDots(index);
});
