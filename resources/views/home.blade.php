@extends('layouts.app')

@section('title', config('app.name') . ' - Vetted Coupons & Store Guides')
@section('description', 'Discover checked coupon codes, current promotions, and straightforward store guides. Updated regularly.')

@push('styles')
    @include('partials.home-index-styles')
@endpush

@section('content')
    @include('partials.home-index-content')
@endsection
