@php
    $headExtras = \App\Models\SiteContent::headExtrasHtml();
@endphp
@if($headExtras !== '')
{!! $headExtras !!}
@endif
