<x-layout>
    <x-slot:title>ভিডিও - {{ site_name_bn() }}</x-slot>

        <div class="py-4 md:py-10 min-h-screen">
            <div class="container">
                @php \Carbon\Carbon::setLocale('bn'); @endphp

                <!-- Category Header -->
                <div class="mb-4 md:mb-10 text-left">
                    <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-4">ভিডিও</h1>

                    <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                        <a href="/" class="text-slate-500 hover:text-primary transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-black font-bold">ভিডিও</span>
                    </div>

                    <div class="w-full border-b border-slate-300 relative mb-4 md:mb-8">
                        <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                    </div>
                </div>

                <style>
                    .videos-grid {
                        display: grid;
                        gap: 0rem;
                        grid-template-columns: 1fr;
                    }

                    @media (min-width: 768px) {
                        .videos-grid {
                            grid-template-columns: 1.7fr 7.4fr 2.9fr;
                        }
                    }
                </style>

                <section class="videos-grid">
                    <!-- প্রথম কলাম -->
                    <div class="p-0 md:p-4"></div>

                    <!-- মাঝের কলাম: ভিডিও তালিকা -->
                    <div class="bg-white p-0 md:p-4 flex flex-col gap-6 md:gap-8">

                        @forelse($videos as $video)
                        @php
                        $thumb = null;
                        if ($video->image) {
                        $thumb = \Illuminate\Support\Str::startsWith($video->image, ['http://', 'https://'])
                        ? $video->image
                        : storage_image_url($video->image);
                        } elseif ($video->youtube_link) {
                        if (preg_match('/(?:youtube\\.com\\/(?:[^\\/]+\\/.+\\/|(?:v|e(?:mbed)?)\\/|.*[?&]v=)|youtu\\.be\\/)([^\"&?\\/\\s]{11})/i', $video->youtube_link, $matches)) {
                        $thumb = 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg';
                        }
                        }
                        @endphp
                        <article class="flex flex-col md:flex-row gap-4 md:gap-6 last:pb-0 group">
                            {{-- ভিডিও থাম্বনেইল --}}
                            <a href="{{ route('news.show', $video->slug) }}" class="relative w-full md:w-[320px] h-[210px] md:h-[180px] shrink-0 overflow-hidden block">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                {{-- Play Overlay --}}
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/40 transition-all">
                                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            {{-- টাইটেল + বিবরণ --}}
                            <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                                <a href="{{ route('news.show', $video->slug) }}">
                                    <h3 class="text-xl md:text-2xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                        {{ $video->title }}
                                    </h3>
                                </a>
                                @if($video->description)
                                <p class="text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($video->description)), 200) }}
                                </p>
                                @endif
                                <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span class="text-xs font-semibold">
                                        {{ $video->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </article>
                        @empty
                        <p class="text-desc text-center py-10">কোনো ভিডিও পাওয়া যায়নি।</p>
                        @endforelse

                        @if($videos->hasPages())
                        <div class="mt-4">{{ $videos->links() }}</div>
                        @endif
                    </div>

                </section>
            </div>
        </div>
</x-layout>