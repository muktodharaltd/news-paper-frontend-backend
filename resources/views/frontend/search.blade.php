<x-layout>
    <x-slot:title>অনুসন্ধান: {{ $query ?: request('q') }} - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen">
        <div class="container">
            @php \Carbon\Carbon::setLocale('bn'); @endphp

            <!-- Header (category-style) -->
            <div class="mb-4 md:mb-10 text-left">
                <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-3">
                    {{ $query ?: 'অনুসন্ধান' }}
                </h1>

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
                    <span class="text-black font-bold uppercase tracking-widest">{{ $query ?: 'অনুসন্ধান' }}</span>
                </div>

                <div class="w-full border-b border-slate-100 relative mb-4 md:mb-8">
                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                </div>
            </div>

            <style>
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

            <section class="national-grid">
                <div class="p-0 md:p-4"></div>

                <div class="bg-white p-0 md:p-4 flex flex-col gap-3 md:gap-5" id="search-items-list">
                    @forelse($items as $item)
                        @if($loop->first)
                            @include('frontend.partials.search-items', ['items' => $items])
                            @break
                        @endif
                    @empty
                        <p class="text-desc text-center py-10">কোন ফলাফল পাওয়া যায়নি।</p>
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
                    var list = document.getElementById('search-items-list');
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

            </section>
        </div>
    </div>
</x-layout>
