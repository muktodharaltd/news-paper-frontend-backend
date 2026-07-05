<x-layout>
    <x-slot:title>{{ $category->name }} - {{ site_name_bn() }}</x-slot>

        <x-ad-slot-display slug="gallery_category_below_menu" variant="banner" />

        <div class="pt-2 pb-4 md:pt-4 md:pb-10 min-h-screen">
            <div class="container">
                @php \Carbon\Carbon::setLocale('bn'); @endphp

                <!-- Category Header -->
                <div class="mb-4 md:mb-10 text-left">
                    <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-4">{{ $category->name }}</h1>

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
                        <span class="text-black font-bold">{{ $category->name }}</span>
                    </div>

                    <div class="w-full border-b border-slate-300 relative mb-4 md:mb-8">
                        <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                    </div>
                </div>

                <style>
                    .national-grid {
                        display: grid;
                        gap: 0rem;
                        grid-template-columns: 1fr;
                    }

                    @@media (min-width: 768px) {
                        .national-grid {
                            grid-template-columns: 1.7fr 7.4fr 2.9fr;
                        }
                    }
                </style>

                <section class="national-grid">
                    <div class="p-0 md:p-4"></div>

                    <!-- মাঝের কলাম: গ্যালারি তালিকা -->
                    <div class="bg-white p-0 md:p-4 flex flex-col gap-3 md:gap-5">
                        <div id="category-posts-list" class="flex flex-col gap-3 md:gap-5">
                            @forelse($galleries as $gallery)
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
                                        {{ Str::limit(strip_tags($gallery->description), 160) }}
                                    </p>
                                    @endif
                                    <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500">
                                            {{ $gallery->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                            @empty
                            <p class="text-desc text-center py-10">এই category-তে কোনো গ্যালারি পাওয়া যায়নি।</p>
                            @endforelse
                        </div>

                        @if(!empty($hasMore) && !empty($nextPageUrl))
                        <div class="mt-6 flex justify-center" id="load-more-wrap">
                            <button type="button" id="load-more-btn" data-next-url="{{ $nextPageUrl }}"
                                class="px-8 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition-colors shadow-sm">
                                আরও
                            </button>
                        </div>
                        <script>
                        (function() {
                            var btn = document.getElementById('load-more-btn');
                            var list = document.getElementById('category-posts-list');
                            var wrap = document.getElementById('load-more-wrap');
                            if (!btn || !list) return;
                            btn.addEventListener('click', function() {
                                var url = btn.getAttribute('data-next-url');
                                if (!url) return;
                                btn.disabled = true;
                                btn.textContent = 'লোড হচ্ছে...';
                                var xhr = new XMLHttpRequest();
                                xhr.open('GET', url, true);
                                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                                xhr.setRequestHeader('Accept', 'application/json');
                                xhr.onload = function() {
                                    btn.disabled = false;
                                    btn.textContent = 'আরও';
                                    if (xhr.status !== 200) return;
                                    try {
                                        var res = JSON.parse(xhr.responseText);
                                        if (res.html) {
                                            var div = document.createElement('div');
                                            div.innerHTML = res.html.trim();
                                            while (div.firstChild) list.appendChild(div.firstChild);
                                        }
                                        if (res.next_page_url) {
                                            btn.setAttribute('data-next-url', res.next_page_url);
                                        } else {
                                            wrap.style.display = 'none';
                                        }
                                    } catch (e) {}
                                };
                                xhr.onerror = function() { btn.disabled = false; btn.textContent = 'আরও'; };
                                xhr.send();
                            });
                        })();
                        </script>
                        @endif
                    </div>

                    @php
                    $adGalleryCategoryRight1 = ad_slot('gallery_category_right_1');
                    $adGalleryCategoryRight2 = ad_slot('gallery_category_right_2');
                    $hasGalleryCategorySidebarAds = ad_should_display($adGalleryCategoryRight1)
                        || ad_should_display($adGalleryCategoryRight2);
                    @endphp
                    @if($hasGalleryCategorySidebarAds)
                    <div class="flex flex-col gap-4 w-full min-w-0 md:justify-self-end">
                        <x-ad-slot-display :ad="$adGalleryCategoryRight1" variant="sidebar" sidebar-class="max-w-[300px] mx-auto" />
                        <x-ad-slot-display :ad="$adGalleryCategoryRight2" variant="sidebar" sidebar-class="max-w-[300px] mx-auto" />
                    </div>
                    @endif

                </section>
            </div>
        </div>
</x-layout>