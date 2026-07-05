<x-layout>
    <x-slot:title>{{ $category->name }} - {{ site_name_bn() }}</x-slot>

        <x-ad-slot-display slug="category_below_menu" variant="banner" />

        <div class="pt-2 pb-4 md:pt-4 md:pb-10 min-h-screen">
            <div class="container">
                @php \Carbon\Carbon::setLocale('bn'); @endphp

                <div class="mb-3 md:mb-6 text-left">
                    <h1 class="text-2xl md:text-3xl font-semibold serif text-title mb-3">{{ $category->name }}</h1>

                    @if(!empty($selectedDate))
                    @php
                        $bnMonths = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
                        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                        $toBn = fn ($value) => str_replace(range(0, 9), $bnDigits, (string) $value);
                    @endphp
                    <p class="text-sm md:text-base text-desc mb-3">
                        {{ $toBn($selectedDate->day) }} {{ $bnMonths[$selectedDate->month - 1] }} {{ $toBn($selectedDate->year) }} পর্যন্ত প্রকাশিত সংবাদ
                    </p>
                    @endif

                    <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-3 md:mb-4">
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

                    <div class="w-full border-b border-slate-300 relative mb-3 md:mb-5">
                        <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                    </div>
                </div>

                <div class="archive-calendar-slot mb-3 md:hidden">
                    <x-archive-calendar
                        :calendar-month="$calendarMonth"
                        :selected-date="$selectedDate"
                        :dates-with-posts="$datesWithPosts"
                        :archive-years="$archiveYears" />
                </div>

                <style>
                    .archive-grid {
                        display: grid;
                        gap: 0.75rem;
                        grid-template-columns: 1fr;
                    }

                    @@media (min-width: 768px) {
                        .archive-grid {
                            grid-template-columns: 7.4fr 2.9fr;
                            gap: 0;
                        }
                    }
                </style>

                <section class="archive-grid">
                    <div class="bg-white p-0 md:pt-2 md:pb-4 md:pr-4 flex flex-col gap-3 md:gap-5">
                        <div id="category-posts-list" class="flex flex-col gap-3 md:gap-5">
                            @forelse($posts as $post)
                            <article class="flex flex-col md:flex-row gap-2 md:gap-4 last:pb-0 category-post-item">
                                <a href="{{ route('news.show', [$post->slug]) }}" class="w-full md:w-auto flex-shrink-0">
                                    <div class="img-placeholder w-full md:w-[350px] aspect-video overflow-hidden shrink-0">
                                        <img src="{{ $post->image ? storage_image_url($post->image) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600' }}"
                                            alt="{{ $post->title }}"
                                            class="w-full h-full object-cover"
                                            onload="this.parentElement.classList.remove('img-placeholder')">
                                    </div>
                                </a>
                                <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                                    <a href="{{ route('news.show', [$post->slug]) }}">
                                        <h3 class="text-lg md:text-lg font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </h3>
                                    </a>
                                    @if($post->description)
                                        <p class="text-base font-normal text-desc leading-relaxed line-clamp-3 max-md:line-clamp-2">
                                            {!! html_entity_decode(\Illuminate\Support\Str::limit(strip_tags($post->description), 250)) !!}
                                        </p>
                                    @endif
                                    <x-post-list-meta :post="$post" />
                                </div>
                            </article>
                            @empty
                            <p class="text-desc text-center py-10">
                                @if(!empty($selectedDate))
                                    এই তারিখে কোনো সংবাদ পাওয়া যায়নি।
                                @else
                                    কোনো সংবাদ পাওয়া যায়নি।
                                @endif
                            </p>
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

                    <div class="hidden md:flex flex-col gap-4 w-full min-w-0 md:pl-4">
                        <div class="archive-calendar-slot">
                            <x-archive-calendar
                                :calendar-month="$calendarMonth"
                                :selected-date="$selectedDate"
                                :dates-with-posts="$datesWithPosts"
                                :archive-years="$archiveYears" />
                        </div>

                        @php
                        $adCategoryRight1 = ad_slot('category_right_1');
                        $adCategoryRight2 = ad_slot('category_right_2');
                        @endphp
                        <x-ad-slot-display :ad="$adCategoryRight1" variant="sidebar" sidebar-class="max-w-[300px] mx-auto" />
                        <x-ad-slot-display :ad="$adCategoryRight2" variant="sidebar" sidebar-class="max-w-[300px] mx-auto" />
                    </div>
                </section>
            </div>
        </div>

        <script>
        (function() {
            var calendarUrl = @json(route('archive.calendar'));
            var loading = false;

            function selectedDateFromUrl() {
                return new URL(window.location.href).searchParams.get('date') || '';
            }

            function loadArchiveCalendars(monthParam) {
                if (!monthParam || loading) {
                    return;
                }

                loading = true;
                var url = new URL(calendarUrl, window.location.origin);
                url.searchParams.set('month', monthParam);

                var selectedDate = selectedDateFromUrl();
                if (selectedDate && selectedDate.indexOf(monthParam) === 0) {
                    url.searchParams.set('date', selectedDate);
                }

                fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(function(res) {
                        if (!res.ok) {
                            throw new Error('calendar fetch failed');
                        }
                        return res.json();
                    })
                    .then(function(data) {
                        if (!data.html) {
                            return;
                        }
                        document.querySelectorAll('.archive-calendar-slot').forEach(function(slot) {
                            slot.innerHTML = data.html;
                        });
                    })
                    .catch(function() {})
                    .finally(function() {
                        loading = false;
                    });
            }

            document.addEventListener('click', function(event) {
                var nav = event.target.closest('[data-archive-cal-month]');
                if (!nav || nav.disabled) {
                    return;
                }

                event.preventDefault();
                loadArchiveCalendars(nav.getAttribute('data-archive-cal-month'));
            });

            document.addEventListener('change', function(event) {
                if (!event.target.matches('.archive-month-select, .archive-year-select')) {
                    return;
                }

                var wrap = event.target.closest('.archive-calendar-wrap');
                if (!wrap) {
                    return;
                }

                var monthSelect = wrap.querySelector('.archive-month-select');
                var yearSelect = wrap.querySelector('.archive-year-select');
                if (!monthSelect || !yearSelect) {
                    return;
                }

                loadArchiveCalendars(yearSelect.value + '-' + monthSelect.value);
            });
        })();
        </script>
</x-layout>
