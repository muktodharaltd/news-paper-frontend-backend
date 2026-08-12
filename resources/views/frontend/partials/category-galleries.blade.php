@foreach($galleries as $gallery)
@php $thumb = $gallery->images->first(); @endphp
<article class="flex flex-col md:flex-row gap-2 md:gap-4 pb-4 border-b border-custom last:border-0 last:pb-0 category-gallery-item">
    <a href="{{ route('gallery.show', $gallery->slug) }}" class="w-full md:w-auto flex-shrink-0">
        <div class="img-placeholder w-full md:w-[305px] h-[200px] md:h-[170px] overflow-hidden">
            <img src="{{ $thumb ? storage_image_url($thumb->image) : 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?w=600' }}"
                alt="{{ $gallery->title }}"
                class="w-full h-full object-cover"
                onload="this.parentElement.classList.remove('img-placeholder')">
        </div>
    </a>
    <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
        <a href="{{ route('gallery.show', $gallery->slug) }}">
            <h3 class="text-xl md:text-base font-bold serif text-title leading-snug hover:text-primary transition-colors">
                {{ $gallery->title }}
            </h3>
        </a>
        @if($gallery->description)
        <p class="hidden md:block text-sm font-semibold text-desc leading-relaxed line-clamp-2">
            {{ Str::limit(html_entity_decode(strip_tags($gallery->description)), 160) }}
        </p>
        @endif
        <div class="flex items-center gap-1.5 mt-auto text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <span class="text-xs font-medium text-gray-500">
                {{ published_at($gallery->created_at, 'd M Y') }}
            </span>
        </div>
    </div>
</article>
@endforeach
