@php
    $isLatestListing = ($listingSlug ?? '') === 'latest';
@endphp
@foreach($posts as $post)
<article class="flex flex-col md:flex-row gap-2 md:gap-4 last:pb-0 category-post-item">
    <a
        href="{{ route('news.show', [$post->slug]) }}"
        class="w-full md:w-auto flex-shrink-0">
        <div class="img-placeholder w-full md:w-[305px] h-[200px] md:h-[170px] overflow-hidden">
            <img src="{{ $post->image ? storage_image_url($post->image) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600' }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover"
                onload="this.parentElement.classList.remove('img-placeholder')">
        </div>
    </a>
    <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
        <a href="{{ route('news.show', [$post->slug]) }}">
            <h3 class="{{ $isLatestListing ? 'text-lg md:text-xl' : 'text-xl md:text-xl' }} font-bold serif text-title leading-snug hover:text-primary transition-colors">
                {{ $post->title }}
            </h3>
        </a>
        @if($post->description)
            <p class="{{ $isLatestListing ? 'text-base md:text-base line-clamp-2' : 'hidden md:block text-sm md:text-base line-clamp-1' }} font-normal text-desc leading-relaxed md:line-clamp-1">
                {!! html_entity_decode(Str::limit(strip_tags($post->description), 100)) !!}
            </p>
        @endif
        @if($isLatestListing)
        <x-post-list-meta :post="$post" />
        @else
        <div class="flex items-center gap-1.5 mt-auto text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <span class="text-xs font-medium text-gray-500">
                {{ published_at($post->created_at, 'd M Y') }}
            </span>
        </div>
        @endif
    </div>
</article>
@endforeach
