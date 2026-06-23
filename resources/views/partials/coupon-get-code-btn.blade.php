@php
    $hasCode = $hasCode ?? !empty($code);
    $isAllCodes = !empty($allCodes);
    $codeHint = $isAllCodes ? 'CODE' : (($hasCode && !empty($code)) ? strtoupper(substr($code, -2)) : '');
    $label = ($hasCode || $isAllCodes) ? 'Get Code' : 'Get Deal';
    $wideClass = !empty($wide) ? ' btn-peel-sticker--wide' : '';
    $ariaLabel = $isAllCodes ? 'View all coupon codes' : ($hasCode ? 'Get coupon code and go to store' : 'Open deal and go to store');
@endphp
<button class="btn-get-code btn-peel-sticker{{ $wideClass }}" type="button"
    data-type="{{ ($hasCode || $isAllCodes) ? 'code' : 'deal' }}"
    @if($isAllCodes) data-all-codes="1" @endif
    data-code="{{ $code ?? '' }}"
    data-coupon-id="{{ $couponId ?? '' }}"
    data-url="{{ $url ?? '' }}"
    aria-label="{{ $ariaLabel }}"
    onclick="return handleCouponClick(this)">
    <span class="peel-inner{{ ($hasCode || $isAllCodes) ? '' : ' peel-inner--deal' }}">
        @if(($hasCode || $isAllCodes) && $codeHint !== '')
            <span class="peel-reveal" aria-hidden="true">{{ $codeHint }}</span>
        @endif
        <span class="peel-sheet">
            <span class="peel-text">{{ $label }}</span>
        </span>
        @if($hasCode || $isAllCodes)
         
        @endif
    </span>
</button>
