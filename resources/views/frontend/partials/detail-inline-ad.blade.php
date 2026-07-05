@if(!empty($ad) && $ad->displayUsesLocalAd())
    @php $boxStyle = $ad->slotBoxStyle('box'); @endphp
    <div class="not-prose ad-section detail-inline-ad w-full mx-auto" data-ad-slot-root data-ad-inline>
        <a href="{{ advertisement_click_url($ad) }}" target="_blank" rel="noopener" class="ad-slot-frame ad-slot-local block group cursor-pointer relative overflow-hidden bg-white w-full" style="{{ $boxStyle }}">
            <x-ad-picture :ad="$ad" class="w-full h-auto object-contain group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" fetchpriority="high" />
        </a>
    </div>
@elseif(!empty($ad) && ad_show_google($ad, 'inline'))
    <div class="not-prose ad-section detail-inline-ad w-full mx-auto" data-ad-slot-root data-ad-google data-ad-inline>
        <x-google-ad-unit :ad="$ad" layout="box" slot-context="inline" />
    </div>
@endif
