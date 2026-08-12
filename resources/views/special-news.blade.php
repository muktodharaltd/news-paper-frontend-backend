<x-layout>
    <x-slot:title>বিশেষ সংবাদ - {{ site_name_bn() }}</x-slot>

        <div class="py-4 md:py-10 min-h-screen">
            <div class="container">
                @php
                $categoryName = "বিশেষ সংবাদ";
                @endphp
                <!-- Category Header -->
                <div class="mb-10 text-left">
                    <h1 class="text-2xl md:text-3xl font-bold serif text-title mb-4">{{ $categoryName }}</h1>

                    <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-6">
                        <!-- Home Icon -->
                        <a href="{{ front_home_url() }}" class="text-slate-500 hover:text-primary transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-100">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-black font-bold">{{ $categoryName }}</span>
                    </div>

                    <div class="w-full border-b border-slate-100 relative mb-8">
                        <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                    </div>
                </div>

                <style>
                    .special-grid {
                        display: grid;
                        gap: 0rem;
                        grid-template-columns: 1fr;
                    }

                    @media (min-width: 768px) {
                        .special-grid {
                            grid-template-columns: 9.1fr 2.9fr;
                        }

                        .special-grid > * {
                            min-width: 0;
                        }
                    }

                    .national-grid {
                        display: grid;
                        gap: 0rem;
                        grid-template-columns: 1fr;
                    }

                    @media (min-width: 768px) {
                        .national-grid {
                            grid-template-columns: 1.7fr 7.4fr 2.9fr;
                        }
                    }
                </style>

                <section class="special-grid">
                    <!-- বাম কলাম: সংবাদ তালিকা (9.1) -->
                    @php
                    \Carbon\Carbon::setLocale('bn');
                    @endphp

                    <div class="bg-white flex flex-col gap-0 md:border-r md:border-slate-200 pr-0 md:pr-3">

                        @if(isset($heroPosts) && $heroPosts->count() > 0)
                        @php $featured = $heroPosts->first(); @endphp
                        {{-- প্রধান নিউজ কার্ড (Featured Article) – নতুন প্রথমে --}}
                        <article class="flex flex-col-reverse md:flex-row gap-3 pb-3 last:border-0 text-left border-b border-gray-100">
                            <div class="flex flex-col justify-start gap-3 flex-1">
                                <a href="{{ news_url($featured) }}">
                                    <h3 class="text-2xl md:text-3xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                        <span class="text-primary">{{ $categoryName }} /</span> {{ $featured->title }}
                                    </h3>
                                </a>
                                <p class="text-base font-normal text-desc leading-relaxed md:hidden">
                                    {{ \Illuminate\Support\Str::words(html_entity_decode(strip_tags($featured->description)), 18, '...') }}
                                </p>
                                <p class="text-base font-normal text-desc leading-relaxed hidden md:block">
                                    {{ \Illuminate\Support\Str::words(html_entity_decode(strip_tags($featured->description)), 32, '...') }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-auto text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span class="text-xs font-medium">{{ published_at($featured->created_at, 'd M Y') }}</span>
                                </div>
                            </div>
                            <div class="min-w-0 group overflow-hidden w-full md:w-auto md:max-w-[55%]">
                                <a href="{{ news_url($featured) }}">
                                    <div class="img-placeholder w-full aspect-video md:aspect-[625/355]">
                                        <img
                                            src="{{ storage_image_url($featured->image) }}"
                                            alt="{{ $featured->title }}"
                                            class="w-full h-full object-cover"
                                            onload="this.parentElement.classList.remove('img-placeholder')">
                                    </div>
                                </a>
                            </div>
                        </article>

                        {{-- পরবর্তী ৩টা পোস্ট গ্রিড --}}
                        @php $gridPosts = $heroPosts->slice(1, 3); @endphp
                        @if($gridPosts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-0 pt-3">
                            @foreach($gridPosts as $index => $post)
                            <article class="flex flex-row-reverse md:flex-col gap-2 md:gap-3 pb-4 border-b border-gray-100 md:border-b-0 md:pb-0 {{ $index < 2 ? 'md:pr-3 md:border-r md:border-slate-200' : 'md:pl-3' }}">
                                <a href="{{ news_url($post) }}" class="group overflow-hidden shrink-0">
                                    <div class="img-placeholder w-36 h-24 md:w-full md:h-[100px]"><img src="{{ storage_image_url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover" onload="this.parentElement.classList.remove('img-placeholder')"></div>
                                </a>
                                <div class="flex flex-col gap-1 flex-1">
                                    <a href="{{ news_url($post) }}">
                                        <h4 class="text-base md:text-xl font-bold serif text-title leading-snug hover:text-primary transition-colors line-clamp-1">{{ $post->title }}</h4>
                                    </a>
                                    <p class="hidden md:block text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-1">{{ html_entity_decode(\Illuminate\Support\Str::limit(strip_tags($post->description), 100)) }}</p>
                                    <div class="flex items-center gap-1.5 mt-1 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        <span class="text-[10px] md:text-xs font-medium">{{ published_at($post->created_at, 'd M Y') }}</span>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                        @endif
                        @else
                        <p class="py-10 text-center text-slate-500">কোন বিশেষ সংবাদ নেই।</p>
                        @endif
                    </div>

                    <!-- ডান কলাম: সর্বশেষ / পঠিত ট্যাব -->
                    <div class="flex flex-col pl-0 md:pl-3 mt-4 md:mt-0">

                        <!-- Tab Bar -->
                        <div class="flex w-full border-b border-gray-200 mb-4 px-4 md:px-0">
                            <button id="sp-tab-latest" onclick="switchSpTab('latest')" class="flex-1 text-lg font-bold py-2 border-b-2 border-primary text-primary -mb-px transition-all duration-200 text-center">
                                সর্বশেষ
                            </button>
                            <button id="sp-tab-popular" onclick="switchSpTab('popular')" class="flex-1 text-lg font-bold py-2 border-b-2 border-transparent text-gray-400 -mb-px hover:text-primary transition-all duration-200 text-center">
                                পঠিত
                            </button>
                        </div>

                        <!-- সর্বশেষ Panel -->
                        <div id="sp-panel-latest" class="space-y-4">
                            @forelse(isset($latestSidebarPosts) ? $latestSidebarPosts : [] as $index => $post)
                            @php $bn = ['১','২','৩','৪','৫','৬']; $num = $bn[$index] ?? ($index + 1); @endphp
                            <a href="{{ news_url($post) }}" class="group cursor-pointer flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0 block">
                                <span class="text-2xl font-bold text-gray-400 serif shrink-0 leading-none">{{ $num }}.</span>
                                <div class="flex-1">
                                    <h4 class="text-base font-bold serif leading-snug group-hover:text-primary transition-colors text-left text-title mt-0.5">{{ $post->title }}</h4>
                                </div>
                                <div class="overflow-hidden shrink-0 border border-gray-100 shadow-sm" style="width:96px; height:72px; min-width:96px; min-height:72px;">
                                    <img src="{{ storage_image_url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                </div>
                            </a>
                            @empty
                            <p class="text-slate-500 text-sm">কোন সংবাদ নেই।</p>
                            @endforelse
                        </div>

                        <!-- পঠিত Panel (hidden by default) -->
                        <div id="sp-panel-popular" class="space-y-4 hidden">
                            @forelse(isset($popularSidebarPosts) ? $popularSidebarPosts : [] as $index => $post)
                            @php $bn = ['১','২','৩','৪','৫','৬']; $num = $bn[$index] ?? ($index + 1); @endphp
                            <a href="{{ news_url($post) }}" class="group cursor-pointer flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0 block">
                                <span class="text-2xl font-bold text-gray-400 serif shrink-0 leading-none">{{ $num }}.</span>
                                <div class="flex-1">
                                    <h4 class="text-base font-bold serif leading-snug group-hover:text-primary transition-colors text-left text-title mt-0.5">{{ $post->title }}</h4>
                                </div>
                                <div class="overflow-hidden shrink-0 border border-gray-100 shadow-sm" style="width:96px; height:72px; min-width:96px; min-height:72px;">
                                    <img src="{{ storage_image_url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                </div>
                            </a>
                            @empty
                            <p class="text-slate-500 text-sm">কোন সংবাদ নেই।</p>
                            @endforelse
                        </div>


                        <!-- কমন আরও বাটন -->
                        <div class="pt-4">
                            <a href="{{ route('latest') }}" class="block w-full text-center bg-primary hover:opacity-90 text-white font-bold text-sm py-2 rounded transition-opacity">
                                আরও →
                            </a>
                        </div>

                        <script>
                            function switchSpTab(tab) {
                                document.getElementById('sp-panel-latest').classList.toggle('hidden', tab !== 'latest');
                                document.getElementById('sp-panel-popular').classList.toggle('hidden', tab !== 'popular');
                                document.getElementById('sp-tab-latest').className = 'flex-1 text-lg font-bold py-2 border-b-2 -mb-px transition-all duration-200 text-center ' + (tab === 'latest' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-primary');
                                document.getElementById('sp-tab-popular').className = 'flex-1 text-lg font-bold py-2 border-b-2 -mb-px transition-all duration-200 text-center ' + (tab === 'popular' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-primary');
                            }
                        </script>

                    </div>
                </section>

                <!-- Section: National Page Style (Exact Copy) -->
                <section class="national-grid mt-6 md:mt-12 pt-3 md:pt-12 border-t border-slate-200">
                    <!-- প্রথম কলাম: সরু -->
                    <div class="p-4">
                        {{-- কন্টেন্ট আপাতত খালি --}}
                    </div>

                    <!-- মাঝের কলাম: হিরোর ৪টার পর বাকি বিশেষ সংবাদ -->
                    <div class="bg-white px-0 md:p-4 flex flex-col gap-0">
                        <div id="special-news-posts-list" class="flex flex-col gap-0">
                            @if($belowPosts->count() > 0)
                                @include('frontend.partials.special-news-posts', ['posts' => $belowPosts])
                            @endif
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
                            var list = document.getElementById('special-news-posts-list');
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

                    <!-- ডান পাশের কলাম: বিজ্ঞাপন স্লট — আপাতত খালি -->
                    <div class="hidden md:block" aria-hidden="true"></div>
                </section>
            </div>
        </div>
</x-layout>