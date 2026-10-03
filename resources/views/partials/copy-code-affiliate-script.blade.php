<script>
function isMobileAffDevice() {
    if (window.matchMedia && window.matchMedia('(max-width: 768px)').matches) {
        return true;
    }
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent || '');
}

function openAffiliateLink(affUrl) {
    if (!affUrl) return;
    if (isMobileAffDevice()) {
        window.location.href = affUrl;
    } else {
        window.open(affUrl, '_blank', 'noopener');
    }
}

function copyCouponCodeAndRedirect(el) {
    if (!el || !el.dataset) return;
    var code = el.dataset.code || '';
    var affUrl = el.dataset.aff || '';
    if (code) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(code).catch(function () {});
        } else {
            var ta = document.createElement('textarea');
            ta.value = code;
            ta.setAttribute('readonly', '');
            ta.style.position = 'absolute';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); } catch (e) {}
            document.body.removeChild(ta);
        }
        el.classList.add('copied');
    }
    openAffiliateLink(affUrl);
}
</script>
