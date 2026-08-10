<?php

namespace App\Services;

use Illuminate\Support\Arr;

class CouponDescriptionGenerator
{
    /** @var list<string> */
    protected array $codeTemplates = [
        'Such quality and price are hard to come by! Great chance to save money with this [brand] coupon.',
        'Experience major savings with this great deal at [brand]! Snatch up your savings before they are gone.',
        'Do not miss this [brand] promo — verified recently and ready to use at checkout.',
        'Smart shoppers save more at [brand] with this limited-time coupon code.',
        'A popular pick for [brand] fans who want great value without paying full price.',
        'Stretch your budget further at [brand] with this hand-checked offer.',
        'Why pay full price? This [brand] code helps you save on the items you already wanted.',
        'Unlock instant savings at [brand] — copy the code and apply it in seconds.',
        'One of the best [brand] codes we have seen this week. Use it before it expires.',
        'Verified for recent [brand] orders — grab this code while it still works.',
        'Great chance to save at [brand] today with a code that takes only a moment to apply.',
        'Treat yourself for less at [brand] with this exclusive coupon offer.',
        'If you are shopping at [brand], start here for an easy win at checkout.',
        'Fresh savings at [brand] — perfect for your next order.',
        'This [brand] coupon is an easy way to keep more money in your pocket.',
    ];

    /** @var list<string> */
    protected array $dealTemplates = [
        'Excellent savings at [brand]. A great place to be if you want a bargain.',
        'A great place to save at [brand] — no code needed, just click and shop.',
        'Hot deal alert from [brand]: limited-time prices you should not ignore.',
        'Score extra value at [brand] with an offer that activates automatically.',
        'Perfect for bargain hunters shopping at [brand] this season.',
        'Browse [brand] today and take advantage of this verified deal.',
        'Smart shoppers choose [brand] for quality products and strong seasonal discounts.',
        'Jump on this [brand] deal before popular items sell out.',
        'Your shortcut to savings at [brand] — no coupon code required.',
        'See why so many customers shop [brand] during promotions like this.',
        'Spend much less on your dream items when you shop at [brand]. Grab the bargain before it is gone.',
        'Excellent savings at [brand]. A great place to be if you want a bargain today.',
        'Limited-time [brand] deal — click through and save on the spot.',
        'Verified recently for [brand] shoppers looking for a quick discount.',
        'Do not wait — this [brand] deal is one of the strongest offers on this page.',
    ];

    /** @var list<string> */
    protected array $freeShippingTemplates = [
        'Get free shipping on your [brand] order — an easy way to save at checkout.',
        'Spend less on delivery when you shop [brand] with this free shipping offer.',
        '[brand] free shipping makes it easier to stock up without extra fees.',
        'Skip shipping costs at [brand] when your cart meets the offer terms.',
        'A favorite among [brand] shoppers — free delivery on qualifying orders.',
        'Enjoy complimentary shipping at [brand] and keep more of your budget for products.',
        'Free shipping at [brand] is a simple way to make your order feel like a better deal.',
        'Shop [brand] with confidence and save on delivery with this verified offer.',
    ];

    public function generate(string $offer, bool $hasCode, string $brandName, ?int $seed = null): string
    {
        $brand = trim($brandName) !== '' ? trim($brandName) : 'this store';
        $offerTrim = trim($offer);
        $offerLower = strtolower($offerTrim);
        $isFreeShipping = $offerLower === 'free shipping' || str_contains($offerLower, 'free shipping');

        if ($isFreeShipping) {
            $templates = $this->freeShippingTemplates;
        } elseif ($hasCode) {
            $templates = $this->codeTemplates;
        } else {
            $templates = $this->dealTemplates;
        }

        if ($seed !== null) {
            $index = abs($seed) % count($templates);
            $template = $templates[$index];
        } else {
            $template = Arr::random($templates);
        }

        $text = str_replace('[brand]', $brand, $template);

        if ($offerTrim !== '' && ! $isFreeShipping) {
            $text = str_replace('[offer]', $offerTrim, $text);
        }

        return $text;
    }
}
