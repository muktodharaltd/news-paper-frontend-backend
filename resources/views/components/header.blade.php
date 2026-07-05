<div
    x-data="{
        showSidebar: false,
        showSearch: false,
        isSticky: false,
        hasHeaderAd: false,
        headerAdHeight: 0,
        syncHeaderAd() {
            const ad = document.getElementById('header-ad-slot');
            if (!ad) {
                this.hasHeaderAd = false;
                this.headerAdHeight = 0;
                ad?.classList.remove('header-ad-sticky');
                return;
            }
            this.hasHeaderAd = true;
            this.headerAdHeight = ad.offsetHeight;
            if (this.isSticky) {
                ad.classList.add('header-ad-sticky');
            } else {
                ad.classList.remove('header-ad-sticky');
            }
        },
        updateSticky() {
            this.isSticky = window.scrollY > 120;
            this.syncHeaderAd();
        }
    }"
    @scroll.window="updateSticky()"
    x-init="
        $data.syncHeaderAd();
        window.addEventListener('load', () => $data.syncHeaderAd(), { once: true });
        $data.updateSticky();
    ">
    <!-- Mobile Fixed / Desktop Shared Wrapper -->
    <div id="site-header-shell" class="fixed top-0 left-0 w-full max-w-full z-50 bg-white md:relative md:z-auto border-b border-slate-100 md:border-b-0 shadow-sm md:shadow-none overflow-x-clip">
        <!-- Main Header -->
        <header class="bg-white pt-0 md:pt-1 overflow-x-clip max-w-full">
            <!-- Sidebar Drawer Contents -->
            <template x-teleport="body">
                <div>
                    <!-- Backdrop -->
                    <div
                        x-show="showSidebar"
                        x-transition:opacity
                        @click="showSidebar = false"
                        class="fixed inset-0 bg-black/50 z-[200] backdrop-blur-sm"
                        x-cloak></div>

                    <!-- Drawer Content -->
                    <div
                        x-show="showSidebar"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="fixed inset-y-0 left-0 w-80 bg-primary text-white z-[210] shadow-2xl pt-2 px-6 pb-6 overflow-y-auto"
                        x-cloak>
                        <div class="flex justify-end items-center mb-2 pb-2 border-b border-white/20">
                            <button @click="showSidebar = false" class="p-2 text-white hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <ul class="header-drawer-menu space-y-2">
                            <li class="border-b border-white/30 pb-2"><a href="{{ front_home_url() }}" class="block text-xl font-medium text-white hover:text-white/80 transition-colors flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    হোম
                                </a></li>
                            {{-- সর্বশেষ সর্বদা বাম পাশের প্রথম মেনু (স্থান পরিবর্তন হবে না) --}}
                            <li class="border-b border-white/30 pb-1"><a href="{{ route('latest') }}" class="block text-2xl font-medium text-white hover:text-white/80 transition-colors">সর্বশেষ</a></li>
                            <li class="border-b border-white/30 pb-1"><a href="{{ route('special-news') }}" class="block text-2xl font-medium text-white hover:text-white/80 transition-colors">বিশেষ সংবাদ</a></li>
                            @if(isset($sideMenuCategories) && $sideMenuCategories->isNotEmpty())
                            @foreach($sideMenuCategories as $cat)
                            <li class="border-b border-white/30 pb-1">
                                <a href="{{ route('category.show', $cat->slug) }}" class="block text-2xl font-medium text-white hover:text-white/80 transition-colors">
                                    {{ $cat->name }}
                                </a>
                            </li>
                            @endforeach
                            @endif
                        </ul>

                        <div class="mt-4">
                            <x-header-utility-links variant="drawer" />
                        </div>

                        <!-- Sidebar Social Links (Mobile/Drawer) -->
                        <div class="mt-6 flex items-center justify-center gap-5">
                            @if(!empty(optional($siteMeta)->facebook_link))
                            <a href="{{ $siteMeta->facebook_link }}" target="_blank" rel="noopener noreferrer" class="text-[#1877F2] hover:opacity-80 transition-opacity" title="Facebook" aria-label="Facebook">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            @endif
                            @if(!empty(optional($siteMeta)->twitter_link))
                            <a href="{{ $siteMeta->twitter_link }}" target="_blank" rel="noopener noreferrer" class="text-black hover:opacity-70 transition-opacity" title="Twitter" aria-label="Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            @endif
                            @if(!empty(optional($siteMeta)->instagram_link))
                            <a href="{{ $siteMeta->instagram_link }}" target="_blank" rel="noopener noreferrer" class="text-[#E4405F] hover:opacity-80 transition-opacity" title="Instagram" aria-label="Instagram">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </a>
                            @endif
                            @if(!empty(optional($siteMeta)->youtube_link))
                            <a href="{{ $siteMeta->youtube_link }}" target="_blank" rel="noopener noreferrer" class="text-[#FF0000] hover:opacity-80 transition-opacity" title="YouTube" aria-label="YouTube">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                                </svg>
                            </a>
                            @endif
                            @if(!empty(optional($siteMeta)->map_link))
                            <a href="{{ $siteMeta->map_link }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:opacity-80 transition-opacity" title="{{ optional($siteMeta)->map_desc ?: 'অবস্থান' }}" aria-label="{{ optional($siteMeta)->map_desc ?: 'অবস্থান' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 384 512" aria-hidden="true">
                                    <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </template>

            <x-ad-slot-display slug="header" variant="header" />

            <div class="container pt-1 md:pt-1 pb-0 text-center overflow-visible">
                <div class="flex items-center justify-between mb-0 md:mb-1">
                    <div class="flex-1 text-left">
                        <div class="hidden md:flex items-center gap-2 md:gap-3">
                            <!-- Menu Icon -->
                            <button @click="showSidebar = true" class="  transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="4" x2="20" y1="12" y2="12"></line>
                                    <line x1="4" x2="20" y1="6" y2="6"></line>
                                    <line x1="4" x2="20" y1="18" y2="18"></line>
                                </svg>
                            </button>

                            <!-- Search Feature -->
                            <div class="flex items-center">
                                <button @click="showSearch = !showSearch" class="p-2 hover:bg-slate-100  transition-colors transition-all duration-300">
                                    <svg x-show="!showSearch" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <svg x-show="showSearch" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>

                                <form action="{{ route('search') }}" method="GET" class="ml-2"
                                    x-show="showSearch"
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 -translate-x-4 scale-95"
                                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                    x-transition:leave="transition ease-in duration-200 transform"
                                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                                    x-transition:leave-end="opacity-0 -translate-x-4 scale-95"
                                    x-cloak>
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="অনুসন্ধান করুন..." class="bg-slate-100 border-0 px-4 py-2 text-sm md:text-base focus:ring-2 focus:ring-primary w-48 md:w-72 outline-none transition-all">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex justify-center items-center px-2">
                        <a href="{{ front_home_url() }}">
                            @if(!empty(optional($siteMeta)->site_logo))
                            <img src="{{ storage_image_url($siteMeta->site_logo) }}" alt="{{ optional($siteMeta)->site_name ?? 'Logo' }}" class="h-11 md:h-18 w-auto object-contain" onerror="this.src='{{ asset('logo.svg') }}'; this.onerror=null;">
                            @else
                            <img src="{{ asset('logo.svg') }}" alt="{{ site_name_bn() }}" class="h-11 md:h-18 w-auto object-contain">
                            @endif
                        </a>
                    </div>
                    <div class="flex-1 text-right flex justify-end items-center gap-2">
                        <!-- Mobile Search Trigger (Far Right) -->
                        <button @click="showSearch = true" class="md:hidden p-2 hover:bg-slate-100 transition-colors shrink-0 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-black">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <x-push-prompt />

                <!-- Full Width Mobile Search Overlay (Covers Logo Section) -->
                <form action="{{ route('search') }}" method="GET"
                    x-show="showSearch"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-[-100%]"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-[-100%]"
                    class="md:hidden fixed inset-x-0 top-0 h-[68px] md:h-[120px] bg-white z-[150] flex items-center px-4 shadow-xl border-b-2 border-primary"
                    x-cloak>
                    <div class="flex-1 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary shrink-0">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="অনুসন্ধান করুন..."
                            class="flex-1 bg-transparent border-0 py-2 text-lg font-bold focus:ring-0 outline-none placeholder:text-slate-400 min-w-0"
                            @keydown.escape="showSearch = false"
                            x-init="$watch('showSearch', value => value && $nextTick(() => $el.focus()))">
                        <button type="button" @click="showSearch = false" class="p-2 text-slate-400 hover:text-primary transition-colors shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Top Utility Bar -->
                <div class="flex justify-between items-center text-slate-800 text-sm md:text-base font-normal mb-0 hidden md:flex relative">
                    <div class="flex items-center gap-2 md:gap-2.5 header-date">
                        <x-icons.calendar-days class="w-4 h-4 md:w-5 md:h-5 shrink-0 text-slate-900 block" />
                        @php
                        $en_d = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $bn_d = ['রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'];
                        $en_m = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $bn_m = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
                        $en_n = range(0, 9);
                        $bn_n = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

                        $now = now()->startOfDay();
                        $d = str_replace($en_d, $bn_d, $now->format('l'));
                        $m = str_replace($en_m, $bn_m, $now->format('F'));
                        $dn = str_replace($en_n, $bn_n, $now->format('d'));
                        $yn = str_replace($en_n, $bn_n, $now->format('Y'));

                        // Lifelong Accurate Bangabda Converter (Revised)
                        function getLifelongBanglaDate($date) {
                        $y = (int)$date->format('Y');
                        $m = (int)$date->format('m');
                        $d = (int)$date->format('d');

                        $is_leap = ($y % 4 == 0 && ($y % 100 != 0 || $y % 400 == 0));

                        // April 14 is the fixed start of Boishakh 1
                        $new_year = \Carbon\Carbon::create($y, 4, 14)->startOfDay();
                        if ($date->lt($new_year)) {
                        $new_year = \Carbon\Carbon::create($y - 1, 4, 14)->startOfDay();
                        }

                        // Get absolute difference in days
                        $diff = (int)abs($date->diffInDays($new_year));

                        $bn_months = ["বৈশাখ", "জ্যৈষ্ঠ", "আষাঢ়", "শ্রাবণ", "ভাদ্র", "আশ্বিন", "কার্তিক", "অগ্রহায়ণ", "পৌষ", "মাঘ", "ফাল্গুন", "চৈত্র"];
                        $month_days = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 29, 30];
                        if ($is_leap) { $month_days[10] = 30; } // Falgun gets 30 days in leap year in revised calendar

                        $bn_day_val = $diff + 1;
                        $bn_m_idx = 0;

                        for ($i = 0; $i < 12; $i++) {
                            if ($bn_day_val <=$month_days[$i]) {
                            $bn_m_idx=$i;
                            break;
                            }
                            $bn_day_val -=$month_days[$i];
                            }

                            $bn_year=($m> 4 || ($m == 4 && $d >= 14)) ? $y - 593 : $y - 594;

                            $bn_digits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                            $bn_d_str = str_replace(range(0, 9), $bn_digits, (string)$bn_day_val);
                            $bn_y_str = str_replace(range(0, 9), $bn_digits, (string)$bn_year);

                            return "$bn_d_str {$bn_months[$bn_m_idx]} $bn_y_str";
                            }

                            $bangla_date = getLifelongBanglaDate($now);
                            @endphp
                            <span class="leading-normal tracking-normal">{{ $d }}, {{ $dn }} {{ $m }} {{ $yn }}, {{ $bangla_date }} বঙ্গাব্দ</span>
                    </div>
                    <div class="flex items-center gap-3 relative">
                        <x-header-utility-links class="hidden md:flex" />
                        @if(!empty(optional($siteMeta)->facebook_link))
                        <a href="{{ $siteMeta->facebook_link }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-primary transition-colors" title="Facebook" aria-label="Facebook">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty(optional($siteMeta)->twitter_link))
                        <a href="{{ $siteMeta->twitter_link }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-primary transition-colors" title="Twitter" aria-label="Twitter">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty(optional($siteMeta)->instagram_link))
                        <a href="{{ $siteMeta->instagram_link }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-primary transition-colors" title="Instagram" aria-label="Instagram">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty(optional($siteMeta)->youtube_link))
                        <a href="{{ $siteMeta->youtube_link }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-primary transition-colors" title="YouTube" aria-label="YouTube">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty(optional($siteMeta)->map_link))
                        <a href="{{ $siteMeta->map_link }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-primary transition-colors" title="{{ optional($siteMeta)->map_desc ?: 'অবস্থান' }}" aria-label="{{ optional($siteMeta)->map_desc ?: 'অবস্থান' }}">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 384 512" aria-hidden="true">
                                <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Placeholder for sticky header ad + nav (desktop) -->
        <div x-show="isSticky && hasHeaderAd" class="hidden md:block" :style="`height: ${headerAdHeight}px`" x-cloak></div>
        <div x-show="isSticky" class="hidden md:block md:h-[68px]" x-cloak></div>

        <nav
            class="z-50 bg-white md:border-b border-slate-200 transition-all duration-300"
            :class="isSticky ? 'py-1 md:fixed md:left-0 md:w-full md:py-1 max-md:shadow-[0_10px_32px_-6px_rgba(15,23,42,0.16),0_-10px_32px_-6px_rgba(15,23,42,0.16)] md:shadow-none' : 'relative py-1 md:pt-2 md:pb-0 max-md:shadow-[0_6px_22px_-4px_rgba(15,23,42,0.12),0_-6px_22px_-4px_rgba(15,23,42,0.12)] md:shadow-none'"
            :style="isSticky ? (hasHeaderAd ? { top: headerAdHeight + 'px' } : { top: '0px' }) : null">
            <div class="container">
                <div
                    class="flex items-center transition-all duration-300 border-t-0 md:border-t-2"
                    :class="isSticky ? 'md:border-transparent pt-0.5 md:pt-0' : 'md:border-black py-0.5 md:py-1'">
                    <!-- Smooth Sliding Sidebar Trigger & Logo Short (Desktop Only) -->
                    <div
                        class="hidden md:flex items-center gap-2 overflow-hidden transition-all duration-500 ease-in-out"
                        :class="isSticky ? 'md:w-20 md:opacity-100 md:mr-2' : 'md:w-0 md:opacity-0 md:mr-0'">
                        <button @click="showSidebar = true" class="  transition-all text-black hover:text-primary shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                                <line x1="4" x2="20" y1="6" y2="6"></line>
                                <line x1="4" x2="20" y1="18" y2="18"></line>
                            </svg>
                        </button>

                        <a href="{{ front_home_url() }}" class="w-8 h-8 md:w-9 md:h-9 rounded-full flex items-center justify-center bg-white text-primary font-normal serif text-lg md:text-xl shadow border border-black/5 hover:bg-primary/5 transition-colors shrink-0 overflow-hidden" title="{{ optional($siteMeta)->site_name ?? 'হোম' }}">
                            @if(!empty(optional($siteMeta)->site_icon))
                            <img src="{{ storage_image_url($siteMeta->site_icon) }}" alt="" width="32" height="32" class="w-full h-full object-cover rounded-full" loading="lazy">
                            @else
                            <span class="leading-none">D</span>
                            @endif
                        </a>
                    </div>

                    <div class="flex-1 overflow-x-auto overflow-y-hidden no-scrollbar py-0 flex items-center justify-between">
                        <ul class="flex justify-start items-center gap-3 md:gap-4.5 text-[1.3125rem] md:text-[1.375rem] whitespace-nowrap py-0.5 md:py-0.5">
                            {{-- সর্বশেষ সর্বদা হেডার মেনুর বাম পাশের প্রথম আইটেম (স্থান অপরিবর্তিত) --}}
                            <li><a href="{{ route('latest') }}" class="font-normal hover:text-primary border-b-2 border-transparent hover:border-primary pb-1 transition-all {{ request()->routeIs('latest') ? 'text-primary border-primary' : '' }}">সর্বশেষ</a></li>
                            <li><a href="{{ route('special-news') }}" class="font-normal hover:text-primary border-b-2 border-transparent hover:border-primary pb-1 transition-all {{ request()->routeIs('special-news') ? 'text-primary border-primary' : '' }}">বিশেষ সংবাদ</a></li>
                            @if(isset($headerCategories) && $headerCategories->isNotEmpty())
                            @foreach($headerCategories as $cat)
                            <li>
                                <a href="{{ route('category.show', $cat->slug) }}"
                                    class="font-normal hover:text-primary border-b-2 border-transparent hover:border-primary pb-1 transition-all {{ request()->is('category/'.$cat->slug) ? 'text-primary border-primary' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                            @endforeach
                            @endif
                        </ul>

                        <!-- Mobile Menu Icon (Far Right of Categories) -->
                        <button @click="showSidebar = true" class="md:hidden p-1.5 ml-2 bg-white sticky right-0 shadow-[-10px_0_10px_-5px_rgba(255,255,255,0.9)] z-10 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black">
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                                <line x1="4" x2="20" y1="6" y2="6"></line>
                                <line x1="4" x2="20" y1="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>