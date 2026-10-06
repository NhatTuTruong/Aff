@php
    $shopNowDirectAffUrl = trim((string) ($campaign->affiliate_url ?? ''));
    $shopNowAllItems = isset($coupons) ? $coupons : ($campaign->couponItems ?? collect());
    $shopNowFirstCoupon = $shopNowAllItems->first(fn ($c) => filled($c->code ?? null));
    $shopNowFirstDeal = $shopNowAllItems->first(fn ($c) => blank($c->code ?? null));
    $shopNowFirstCouponId = $shopNowFirstCoupon?->id;
    $shopNowFirstCode = (string) ($shopNowFirstCoupon?->code ?? '');
    $shopNowFirstDealId = $shopNowFirstDeal?->id;
@endphp
<script>
function handleShopNowClick(event) {
    if (event) {
        event.preventDefault();
    }

    var affDirect = @json($shopNowDirectAffUrl);
    var couponId = @json($shopNowFirstCouponId !== null ? (string) $shopNowFirstCouponId : null);
    var code = @json($shopNowFirstCode);
    var isDeal = false;

    if (!couponId || !code) {
        var firstBtn = document.querySelector('.btn-get-code[data-code]:not([data-all-codes="1"])');
        if (firstBtn && firstBtn.dataset.code) {
            couponId = firstBtn.dataset.couponId || null;
            code = firstBtn.dataset.code || '';
        }
    }

    if (!couponId || !code) {
        var dealId = @json($shopNowFirstDealId !== null ? (string) $shopNowFirstDealId : null);
        var firstDealBtn = document.querySelector('.btn-get-code[data-type="deal"]:not([data-all-codes="1"])');
        if (firstDealBtn && firstDealBtn.dataset.couponId) {
            couponId = firstDealBtn.dataset.couponId;
            isDeal = true;
            code = '';
        } else if (dealId) {
            couponId = dealId;
            isDeal = true;
            code = '';
        }
    }

    if (couponId && (code || isDeal)) {
        var couponUrl = new URL(window.location.href);
        couponUrl.searchParams.set('show_coupon', String(couponId));
        couponUrl.searchParams.set('aff_opened', '1');
        if (isDeal) {
            couponUrl.searchParams.set('deal', '1');
            couponUrl.searchParams.delete('code');
        } else {
            couponUrl.searchParams.set('code', String(code));
            couponUrl.searchParams.delete('deal');
        }
        couponUrl.hash = '';
        window.open(couponUrl.toString(), '_blank', 'noopener');
    }

    if (affDirect) {
        window.location.href = affDirect;
    }

    return false;
}
</script>
