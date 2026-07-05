{{-- Ad slot — local অথবা Google fallback (strip = container width, site এর মতো) --}}
@props([
    'slug' => null,
    'ad' => null,
    'variant' => 'banner',
    'wrapperClass' => '',
    'sidebarClass' => '',
    'pictureClass' => 'ad-slot-media max-w-full max-h-full w-auto h-auto object-contain',
    'sidebarPictureClass' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100',
])

@php
$ad = $ad ?? ($slug ? ad_slot($slug) : null);
$isStrip = in_array($variant, ['header', 'banner'], true);
$isBelowMenu = in_array($slug ?? $ad?->slug, [
    'below_menu',
    'category_below_menu',
    'details_below_menu',
    'video_details_below_menu',
    'gallery_details_below_menu',
    'gallery_category_below_menu',
    'video_category_below_menu',
], true);
$stripOuterClass = match (true) {
    $variant === 'header' => 'hidden w-full md:flex md:py-0',
    $isBelowMenu => 'pt-0 pb-0 w-full',
    default => 'py-2 md:py-3 w-full',
};
$stripBoxStyle = $ad ? $ad->slotBoxStyle('strip') : '';
$boxStyle = $ad ? $ad->slotBoxStyle('box') : '';
$showLocal = $ad && $ad->displayUsesLocalAd();
$showGoogle = $ad && ad_show_google($ad);
@endphp

@if($showLocal)
    @if($isStrip)
        <div class="{{ $stripOuterClass }} {{ $wrapperClass }} w-full max-w-full min-w-0" data-ad-slot-root @if($isBelowMenu) data-ad-below-menu @endif @if($variant === 'header') id="header-ad-slot" @endif>
            <div class="container">
                <a href="{{ advertisement_click_url($ad) }}" class="ad-slot-frame ad-slot-local w-full min-w-0 flex items-center justify-center overflow-hidden bg-white" style="{{ $stripBoxStyle }}" target="_blank" rel="noopener">
                    <x-ad-picture :ad="$ad" class="{{ $pictureClass }}" :fetchpriority="($isBelowMenu || $variant === 'header') ? 'high' : null" />
                </a>
            </div>
        </div>
    @elseif($variant === 'sidebar')
        <div class="{{ $wrapperClass ?: 'shrink-0 w-full' }}" data-ad-slot-root>
            <a href="{{ advertisement_click_url($ad) }}" target="_blank" rel="noopener" class="ad-slot-frame ad-slot-local block group cursor-pointer relative overflow-hidden bg-white w-full {{ $sidebarClass }}" style="{{ $boxStyle }}">
                <x-ad-picture :ad="$ad" class="{{ $sidebarPictureClass }}" fetchpriority="high" />
            </a>
        </div>
    @elseif($variant === 'inline')
        @include('frontend.partials.detail-inline-ad', ['ad' => $ad])
    @endif
@elseif($showGoogle)
    @if($isStrip)
        <div class="{{ $stripOuterClass }} {{ $wrapperClass }} w-full max-w-full min-w-0" data-ad-slot-root data-ad-google @if($isBelowMenu) data-ad-below-menu @endif @if($variant === 'header') id="header-ad-slot" @endif>
            <div class="container">
                <x-google-ad-unit :ad="$ad" layout="strip" />
            </div>
        </div>
    @elseif($variant === 'sidebar')
        <div class="{{ $wrapperClass ?: 'shrink-0 w-full' }}" data-ad-slot-root data-ad-google>
            <x-google-ad-unit :ad="$ad" layout="box" :class="$sidebarClass" />
        </div>
    @elseif($variant === 'inline')
        @include('frontend.partials.detail-inline-ad', ['ad' => $ad])
    @endif
@elseif($slug)
<!-- ad-slot:{{ $slug }} status=empty -->
@endif
