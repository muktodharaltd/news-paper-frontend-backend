<x-layout>
    <x-slot:title>ভিডিও - {{ site_name_bn() }}</x-slot>

        <div class="py-4 md:py-10 min-h-screen">
            <div class="container">
                @php
                    $categoryName = "ভিডিও";
                @endphp
                <!-- Category Header -->
                <div class="mb-4 md:mb-10 text-left">
                    <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-4">{{ $categoryName }}</h1>

                    <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                        <!-- Home Icon -->
                        <a href="/" class="text-slate-500 hover:text-primary transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="m9 18 6-6-6-6"/></svg>
                        <span class="text-black font-bold">{{ $categoryName }}</span>
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
                    <!-- প্রথম কলাম: সরু (ঐচ্ছিক কন্টেন্ট বা স্পেসার) -->
                    <div class="p-0 md:p-4">
                        {{-- কন্টেন্ট আপাতত খালি --}}
                    </div>

                    <!-- মাঝের কলাম: ভিডিও তালিকা -->
                    @php 
                        \Carbon\Carbon::setLocale('bn'); 
                    @endphp

                    <div class="bg-white p-0 md:p-4 flex flex-col gap-6 md:gap-8">
 
                        {{-- ভিডিও আইটেম ১ --}}
                        <article class="flex flex-col md:flex-row gap-4 md:gap-6 last:pb-0 group">
                            {{-- ভিডিও থাম্বনেইল --}}
                            <a href="/video-details" class="relative w-full md:w-[320px] h-[210px] md:h-[180px] shrink-0 overflow-hidden block">
                                <div class="img-placeholder w-full h-full">
                                    <img src="https://loremflickr.com/640/360/news,video?lock=1" 
                                         alt="ভিডিও থাম্বনেইল"
                                         class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" 
                                         onload="this.parentElement.classList.remove('img-placeholder')" >
                                </div>
                                {{-- Play Overlay --}}
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/40 transition-all">
                                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                                {{-- Duration --}}
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                    ০৩:৪৫
                                </div>
                            </a>
                            {{-- টাইটেল + বিবরণ --}}
                            <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                                <a href="/video-details">
                                    <h3 class="text-xl md:text-2xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                        জাতীয় সংসদের বাজেট অধিবেশনের সরাসরি সম্প্রচার
                                    </h3>
                                </a>
                                <p class="text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-2">
                                    আজকের বাজেট অধিবেশনে গুরুত্বপূর্ণ প্রস্তাবনা নিয়ে আলোচনা করছেন সংসদ সদস্যরা। ২০২৬-২৭ অর্থবছরের বাজেট নিয়ে বিস্তারিত দেখুন আমাদের এই প্রতিবেদনে।
                                </p>
                                <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span class="text-xs font-semibold">
                                        {{ list_published_at(\Carbon\Carbon::now()->subHours(2)) }}
                                    </span>
                                </div>
                            </div>
                        </article>

                        {{-- ভিডিও আইটেম ২ --}}
                        <article class="flex flex-col md:flex-row gap-4 md:gap-6 last:pb-0 group">
                            <a href="/video-details" class="relative w-full md:w-[320px] h-[210px] md:h-[180px] shrink-0 overflow-hidden block">
                                <div class="img-placeholder w-full h-full">
                                    <img src="https://loremflickr.com/640/360/sports,stadium?lock=2" 
                                         alt="স্পোর্টস ভিডিও"
                                         class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" 
                                         onload="this.parentElement.classList.remove('img-placeholder')" >
                                </div>
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/40 transition-all">
                                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                    ০৫:২০
                                </div>
                            </a>
                            <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                                <a href="/video-details">
                                    <h3 class="text-xl md:text-2xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                        বিপিএল ২০২৬: গ্যালারিতে উন্মাদনা ও রুদ্ধশ্বাস মুহূর্ত
                                    </h3>
                                </a>
                                <p class="text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-2">
                                    স্টেডিয়ামের উত্তেজনা এখন সবার হাতের মুঠোয়। বিপিএলের শেষ ওভারের সেই থ্রিলার মিস করলে এখনই দেখে নিন ভিডিওটি।
                                </p>
                                <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span class="text-xs font-semibold">
                                        {{ list_published_at(\Carbon\Carbon::now()->subHours(5)) }}
                                    </span>
                                </div>
                            </div>
                        </article>

                        {{-- ভিডিও আইটেম ৩ --}}
                        <article class="flex flex-col md:flex-row gap-4 md:gap-6 last:pb-0 group">
                            <a href="/video-details" class="relative w-full md:w-[320px] h-[210px] md:h-[180px] shrink-0 overflow-hidden block">
                                <div class="img-placeholder w-full h-full">
                                    <img src="https://loremflickr.com/640/360/nature,river?lock=3" 
                                         alt="প্রকৃতি ভিডিও"
                                         class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" 
                                         onload="this.parentElement.classList.remove('img-placeholder')" >
                                </div>
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/40 transition-all">
                                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full shadow-lg transform group-hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                    ০৯:১৫
                                </div>
                            </a>
                            <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                                <a href="/video-details">
                                    <h3 class="text-xl md:text-2xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                        বর্ষায় বাংলার রূপ: ছোট এক নৌকায় নদী ভ্রমণ
                                    </h3>
                                </a>
                                <p class="text-sm md:text-base font-medium text-desc leading-relaxed line-clamp-2">
                                    বাংলার চিরচেনা রূপ আরও সুন্দর হয়ে ধরা দিয়েছে এই প্রতিবেদনে। যমুনা নদীর পাড় থেকে আমাদের প্রতিনিধির ধারণ করা বিশেষ ভিডিও।
                                </p>
                                <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span class="text-xs font-semibold">
                                        {{ list_published_at(\Carbon\Carbon::now()->subHours(10)) }}
                                    </span>
                                </div>
                            </div>
                        </article>

                    </div>

                    <!-- ডান পাশের কলাম: বিজ্ঞাপন -->
                    <div class="flex flex-col gap-4">

                        {{-- বিজ্ঞাপন লেবেল --}}
                        <div class="flex items-center gap-2 mb-1">
                            <div class="h-px flex-1 bg-slate-200"></div>
                            <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">বিজ্ঞাপন</span>
                            <div class="h-px flex-1 bg-slate-200"></div>
                        </div>

                        {{-- বিজ্ঞাপন ১ --}}
                        <div class="block overflow-hidden border border-slate-200 shadow-sm transition-all group">
                            <div class="relative h-[250px] w-full bg-gradient-to-br from-[#1e3a5f] to-[#0f172a] flex flex-col items-center justify-center p-8 text-center">
                                <h4 class="text-white font-bold text-lg mb-4">আপনার পণ্যের প্রচারণা আজই শুরু করুন</h4>
                                <a href="#" class="px-6 py-2 bg-primary text-white text-xs font-bold  hover:bg-primary/90 transition-all">বিস্তারিত দেখুন →</a>
                            </div>
                        </div>

                        {{-- বিজ্ঞাপন ২ --}}
                        <div class="block overflow-hidden border border-slate-200 shadow-sm transition-all group mt-4">
                             <div class="relative h-[180px] w-full bg-[#f8fafc] flex items-center justify-center p-4">
                                <span class="text-slate-400 font-bold">এনার্জি প্লাস অ্যাড</span>
                             </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
</x-layout>
