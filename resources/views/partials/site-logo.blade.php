@php
    $siteLogoUrl = \App\Support\AdminSettings::siteLogoUrl();
@endphp
<a href="{{ url('/') }}" class="logo font-heading{{ $siteLogoUrl ? ' logo--image' : '' }}">
    @if($siteLogoUrl)
        <img src="{{ $siteLogoUrl }}" alt="{{ config('app.name') }}" class="site-logo-img" width="160" height="40">
    @else
        {{ config('app.name') }}<span>.</span>
    @endif
</a>
