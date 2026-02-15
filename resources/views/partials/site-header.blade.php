<header class="site-header">
    <div class="header-inner">
        <a href="{{ url('/') }}" class="logo">{{ config('app.name') }}<span>.</span></a>
        <nav class="nav-links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="{{ route('legal.about') }}">About Us</a>
            <a href="{{ route('legal.contact') }}">Contact</a>
        </nav>
    </div>
</header>
