<x-layout>
    <x-slot:title>ভিডিও বিস্তারিত - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen bg-white">
        <div class="container">
            @php
                \Carbon\Carbon::setLocale('bn');
                $categoryName = "ভিডিও";
            @endphp

            <!-- Breadcrumbs -->
            <div class="mb-4 md:mb-10 text-left">
                <div class="flex flex-wrap items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                    <a href="/" class="text-slate-500 hover:text-primary transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="m9 18 6-6-6-6"/></svg>
                    <a href="/videos" class="text-slate-500 hover:text-primary transition-all">{{ $categoryName }}</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-black font-bold">ভিডিও বিস্তারিত</span>
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
            </style>

            <!-- Main Layout Grid -->
            <section class="details-grid">
                
                <!-- প্রথম কলাম (৮ ভাগ) -->
                <div class="flex flex-col gap-6 w-full">
                    <!-- শিরোনাম -->
                    <h1 class="text-2xl md:text-4xl font-bold serif text-title leading-tight">
                        জাতীয় সংসদের বাজেট অধিবেশনের সরাসরি সম্প্রচার ও বিশেষ আলোচনা
                    </h1>

                    <div class="flex flex-col gap-1 pb-2 mb-2">
                        <span class="text-lg font-bold text-title leading-tight">ডিজিটাল ভিডিও ডেস্ক</span>
                        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 gap-4">
                            <span class="text-sm md:text-base text-desc">প্রকাশ : ১২ মার্চ ২০২৬, ১৫:২০</span>
                            
                            <!-- সোশ্যাল শেয়ার আইকনসমূহ -->
                            <div class="flex items-center gap-3">
                                <a href="#" class="w-8 h-8 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#3b5998] hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                                </a>
                                <a href="#" class="w-8 h-8 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#25D366] hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ভিডিও ও শিরোনামের মাঝখানে ছোট ডেসক্রিপশন (Full Width) -->
                    <div class="w-full">
                        <p class="text-lg md:text-xl font-medium text-desc leading-relaxed">
                            জাতীয় সংসদের বাজেট অধিবেশনের সরাসরি সম্প্রচার ও বিশেষ আলোচনা। বাজেটের গুরুত্বপূর্ণ দিকগুলো এবং সাধারণ মানুষের জীবনে এর প্রভাব নিয়ে আমাদের বিশেষ আয়োজন এই ভিডিও প্রতিবেদনে। সরাসরি সংসদ ভবন থেকে আমাদের প্রতিনিধিদের ধারণ করা ভিডিও চিত্রগুলো দেখুন।
                        </p>
                    </div>

                    <!-- ভিডিও প্লেয়ার সেকশন -->
                    <div class="w-full bg-black aspect-video relative group overflow-hidden shadow-2xl">
                        {{-- Placeholder for Video --}}
                        <div class="absolute inset-0 img-placeholder">
                            <img src="https://loremflickr.com/1280/720/parliament,inside?lock=4" 
                                 class="w-full h-full object-cover opacity-60"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        
                        {{-- Play Toggle --}}
                        <div class="absolute inset-0 flex items-center justify-center cursor-pointer group-hover:bg-black/10 transition-all">
                            <div class="w-20 h-20 bg-primary/90 text-white rounded-full flex items-center justify-center shadow-2xl transform transition-transform group-hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                        {{-- Duration/Live Badge --}}
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary text-white text-[10px] font-black px-2 py-0.5 uppercase tracking-widest animate-pulse">Live</span>
                        </div>
                    </div>

                    <!-- ভিডিও ডেসক্রিপশন বা খবর -->
                    <div class="prose prose-lg max-w-none text-title text-xl font-medium space-y-6 pt-4 px-0 lg:px-[100px] text-justify leading-[1.8]">
                        <p>
                            জাতীয় সংসদে ২০২৬-২৭ অর্থবছরের বাজেট অধিবেশন শুরু হয়েছে। অধিবেশনে অর্থ মন্ত্রণালয় থেকে বাজেট প্রস্তাবনা পেশ করা হচ্ছে। সরাসরি এই অধিবেশনে সংসদ সদস্যদের বিভিন্ন গুরুত্বপূর্ণ আলোচনা ও প্রস্তাবনাগুলো ভিডিওর মাধ্যমে দেখতে পাচ্ছেন।
                        </p>
                        <p>
                            বিশেষজ্ঞরা বলছেন, এবারের বাজেট দেশের অর্থনীতির জন্য অত্যন্ত গুরুত্বপূর্ণ। বিশেষ করে দেশের ডিজিটাল অবকাঠামো এবং শিক্ষা খাতে বড় ধরনের বরাদ্দের কথা ভাবছে সরকার। বিস্তারিত বিশ্লেষন দেখুন ভিডিওটিতে।
                        </p>
                        
                        <!-- শেয়ার বাটন নিচের দিকেও থাকবে -->
                         <div x-data="{ showIcons: false }" class="flex items-center gap-4 mt-8 no-print">
                            <button @click="showIcons = !showIcons" 
                                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white font-bold hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                </svg>
                                <span class="text-sm md:text-base">ভিডিওটি শেয়ার করুন</span>
                            </button>

                            <div x-show="showIcons" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-[-20px]"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 class="flex items-center gap-3">
                                <a href="#" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#3b5998] hover:bg-[#3b5998] hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg></a>
                                <a href="#" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326z"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- দ্বিতীয় কলাম (৩ ভাগ) -->
                <div class="flex flex-col gap-10 w-full">
                    
                    <!-- বিজ্ঞাপন -->
                    <div class="w-full bg-slate-50 border border-slate-100 p-4 pt-10 text-center relative h-[400px]">
                        <span class="absolute top-2 left-2 text-[10px] uppercase font-bold text-slate-400">বিজ্ঞাপন</span>
                        <div class="flex items-center justify-center h-full text-slate-300 font-bold italic">
                            প্রোডাক্ট অ্যাড হিয়ার
                        </div>
                    </div>

                    <!-- আরও ভিডিও (সাইডবার) -->
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                            <div class="w-1.5 h-6 bg-primary"></div>
                            <h3 class="text-xl font-bold serif text-title">আরও ভিডিও</h3>
                        </div>

                        <!-- ভিডিও ১ -->
                        <a href="/video-details" class="group flex flex-col gap-2">
                            <div class="relative aspect-video overflow-hidden">
                                <img src="https://loremflickr.com/400/225/football?lock=100" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-8 h-8 bg-black/60 text-white rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-title group-hover:text-primary">বিপিএল ফাইনাল হাইলাইটস: কে জিতলো শিরোপা?</h4>
                        </a>

                        <!-- ভিডিও ২ -->
                        <a href="/video-details" class="group flex flex-col gap-2">
                            <div class="relative aspect-video overflow-hidden">
                                <img src="https://loremflickr.com/400/225/city?lock=101" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-8 h-8 bg-black/60 text-white rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-title group-hover:text-primary">রাজধানীর ট্রাফিক জ্যাম নিরসনে নতুন প্রকল্পের শুভ উদ্বোধন</h4>
                        </a>
                    </div>

                </div>

            </section>

        </div>
    </div>
</x-layout>
