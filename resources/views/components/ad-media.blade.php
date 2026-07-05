{{-- ইমেজ / GIF / আপলোডেড ভিডিও / YouTube — সব অ্যাড স্লট --}}
@props(['ad', 'autoplay' => false, 'muted' => true, 'loop' => true, 'controls' => false])

@if($ad && ad_has_media($ad))
    @if(filled($ad->video_youtube_id))
        <div class="relative w-full h-full min-h-0 min-w-0 max-w-full aspect-video">
            <iframe
                src="https://www.youtube-nocookie.com/embed/{{ $ad->video_youtube_id }}?rel=0{{ $autoplay ? '&autoplay=1&mute=1' : '' }}"
                title="{{ $ad->caption ?? 'বিজ্ঞাপন ভিডিও' }}"
                class="absolute inset-0 h-full w-full border-0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                loading="lazy"
            ></iframe>
        </div>
    @elseif(filled($ad->video) || filled($ad->video_mobile))
        @if(filled($ad->video_mobile))
        <video
            class="block h-full w-full min-h-0 min-w-0 max-w-full object-cover md:hidden {{ $attributes->get('class') }}"
            @if($autoplay) autoplay @endif
            @if($muted) muted @endif
            @if($loop) loop @endif
            @if($controls) controls @endif
            playsinline
            preload="metadata"
            onloadeddata="this.closest('.img-placeholder')?.classList.remove('img-placeholder')"
        >
            <source src="{{ storage_image_url($ad->video_mobile) }}" type="video/mp4">
        </video>
        @endif
        @if(filled($ad->video))
        <video
            class="block h-full w-full min-h-0 min-w-0 max-w-full object-cover {{ filled($ad->video_mobile) ? 'hidden md:block' : '' }} {{ $attributes->get('class') }}"
            @if($autoplay) autoplay @endif
            @if($muted) muted @endif
            @if($loop) loop @endif
            @if($controls) controls @endif
            playsinline
            preload="metadata"
            onloadeddata="this.closest('.img-placeholder')?.classList.remove('img-placeholder')"
        >
            <source src="{{ storage_image_url($ad->video) }}" type="video/mp4">
        </video>
        @elseif(filled($ad->video_mobile))
        <video
            class="hidden md:block h-full w-full min-h-0 min-w-0 max-w-full object-cover {{ $attributes->get('class') }}"
            @if($autoplay) autoplay @endif
            @if($muted) muted @endif
            @if($loop) loop @endif
            @if($controls) controls @endif
            playsinline
            preload="metadata"
            onloadeddata="this.closest('.img-placeholder')?.classList.remove('img-placeholder')"
        >
            <source src="{{ storage_image_url($ad->video_mobile) }}" type="video/mp4">
        </video>
        @endif
    @elseif(filled($ad->image) || filled($ad->image_mobile))
        <picture class="flex h-full w-full min-h-0 min-w-0 max-w-full items-center justify-center">
            @if(filled($ad->image_mobile))
            <source media="(max-width: 767px)" srcset="{{ storage_image_url($ad->image_mobile) }}">
            @endif
            <img
                src="{{ storage_image_url($ad->image ?: $ad->image_mobile) }}"
                alt="{{ $ad->caption ?? 'বিজ্ঞাপন' }}"
                loading="eager"
                decoding="sync"
                @if($attributes->get('fetchpriority')) fetchpriority="{{ $attributes->get('fetchpriority') }}" @endif
                onload="this.closest('.img-placeholder')?.classList.remove('img-placeholder')"
                {{ $attributes->except(['autoplay', 'muted', 'loop', 'controls', 'fetchpriority'])->merge(['class' => 'max-w-full min-w-0']) }}
            />
        </picture>
    @endif
@endif
