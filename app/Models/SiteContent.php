<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Lấy giá trị theo key (cache 1 giờ). Trả về decoded JSON nếu value là JSON hợp lệ.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = 'site_content.' . $key;
        $value = Cache::remember($cacheKey, 3600, function () use ($key) {
            $row = static::query()->where('key', $key)->first();
            return $row?->value;
        });

        if ($value === null) {
            return $default;
        }

        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    /**
     * Ghi giá trị (array/object sẽ được lưu dạng JSON).
     */
    public static function set(string $key, mixed $value): void
    {
        $stringValue = is_string($value) ? $value : json_encode($value);
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stringValue]
        );
        Cache::forget('site_content.' . $key);
    }

    /** Mặc định nav header: [{label, url}, ...] */
    public static function defaultHeaderNav(): array
    {
        return [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Blog', 'url' => '/blog'],
            ['label' => 'About Us', 'url' => '/about'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];
    }

    /** Mặc định footer columns: [{title, links: [{label, url}]}, ...] */
    public static function defaultFooterColumns(): array
    {
        return [
            [
                'title' => 'Explore',
                'links' => [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'Review Blog', 'url' => '/blog'],
                    ['label' => 'Stores', 'url' => '/#stores'],
                    ['label' => 'Coupons', 'url' => '/#coupons'],
                ],
            ],
            [
                'title' => 'Legal',
                'links' => [
                    ['label' => 'About Us', 'url' => '/about'],
                    ['label' => 'Contact', 'url' => '/contact'],
                    ['label' => 'Privacy Policy', 'url' => '/privacy'],
                    ['label' => 'Terms of Use', 'url' => '/terms'],
                ],
            ],
            [
                'title' => 'Links',
                'links' => [
                    ['label' => 'Feedback', 'url' => '/contact'],
                    ['label' => 'Deals', 'url' => '/deals'],
                    ['label' => 'Affiliate Disclosure', 'url' => '/affiliate-disclosure'],
                ],
            ],
        ];
    }

    /** Mặc định nội dung trang lỗi: title, message */
    public static function defaultErrorContent(string $code): array
    {
        return match ($code) {
            '404' => ['title' => 'Trang không tồn tại', 'message' => 'Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.'],
            '403' => ['title' => 'Không có quyền truy cập', 'message' => 'Bạn không có quyền truy cập trang này.'],
            '500' => ['title' => 'Lỗi máy chủ', 'message' => 'Đã xảy ra lỗi. Chúng tôi đang khắc phục.'],
            '503' => ['title' => 'Bảo trì', 'message' => 'Hệ thống đang bảo trì. Vui lòng quay lại sau.'],
            default => ['title' => 'Lỗi', 'message' => 'Đã xảy ra lỗi.'],
        };
    }

    /** Nội dung mặc định trang About Us (tiếng Anh) */
    public static function defaultPageAboutUs(): string
    {
        return <<<'HTML'
<h1 class="font-heading">About ReviewHays</h1>

<p><strong>ReviewHays</strong> is an independent review and deal discovery platform designed to help consumers make smarter purchasing decisions online.</p>

<p>In a digital landscape crowded with promotions, discount codes, and sponsored content, our goal is simple: to provide clear, honest, and well-researched reviews alongside verified offers that genuinely add value.</p>

<h2 class="font-heading">Our Mission</h2>
<p>At ReviewHays, our mission is to:</p>
<ul>
<li>Deliver transparent and unbiased product reviews</li>
<li>Compare products, services, and brands based on real value, not hype</li>
<li>Curate up-to-date deals, coupons, and promotions from trusted merchants</li>
<li>Help readers save time and money while shopping online</li>
</ul>

<h2 class="font-heading">How We Create Our Content</h2>
<p>Our editorial process focuses on accuracy, relevance, and usefulness.</p>
<p>We research and evaluate products and services using official brand information, market comparisons, user feedback, and real-world use cases.</p>
<p>Each review clearly outlines:</p>
<ul>
<li>Key features and benefits</li>
<li>Pros and cons</li>
<li>Who the product or service is best suited for</li>
<li>Overall value for money</li>
</ul>

<h2 class="font-heading">Affiliate Disclosure &amp; Transparency</h2>
<p>ReviewHays participates in various affiliate marketing programs. This means some links on our website may be affiliate links.</p>
<p>If you make a purchase through these links, we may earn a small commission at no additional cost to you. Affiliate relationships do not influence our editorial decisions.</p>
<p>We prioritize accuracy, transparency, and user trust above all else.</p>

<h2 class="font-heading">Why Trust ReviewHays?</h2>
<ul>
<li>Independent and research-driven content</li>
<li>Regularly updated reviews and deals</li>
<li>Clear affiliate disclosure and transparency</li>
<li>No misleading claims or exaggerated promotions</li>
<li>Focused on long-term user value</li>
</ul>

<h2 class="font-heading">Get in Touch</h2>
<p>We welcome feedback, questions, and collaboration inquiries. Please visit our Contact page to reach out to us.</p>
<p>Thank you for choosing <strong>ReviewHays</strong> as your trusted source for reviews, comparisons, and savings.</p>
HTML;
    }

    /** Nội dung mặc định trang Contact (tiếng Anh). Dùng [SITE_EMAIL] làm placeholder cho email. */
    public static function defaultPageContact(): string
    {
        return <<<'HTML'
<h1 class="font-heading">Contact Us</h1>
<p>If you have any questions or concerns, please feel free to reach out to us.</p>
<p>Email: [SITE_EMAIL]</p>
<p>We aim to respond to all inquiries within 24-48 hours.</p>
HTML;
    }

    /** Nội dung mặc định trang Privacy Policy (tiếng Anh) */
    public static function defaultPagePrivacy(): string
    {
        return <<<'HTML'
<h1 class="font-heading">Privacy Policy</h1>
<p class="updated">Last updated: [PRIVACY_DATE]</p>

<p>At <strong>ReviewHays</strong>, we value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you visit our website.</p>
<p>By using this website, you agree to the practices described in this Privacy Policy.</p>

<h2>Information We Collect</h2>
<p>We may collect the following types of information:</p>
<ul>
<li>Non-personal information such as browser type, device information, operating system, and referral sources</li>
<li>Usage data including pages visited, time spent on the site, and interactions with content</li>
<li>Personal information only when voluntarily provided (for example, via a contact form)</li>
</ul>

<h2>How We Use Your Information</h2>
<p>The information we collect may be used to:</p>
<ul>
<li>Improve website performance, content quality, and user experience</li>
<li>Analyze traffic patterns and audience behavior</li>
<li>Respond to inquiries or communications you initiate</li>
<li>Maintain website security and prevent fraudulent activity</li>
</ul>

<h2>Cookies and Tracking Technologies</h2>
<p>ReviewHays uses cookies and similar tracking technologies to enhance user experience and analyze website traffic.</p>
<p>Cookies may be used by third-party services such as analytics providers and affiliate networks to track referrals and conversions.</p>
<p>You can choose to disable cookies through your browser settings. Please note that doing so may affect certain website functionalities.</p>

<h2>Affiliate Links and Third-Party Services</h2>
<p>ReviewHays participates in affiliate marketing programs. Some links on this website are affiliate links, which may track clicks or purchases for commission purposes.</p>
<p>We do not control how third-party websites collect or use your data. We encourage you to review the privacy policies of any external sites you visit through our links.</p>

<h2>Third-Party Advertising</h2>
<p>Third-party vendors, including advertising and analytics partners, may use cookies or similar technologies to serve ads or measure performance based on your visits to this and other websites.</p>
<p>Users may opt out of personalized advertising by adjusting their browser settings or using industry opt-out tools.</p>

<h2>Data Protection and Security</h2>
<p>We implement reasonable security measures to protect your information. However, no method of transmission over the internet or electronic storage is 100% secure.</p>
<p>We cannot guarantee absolute security, but we strive to protect your data using commercially acceptable means.</p>

<h2>Your Privacy Rights</h2>
<p>Depending on your location, you may have certain rights regarding your personal data, including:</p>
<ul>
<li>The right to access the personal data we hold about you</li>
<li>The right to request correction or deletion of your data</li>
<li>The right to restrict or object to certain data processing activities</li>
</ul>
<p>To exercise these rights, please contact us through the information provided on our Contact page.</p>

<h2>Children's Information</h2>
<p>ReviewHays does not knowingly collect personal information from children under the age of 13. If you believe that a child has provided personal information on our website, please contact us so we can promptly remove such data.</p>

<h2>Changes to This Privacy Policy</h2>
<p>We may update this Privacy Policy from time to time to reflect changes in legal requirements or website practices. Any updates will be posted on this page with a revised effective date.</p>

<h2>Contact Us</h2>
<p>If you have any questions about this Privacy Policy or how we handle your data, please visit our Contact page to get in touch.</p>
HTML;
    }

    /** Nội dung mặc định trang Affiliate Disclosure (tiếng Anh) */
    public static function defaultPageAffiliateDisclosure(): string
    {
        return <<<'HTML'
<h1 class="font-heading">Affiliate Disclosure</h1>

<p><strong>[SITE_NAME]</strong> participates in affiliate marketing programs. This means that some of the links on this website are affiliate links, which may earn us a commission when you click or make a purchase, at no additional cost to you.</p>

<h2>Why we use affiliate links</h2>
<p>Affiliate partnerships allow us to keep the website running, maintain our tools, and continue researching and curating the best deals, coupons, and reviews for you without charging a subscription fee.</p>

<h2>How affiliate links affect you</h2>
<ul>
<li>The price you pay is the same whether you use our links or go directly to the merchant.</li>
<li>We only promote brands, stores, and offers that we believe provide real value.</li>
<li>Affiliate relationships do not influence our editorial opinions or review content.</li>
</ul>

<h2>Transparency &amp; trust</h2>
<p>We aim to be fully transparent about how we monetize this website. If you ever have questions about a specific recommendation, partnership, or how we earn commissions, please contact us via the Contact page.</p>

<p>Thank you for supporting <strong>[SITE_NAME]</strong>. Your support helps us continue finding and sharing great deals.</p>
HTML;
    }
}
