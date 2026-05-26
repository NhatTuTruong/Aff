@php
    $siteName = (string) config('app.name');
    $hasKtsPrefix = str_starts_with($siteName, 'KTS') && strlen($siteName) > 3;
    $suffix = $hasKtsPrefix ? substr($siteName, 3) : '';
@endphp
<a href="{{ url('/') }}" class="logo font-heading site-logo"@if($hasKtsPrefix) aria-label="{{ $siteName }}"@endif>
@if ($hasKtsPrefix)
    <span class="site-logo__k" aria-hidden="true">K</span><span class="site-logo__t" aria-hidden="true">T</span><span class="site-logo__s" aria-hidden="true">S</span><span class="site-logo__rest">{{ $suffix }}</span>
@else
    {{ $siteName }}
@endif
</a>
