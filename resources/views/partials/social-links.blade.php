@php
    use App\Models\SiteContent;
    use App\Support\SocialNetwork;

    $links = SiteContent::activeSocialLinks();
    $variant = $variant ?? 'icons';
@endphp
@if($links !== [])
<div class="site-social site-social--{{ $variant }}" aria-label="Social media">
    @foreach($links as $link)
        @php
            $network = (string) ($link['network'] ?? '');
            $url = trim((string) ($link['url'] ?? ''));
            $label = SocialNetwork::label($network);
            $iconPath = SocialNetwork::iconSvg($network);
        @endphp
        @if($url !== '')
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="site-social-link site-social-link--{{ $network }}" aria-label="{{ $label }}">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">{!! $iconPath !!}</svg>
            @if($variant === 'pills')<span>{{ $label }}</span>@endif
            @if($variant === 'topbar')<span class="sr-only">{{ $label }}</span>@endif
        </a>
        @endif
    @endforeach
</div>
@endif
