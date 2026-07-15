<button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 19V5"/>
        <path d="m5 12 7-7 7 7"/>
    </svg>
</button>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('back-to-top');
    if (!btn) return;

    var threshold = 320;
    var isVisible = false;
    var scrollTicking = false;

    function updateVisibility() {
        var show = window.scrollY > threshold;
        if (show === isVisible) {
            scrollTicking = false;
            return;
        }

        isVisible = show;
        btn.classList.toggle('is-visible', show);
        btn.setAttribute('aria-hidden', show ? 'false' : 'true');
        btn.tabIndex = show ? 0 : -1;
        scrollTicking = false;
    }

    btn.addEventListener('click', function () {
        var start = window.scrollY;
        var duration = 600;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var elapsed = timestamp - startTime;
            var progress = Math.min(elapsed / duration, 1);
            var ease = 1 - Math.pow(1 - progress, 3);

            window.scrollTo(0, start * (1 - ease));

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }

        requestAnimationFrame(step);
    });

    window.addEventListener('scroll', function () {
        if (!scrollTicking) {
            scrollTicking = true;
            requestAnimationFrame(updateVisibility);
        }
    }, { passive: true });

    requestAnimationFrame(updateVisibility);
});
</script>
