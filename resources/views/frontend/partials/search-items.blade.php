@foreach($items as $item)
<article class="flex flex-col md:flex-row gap-2 md:gap-4 last:pb-0">
    <a href="{{ $item->url }}" class="w-full md:w-auto flex-shrink-0">
        <div class="img-placeholder w-full md:w-[305px] h-[200px] md:h-[170px] overflow-hidden">
            <img src="{{ $item->image }}"
                alt="{{ $item->title }}"
                class="w-full h-full object-cover"
                onload="this.parentElement.classList.remove('img-placeholder')">
        </div>
    </a>
    <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
        <a href="{{ $item->url }}">
            <h3 class="text-xl md:text-xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                {{ $item->title }}
            </h3>
        </a>
        @if($item->snippet)
        <p class="hidden md:block text-sm md:text-base font-normal text-desc leading-relaxed line-clamp-1">
            {!! $item->snippet !!}
        </p>
        @endif
        <div class="flex items-center gap-1.5 mt-auto text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <span class="text-xs font-medium text-gray-500">
                {{ list_published_at($item->created_at) }}
            </span>
        </div>
    </div>
</article>
@endforeach
