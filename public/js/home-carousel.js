(function () {
    'use strict';

    var pageHidden = document.hidden;

    document.addEventListener('visibilitychange', function () {
        pageHidden = document.hidden;
    }, { passive: true });

    function initHmCarousel(carousel) {
        var track = carousel.querySelector('.hm-hero-carousel-track, .hm-cat-carousel-track');
        var prev = carousel.querySelector('[data-hm-carousel-prev]');
        var next = carousel.querySelector('[data-hm-carousel-next]');
        var dotsWrap = carousel.querySelector('[data-hm-carousel-dots]');

        if (!track) {
            return;
        }

        var slides = track.querySelectorAll('.hm-hero-slide, .hm-cat-slide');
        var dots = dotsWrap ? dotsWrap.querySelectorAll('.hm-hero-dot') : [];
        var total = slides.length;

        if (total <= 1) {
            return;
        }

        var index = 0;
        var timer = null;
        var delay = 3000;
        var isHovered = false;

        function goTo(i) {
            index = (i % total + total) % total;
            track.style.transform = 'translate3d(' + (-index * 100) + '%,0,0)';

            for (var j = 0; j < dots.length; j++) {
                dots[j].classList.toggle('is-active', j === index);
            }
        }

        function nextSlide() {
            goTo(index + 1);
        }

        function prevSlide() {
            goTo(index - 1);
        }

        function startAuto() {
            if (pageHidden || isHovered) {
                return;
            }

            stopAuto();
            timer = setInterval(function () {
                if (pageHidden || isHovered) {
                    return;
                }

                nextSlide();
            }, delay);
        }

        function stopAuto() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function resetAuto() {
            stopAuto();
            startAuto();
        }

        if (prev) {
            prev.addEventListener('click', function () {
                prevSlide();
                resetAuto();
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                nextSlide();
                resetAuto();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var target = parseInt(dot.getAttribute('data-slide') || '0', 10);
                if (!isNaN(target)) {
                    goTo(target);
                    resetAuto();
                }
            });
        });

        carousel.addEventListener('mouseenter', function () {
            isHovered = true;
            stopAuto();
        });

        carousel.addEventListener('mouseleave', function () {
            isHovered = false;
            startAuto();
        });

        goTo(0);
        startAuto();
    }

    function bootCarousels() {
        document.querySelectorAll('[data-hm-carousel]').forEach(initHmCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootCarousels, { once: true });
    } else {
        bootCarousels();
    }
})();
