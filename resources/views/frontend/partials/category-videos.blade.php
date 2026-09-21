@foreach($videos as $video)
@php
$thumb = null;
if ($video->image) {
    $thumb = \Illuminate\Support\Str::startsWith($video->image, ['http://', 'https://'])
        ? $video->image
        : storage_image_url($video->image);
} elseif ($video->youtube_link && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video->youtube_link, $matches)) {
    $thumb = 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg';
}
@endphp
<article class="flex flex-col md:flex-row gap-4 md:gap-6 last:pb-0 group category-video-item">
    <a href="{{ route('videos.show', $video->slug) }}" class="relative w-full md:w-[320px] h-[210px] md:h-[180px] shrink-0 overflow-hidden block">
        @if($thumb)
        <div class="img-placeholder w-full h-full">
            <img src="{{ $thumb }}"
                alt="{{ $video->title }}"
                class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                onload="this.parentElement.classList.remove('img-placeholder')">
        </div>
        @else
        <div class="w-full h-full bg-black flex items-center justify-center">
            <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
        @endif
        <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/40 transition-all">
            <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg transform group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
    </a>
    <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
        <a href="{{ route('videos.show', $video->slug) }}">
            <h3 class="text-xl md:text-2xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                {{ $video->title }}
            </h3>
        </a>
        @if($video->description)
        <p class="text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-2">
            {{ Str::limit(html_entity_decode(strip_tags($video->description)), 200) }}
        </p>
        @endif
        <div class="flex items-center gap-1.5 mt-auto text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />
            </svg>
            <span class="text-xs font-semibold">{{ list_published_at($video->created_at) }}</span>
        </div>
    </div>
</article>
@endforeach
