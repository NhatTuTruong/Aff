<button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 19V5"/>
        <path d="m5 12 7-7 7 7"/>
    </svg>
</button>
<script>
(function () {
    var btn = document.getElementById('back-to-top');
    if (!btn) return;

    var threshold = 320;

    function toggle() {
        var show = window.scrollY > threshold;
        btn.classList.toggle('is-visible', show);
        btn.setAttribute('aria-hidden', show ? 'false' : 'true');
        btn.tabIndex = show ? 0 : -1;
    }

    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', toggle, { passive: true });
    toggle();
})();
</script>
