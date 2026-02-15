<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="logo">{{ config('app.name') }}<span>.</span></a>
                <p>Coupons, promotions and trusted store reviews. Updated regularly.</p>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('blog.index') }}">Review Blog</a></li>
                    <li><a href="{{ url('/') }}#stores">Stores</a></li>
                    <li><a href="{{ url('/') }}#coupons">Coupons</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="{{ route('legal.about') }}">About Us</a></li>
                    <li><a href="{{ route('legal.contact') }}">Contact</a></li>
                    <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Links</h4>
                <ul>
                    <li><a href="{{ route('legal.contact') }}">Feedback</a></li>
                    <li><a href="{{ route('legal.privacy') }}">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
