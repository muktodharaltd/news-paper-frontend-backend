@foreach($posts as $post)
<article class="flex flex-row-reverse md:flex-row-reverse gap-2 md:gap-4 py-3 md:py-4 border-b border-gray-100 last:border-0 special-news-post-item">
    <a href="{{ news_url($post) }}" class="flex-shrink-0">
        <div class="img-placeholder w-36 h-24 md:w-[305px] md:h-[170px]">
            <img src="{{ storage_image_url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover" onload="this.parentElement.classList.remove('img-placeholder')">
        </div>
    </a>
    <div class="flex flex-col justify-start gap-1 md:gap-2 pt-0 md:pt-1 md:px-0 flex-1">
        <a href="{{ news_url($post) }}">
            <h3 class="text-lg md:text-xl font-bold serif text-title leading-snug hover:text-primary transition-colors line-clamp-1">{{ $post->title }}</h3>
        </a>
        <p class="hidden md:block text-sm md:text-base font-normal text-desc leading-relaxed line-clamp-1">{{ html_entity_decode(\Illuminate\Support\Str::limit(strip_tags($post->description), 100)) }}</p>
        <div class="flex items-center gap-1.5 mt-auto text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span class="text-[10px] md:text-xs font-medium text-gray-500">{{ list_published_at($post->created_at) }}</span>
        </div>
    </div>
</article>
@endforeach
