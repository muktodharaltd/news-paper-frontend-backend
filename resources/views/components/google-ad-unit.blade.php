@props([
    'ad',
    'layout' => 'strip',
    'slotContext' => null,
    'class' => '',
    'fullWidthResponsive' => null,
])

@php
$client = google_adsense_client();
$slotContext = $slotContext ?? ($layout === 'strip' ? 'strip' : 'box');
$slotId = google_adsense_slot_for($ad, $slotContext);
$boxStyle = $ad ? $ad->slotBoxStyle($layout === 'strip' ? 'strip' : 'box') : '';
$googleOpts = ad_google_unit_options($ad, $layout === 'strip' ? 'strip' : 'box');
$responsive = $fullWidthResponsive === null
    ? $googleOpts['responsive']
    : filter_var($fullWidthResponsive, FILTER_VALIDATE_BOOLEAN);
$adFormat = $googleOpts['format'];
$adWidth = $googleOpts['width'];
$adHeight = $googleOpts['height'];
$insMinHeight = $googleOpts['ins_min_height'];
$frameClass = $layout === 'strip'
    ? 'ad-slot-frame ad-slot-google w-full min-w-0 block relative overflow-hidden bg-white'
    : 'ad-slot-frame ad-slot-google block overflow-hidden bg-white w-full min-w-0 relative';
$insStyle = "display:block;width:100%;min-height:{$insMinHeight}px;";
@endphp

@if($client && $slotId && $ad)
<div class="{{ $frameClass }} {{ $class }}" style="{{ $boxStyle }}" data-ad-layout="{{ $layout }}">
    @if($layout === 'strip')
    <span class="ad-slot-size-hold block w-full pointer-events-none" aria-hidden="true"></span>
    @endif
    <ins class="adsbygoogle"
         style="{{ $insStyle }}"
         data-ad-client="{{ $client }}"
         data-ad-slot="{{ $slotId }}"
         data-ad-format="{{ $adFormat }}"
         data-full-width-responsive="{{ $responsive ? 'true' : 'false' }}"
         @if($adWidth && $adHeight && ! $responsive)
         data-ad-width="{{ $adWidth }}"
         data-ad-height="{{ $adHeight }}"
         @endif></ins>
    <script>(adsbygoogle=window.adsbygoogle||[]).push({});</script>
</div>
@endif
