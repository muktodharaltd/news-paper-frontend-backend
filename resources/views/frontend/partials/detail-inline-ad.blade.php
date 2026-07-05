@if(!empty($ad) && $ad->displayUsesLocalAd())
    @php $boxStyle = $ad->slotBoxStyle('box'); @endphp
    <div class="not-prose ad-section my-6 w-full mx-auto" data-ad-slot-root>
        <a href="{{ advertisement_click_url($ad) }}" target="_blank" rel="noopener" class="ad-slot-frame ad-slot-local block img-placeholder group cursor-pointer relative overflow-hidden bg-white w-full" style="{{ $boxStyle }}">
            <x-ad-picture :ad="$ad" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" />
        </a>
    </div>
@elseif(!empty($ad) && ad_show_google($ad))
    <div class="not-prose ad-section my-6 w-full mx-auto" data-ad-slot-root data-ad-google>
        <x-google-ad-unit :ad="$ad" layout="box" />
    </div>
@endif
