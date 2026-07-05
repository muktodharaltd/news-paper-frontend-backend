<x-layout>
    <x-slot:title>{{ $gallery->title }} - {{ site_name_bn() }}</x-slot>

        <div class="py-4 md:py-10 min-h-screen bg-white">
            <div class="container">
                @php \Carbon\Carbon::setLocale('bn'); @endphp

                <!-- Breadcrumbs -->
                <div class="mb-4 md:mb-10 text-left no-print">
                    <div class="flex flex-wrap items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                        <a href="/" class="text-slate-500 hover:text-primary transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <a href="{{ route('gallery.index') }}" class="text-slate-500 hover:text-primary transition-all">গ্যালারি</a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-black font-bold">গ্যালারি বিস্তারিত</span>
                    </div>

                    <div class="w-full border-b border-slate-300 relative mb-8">
                        <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                    </div>
                </div>

                <style>
                    .details-grid {
                        display: grid;
                        gap: 0.7rem;
                        grid-template-columns: 1fr;
                    }

                    @media (min-width: 1024px) {
                        .details-grid {
                            grid-template-columns: 9fr 3fr;
                        }
                    }

                    /* প্রিন্ট সেটিংস */
                    @media print {
                        header, footer, x-header, x-footer, .md\:fixed, nav, 
                        #globalScrollToTopBtn, .details-grid > div:nth-child(2), 
                        .mt-12, .flex.items-center.gap-3, .ad-section, 
                        .flex.flex-col.gap-1.pb-2, .sub-nav, .search-overlay,
                        .no-print,
                        [class*="ad-"], [class*="advertisement"], .img-placeholder::before, .img-placeholder::after {
                            display: none !important;
                        }

                        body, .bg-white {
                            background: white !important;
                            color: black !important;
                        }
                        
                        .container {
                            width: 100% !important;
                            max-width: 100% !important;
                            padding: 0 !important;
                            margin: 0 !important;
                        }

                        .details-grid {
                            display: block !important;
                        }

                        .prose {
                            padding-left: 0 !important;
                            padding-right: 0 !important;
                            max-width: 100% !important;
                        }
                    }
                </style>

                <!-- Main Layout Grid -->
                <section class="details-grid">

                    <!-- প্রথম কলাম (৮ ভাগ) -->
                    <div class="flex flex-col gap-6 w-full">
                        <!-- শিরোনাম -->
                        <h1 class="text-2xl md:text-4xl font-bold serif text-title leading-tight">
                            {{ $gallery->title }}
                        </h1>

                        <div class="flex flex-col gap-1 pb-2 mb-2">
                            <span class="text-lg font-bold text-title leading-tight">ফটো ডেস্ক</span>
                            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 gap-4">
                                <span class="text-sm md:text-base text-desc">প্রকাশ : {{ published_at($gallery->created_at) }}</span>

                                <!-- সোশ্যাল শেয়ার আইকনসমূহ -->
                                <div class="flex items-center gap-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#3b5998] hover:text-white transition-all" title="Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                    </a>
                                    <a href="javascript:window.print()" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all" title="Print">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- গ্যালারি ডেসক্রিপশন -->
                        @if($gallery->description)
                        <div class="w-full">
                            <p class="text-lg md:text-xl font-medium text-desc leading-relaxed">
                                {{ $gallery->description }}
                            </p>
                        </div>
                        @endif

                        <!-- ফিচারড ইমেজ (প্রথম ছবি) -->
                        @php $firstImage = $gallery->images->first(); @endphp
                        @if($firstImage)
                        <div class="w-full">
                            <div class="img-placeholder w-full aspect-[3/2] overflow-hidden shadow-md">
                                <img src="{{ storage_image_url($firstImage->image) }}"
                                    alt="{{ $gallery->title }}"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            @if($firstImage->description)
                            <p class="text-base md:text-lg font-medium text-desc mt-3 leading-relaxed">
                                {{ $firstImage->description }}
                            </p>
                            @endif

                            <!-- শেয়ার বাটন -->
                            <div x-data="{ showIcons: false }" class="flex items-center gap-4 mt-4">
                                <button @click="showIcons = !showIcons"
                                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white font-bold hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="18" cy="5" r="3"></circle>
                                        <circle cx="6" cy="12" r="3"></circle>
                                        <circle cx="18" cy="19" r="3"></circle>
                                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                    </svg>
                                    <span class="text-sm md:text-base">শেয়ার করুন</span>
                                </button>

                                <div x-show="showIcons"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-x-[-20px]"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100 translate-x-0"
                                    x-transition:leave-end="opacity-0 translate-x-[-20px]"
                                    class="flex items-center gap-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#3b5998] hover:bg-[#3b5998] hover:text-white transition-all shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode(request()->url()) }}" target="_blank" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- বাকি ছবিগুলো: প্রথম ছবির মতোই – ছবি তার নিচে বর্ণনা -->
                        @if($gallery->images->count() > 1)
                        <div class="space-y-6 mt-6">
                            @foreach($gallery->images->skip(1) as $image)
                            <div>
                                <div class="img-placeholder w-full aspect-[3/2] overflow-hidden shadow-md">
                                    <img src="{{ storage_image_url($image->image) }}"
                                        alt="{{ $image->description ?? $gallery->title }}"
                                        class="w-full h-full object-cover"
                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                </div>
                                @if($image->description)
                                <p class="text-base md:text-lg font-medium text-desc mt-3 leading-relaxed">
                                    {{ $image->description }}
                                </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- দ্বিতীয় কলাম (৩ ভাগ) -->
                    <div class="flex flex-col gap-10 w-full">

                        <!-- গ্যালারির আরও ছবি (সাইডবার) -->
                        @if($otherGalleries->count())
                        <div class="hidden lg:flex flex-col gap-6 pt-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                                <div class="w-1.5 h-6 bg-primary"></div>
                                <h3 class="text-xl font-bold serif text-title">আরও ছবি</h3>
                            </div>

                            @foreach($otherGalleries->take(2) as $other)
                            @php $otherThumb = $other->images->first(); @endphp
                            <a href="{{ route('gallery.show', $other->slug) }}" class="group cursor-pointer flex flex-col gap-2">
                                <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                    <img src="{{ $otherThumb ? storage_image_url($otherThumb->image) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=400' }}"
                                        alt="{{ $other->title }}"
                                        class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                </div>
                                <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                    {{ $other->title }}
                                </h4>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                </section>

                <!-- আরও ছবি (একদম নিচে) -->
                @if($otherGalleries->count())
                <div class="mt-12 md:mt-[100px] pt-8 md:pt-[60px]">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2 h-8 bg-primary"></div>
                        <h3 class="text-xl md:text-3xl font-bold serif text-title">গ্যালারির আরও খবর</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($otherGalleries as $other)
                        @php $t = $other->images->first(); @endphp
                        <a href="{{ route('gallery.show', $other->slug) }}" class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="{{ $t ? storage_image_url($t->image) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=400' }}"
                                    alt="{{ $other->title }}"
                                    class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                {{ $other->title }}
                            </h4>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
</x-layout>