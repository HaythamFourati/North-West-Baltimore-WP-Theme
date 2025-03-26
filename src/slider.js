export function initBusinessSlider(sliderElement) {
    if (!sliderElement) return;

    const track = sliderElement.querySelector('.slider-track');
    const slides = Array.from(track.querySelectorAll('.slide'));
    if (slides.length <= 1) return;

    const actualSlideCount = slides.length - 2; // Subtract cloned slides
    let currentSlide = 1; // Start at first real slide (after clone)
    let autoplayInterval = null;
    let isAutoPlaying = true;
    let isTransitioning = false;

    // Create dots - only create for actual slides (not clones)
    const dotsContainer = sliderElement.parentElement.querySelector('.absolute.bottom-4');
    dotsContainer.innerHTML = '';
    
    for (let i = 0; i < actualSlideCount; i++) {
        const dot = document.createElement('button');
        dot.className = i === 0 ? 'w-3 h-3 rounded-full bg-white shadow-md transition-all' : 'w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all';
        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
        dot.addEventListener('click', () => goToSlide(i + 1));
        dotsContainer.appendChild(dot);
    }
    const dots = Array.from(dotsContainer.children);

    // Setup navigation
    const prevButton = sliderElement.parentElement.querySelector('.slider-nav.prev');
    const nextButton = sliderElement.parentElement.querySelector('.slider-nav.next');

    prevButton.addEventListener('click', () => {
        if (!isTransitioning) {
            stopAutoplay();
            prevSlide();
        }
    });

    nextButton.addEventListener('click', () => {
        if (!isTransitioning) {
            stopAutoplay();
            nextSlide();
        }
    });

    // Add keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (document.activeElement === prevButton || document.activeElement === nextButton) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                if (!isTransitioning) {
                    stopAutoplay();
                    prevSlide();
                }
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                if (!isTransitioning) {
                    stopAutoplay();
                    nextSlide();
                }
            }
        }
    });

    function updateSlides(skipTransition = false) {
        isTransitioning = true;
        
        if (skipTransition) {
            track.style.transition = 'none';
        } else {
            track.style.transition = 'transform 0.5s ease-in-out';
        }

        // Calculate the translation - simple 100% per slide
        const translateX = -currentSlide * 100;
        track.style.transform = `translateX(${translateX}%)`;
        
        // Update active class for current slide and opacity for adjacent slides
        slides.forEach((slide, index) => {
            // Remove active class from all slides
            slide.classList.remove('active');
            
            // Add active class to current slide
            if (index === currentSlide) {
                slide.classList.add('active');
            }
        });

        // Update dots - adjust for cloned slides
        const activeDotIndex = currentSlide - 1;
        dots.forEach((dot, index) => {
            dot.className = index === activeDotIndex 
                ? 'w-3 h-3 rounded-full bg-white shadow-md transition-all' 
                : 'w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all';
        });

        if (skipTransition) {
            isTransitioning = false;
        } else {
            // Reset transition lock after animation
            setTimeout(() => {
                if (currentSlide === 0) {
                    // If we're at the first clone, jump to the last real slide
                    currentSlide = actualSlideCount;
                    updateSlides(true);
                } else if (currentSlide === slides.length - 1) {
                    // If we're at the last clone, jump to the first real slide
                    currentSlide = 1;
                    updateSlides(true);
                }
                isTransitioning = false;
            }, 500);
        }
    }

    function nextSlide() {
        if (isTransitioning) return;
        currentSlide++;
        updateSlides();
    }

    function prevSlide() {
        if (isTransitioning) return;
        currentSlide--;
        updateSlides();
    }

    function goToSlide(index) {
        if (isTransitioning || currentSlide === index) return;
        stopAutoplay();
        currentSlide = index;
        updateSlides();
    }

    function startAutoplay() {
        isAutoPlaying = true;
        autoplayInterval = setInterval(() => {
            if (!isTransitioning) {
                nextSlide();
            }
        }, 5000);
    }

    function stopAutoplay() {
        isAutoPlaying = false;
        clearInterval(autoplayInterval);
    }

    // Initialize
    updateSlides(true); // Initialize with the first slide active
    startAutoplay();

    // Add touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderElement.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    sliderElement.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left - next slide
            stopAutoplay();
            nextSlide();
        } else if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right - previous slide
            stopAutoplay();
            prevSlide();
        }
    }

    // Handle visibility change
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoplay();
        } else if (!isTransitioning) {
            startAutoplay();
        }
    });

    // Handle transition end to prevent animation glitches
    track.addEventListener('transitionend', () => {
        isTransitioning = false;
    });
}

// Initialize sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const sliders = document.querySelectorAll('.business-slider');
    sliders.forEach(slider => initBusinessSlider(slider));
});
