document.addEventListener('DOMContentLoaded', function () {
    let slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    let footer = document.querySelector('footer');
    let preloader = document.getElementById('preloader');
    let introSection = document.getElementById('intro');
    let slider = document.querySelector('.slider');
    let mainContent = document.getElementById('about-us');
    let images = document.querySelectorAll('img');
    let imagesLoaded = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? 1 : 0;
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function handleScroll() {
        //if (window.scrollY > 5) {
        //    footer.classList.add('visible');
        //} else {
        //    footer.classList.remove('visible');
        //}
    }

    function hidePreloader() {
        preloader.style.display = 'none';
        introSection.style.display = 'flex';
        slider.style.display = 'block';
        mainContent.style.display = 'block';
    }

    function checkImagesLoaded() {
        imagesLoaded++;
        if (imagesLoaded === images.length) {
            hidePreloader();
        }
    }

    showSlide(currentSlide);
    setInterval(nextSlide, 10000); // Change slide every 10 seconds

    window.addEventListener('scroll', handleScroll);

    images.forEach((img) => {
        img.addEventListener('load', checkImagesLoaded);
        img.addEventListener('error', checkImagesLoaded); // Handle image load errors
    });

    // Initial check in case images are cached
    if (images.length === 0) {
        hidePreloader();
    } else {
        images.forEach((img) => {
            if (img.complete) {
                checkImagesLoaded();
            }
        });
    }

});
