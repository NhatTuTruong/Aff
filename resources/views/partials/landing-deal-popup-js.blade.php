function applyDealModalUi(isDeal) {
    isDeal = isDeal === true;
    const codeBox = document.getElementById('modalCode');
    const copyBtn = document.getElementById('copyCouponBtn');
    if (isDeal) {
        if (codeBox) codeBox.innerText = 'No code needed';
        if (copyBtn) copyBtn.style.display = 'none';
    } else {
        if (codeBox) codeBox.innerText = '••••••••';
        if (copyBtn) copyBtn.style.display = '';
    }
}

function openDealCouponFlow(couponId, url, actualBtn) {
    currentCouponId = couponId;
    currentCouponRow = actualBtn ? actualBtn.closest('.coupon-row') : null;
    if (!affiliateAlreadyOpened) {
        const couponUrl = new URL(window.location.href);
        couponUrl.searchParams.set('show_coupon', String(couponId));
        couponUrl.searchParams.set('deal', '1');
        couponUrl.searchParams.set('aff_opened', '1');
        couponUrl.searchParams.delete('code');
        couponUrl.hash = '';
        window.open(couponUrl.toString(), '_blank', 'noopener');
        if (url) window.location.href = url;
        return false;
    }
    openModalForCoupon(couponId, '', url, true);
    return false;
}

function restoreDealModalFromUrlParams(affUrl) {
    const urlParams = new URLSearchParams(window.location.search);
    const showCouponId = urlParams.get('show_coupon');
    const isDealFromUrl = urlParams.get('deal') === '1';
    if (!showCouponId || !isDealFromUrl) return false;
    currentCouponId = showCouponId;
    currentCouponRow = document.querySelector('.coupon-row[data-coupon-id="' + showCouponId + '"]');
    openModalForCoupon(showCouponId, '', affUrl, true);
    return true;
}
