<x-layout>
    <x-slot:title>হিমালয়ের পাদদেশে সূর্যাস্তের মনোরম দৃশ্য - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen bg-white">
        <div class="container">
            @php
                \Carbon\Carbon::setLocale('bn');
                $categoryName = "গ্যালারি";
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
                    <a href="/gallery" class="text-slate-500 hover:text-primary transition-all">{{ $categoryName }}</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="m9 18 6-6-6-6"/></svg>
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
            </style>

            <!-- Main Layout Grid -->
            <section class="details-grid">
                
                <!-- প্রথম কলাম (৮ ভাগ) -->
                <div class="flex flex-col gap-6 w-full">
                    <!-- শিরোনাম -->
                    <h1 class="text-2xl md:text-4xl font-bold serif text-title leading-tight">
                        হিমালয়ের পাদদেশে সূর্যাস্তের মনোরম দৃশ্য
                    </h1>

                    <div class="flex flex-col gap-1 pb-2 mb-2">
                        <span class="text-lg font-bold text-title leading-tight">ফটো ডেস্ক</span>
                        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 gap-4">
                            <span class="text-sm md:text-base text-desc">প্রকাশ : ১২ মার্চ ২০২৬, ০২:৪৫</span>
                            
                            <!-- সোশ্যাল শেয়ার আইকনসমূহ -->
                            <div class="flex items-center gap-3">
                                <!-- Facebook -->
                                <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#3b5998] hover:bg-slate-800 hover:text-white transition-all" title="Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                                </a>
                                <!-- Messenger -->
                                <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0084ff] hover:bg-slate-800 hover:text-white transition-all" title="Messenger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z"/></svg>
                                </a>
                                <!-- X -->
                                <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-black hover:bg-slate-800 hover:text-white transition-all" title="X">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/></svg>
                                </a>
                                <!-- LinkedIn -->
                                <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0077b5] hover:bg-slate-800 hover:text-white transition-all" title="LinkedIn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H3.362v7.225h1.581zm-1-8.306c.564 0 1.022-.458 1.022-1.022 0-.564-.458-1.022-1.022-1.022-.564 0-1.022.458-1.022 1.022 0 .564.458 1.022 1.022 1.022zm11.035 8.306V9.759c0-1.847-.988-2.706-2.301-2.706-1.059 0-1.532.584-1.797.994V6.169h-1.582c.021.445 0 7.225 0 7.225h1.582V9.759c0-.399.028-.799.145-1.087.32-.799 1.05-1.625 2.275-1.625 1.605 0 2.247 1.223 2.247 3.016v4.185h1.582z"/></svg>
                                </a>
                                <!-- Print -->
                                <a href="javascript:window.print()" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all" title="Print This Article">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ছবি ও শিরোনামের মাঝখানে ছোট ডেসক্রিপশন (Full Width) -->
                    <div class="w-full">
                        <p class="text-lg md:text-xl font-medium text-desc leading-relaxed">
                            হিমালয়ের পর্বতমালায় ঘেরা এই অঞ্চলের প্রাকৃতিক দৃশ্য দেখে যে কেউই মুগ্ধ হতে বাধ্য। বিশেষ করে গোধূলি বেলায় পাহাড়ের গায়ে সূর্যের সোনালী আভা ফুটে ওঠা এক অনবদ্য দৃশ্য। এই গ্যালারিতে আমরা সেই সৌন্দর্যের কিছু চিত্র তুলে ধরেছি যা আপনাকে প্রকৃতির কাছাকাছি নিয়ে যাবে।
                        </p>

                    </div>

                    <!-- ফিচারড ইমেজ -->
                    <div class="w-full">
                        <div class="img-placeholder w-full aspect-[3/2] overflow-hidden shadow-md">
                            <img src="https://loremflickr.com/1200/800/nature,mountain?lock=10" 
                                 alt="প্রকৃতি" 
                                 class="w-full h-full object-cover"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        <p class="text-base md:text-lg font-medium text-desc mt-3 leading-relaxed">
                            হিমালয়ের সূর্যাস্ত। ছবি: সংগৃহীত
                        </p>

                        <!-- শেয়ার বাটন (Smooth Reveal) -->
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

                            <!-- সোশ্যাল আইকনসমূহ -->
                            <div x-show="showIcons" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-[-20px]"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 translate-x-[-20px]"
                                 class="flex items-center gap-3">
                                
                                <!-- Facebook -->
                                <a href="#" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#3b5998] hover:bg-[#3b5998] hover:text-white transition-all shadow-sm" title="Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                                </a>
                                <!-- Messenger -->
                                <a href="#" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#0084ff] hover:bg-[#0084ff] hover:text-white transition-all shadow-sm" title="Messenger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z"/></svg>
                                </a>
                                <!-- WhatsApp -->
                                <a href="#" class="w-9 h-9 border border-slate-200 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all shadow-sm" title="WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- বিজ্ঞাপন - ডেসক্রিপশনের উপরে -->
                    <div class="my-6 w-full">
                        <a href="#" class="flex justify-center">
                            <div class="img-placeholder w-[80%] h-[90px] overflow-hidden">
                                <img src="/top-banner.gif" 
                                     alt="Advertisement" 
                                     style="width: 100%; height: 90px; object-fit: cover;"
                                     class="shadow-sm"
                                     onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                        </a>
                    </div>

                    <!-- গ্যালারি ডেসক্রিপশন -->
                    <div class="prose prose-lg max-w-none text-title text-xl font-medium space-y-6 pt-4 px-0 lg:px-[125px] text-justify leading-[1.8]">
                        <p>
                            হিমালয় পর্বতমালা তার বিশালতা এবং সৌন্দর্যের জন্য বিশ্বখ্যাত। সূর্যাস্তের সময় যখন সূর্যের শেষ আভা বরফে ঢাকা পাহাড়ের চূড়ায় পড়ে, তখন এক স্বর্গীয় আবেশ তৈরি হয়। এই মনোরম দৃশ্যের সাক্ষী হতে প্রতি বছর হাজার হাজার পর্যটক ভিড় করেন নেপাল এবং ভারতের উত্তরাঞ্চলীয় এলাকাগুলোতে।
                        </p>
                        <p>
                            প্রকৃতিপ্রেমী এবং ফটোগ্রাফারদের জন্য এটি একটি নেশার মতো। বিশেষ করে অন্নপূর্ণা বেস ক্যাম্প বা এভারেস্ট ভিউ পয়েন্ট থেকে এই সূর্যাস্ত দেখা এক অনন্য অভিজ্ঞতা। যখন আকাশ ধীরে ধীরে লাল থেকে কমলা এবং তারপর বেগুনি রঙ ধারণ করে, তখন মনে হয় যেন সময় স্থির হয়ে গিয়েছে।
                        </p>
                        <p>
                            পরিবেশবিদরা বলছেন, গ্লোবাল ওয়ার্মিংয়ের কারণে হিমালয়ের এই শুভ্রতা দিন দিন ম্লান হচ্ছে। বরফ গলে যাওয়ার ফলে পরিবেশগত ভারসাম্য নষ্ট হচ্ছে। তাই এই অপরূপ সৌন্দর্য রক্ষা করা আমাদের সবার দায়িত্ব।
                        </p>
                        <p>
                            আমাদের গ্যালারি বিভাগে আজ আমরা হিমালয়ের সেই বিরল এবং অপূর্ব কিছু মুহূর্ত তুলে ধরেছি। আশা করি এই ছবিগুলো আপনাদের ভালো লাগবে এবং প্রকৃতির প্রতি ভালোবাসা আরও বাড়িয়ে দেবে।
                        </p>
                    </div>
                </div>

                <!-- দ্বিতীয় কলাম (৩ ভাগ) -->
                <div class="flex flex-col gap-10 w-full">
                    
                    <!-- বিজ্ঞাপন -->
                    <div class="w-full bg-slate-50 overflow-hidden border border-slate-100 shadow-sm">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest p-2 block bg-white/50 text-center">বিজ্ঞাপন</span>
                        <a href="#" class="block hover:opacity-95 transition-opacity">
                            <div class="img-placeholder w-full aspect-[2/3] overflow-hidden">
                                <img src="https://loremflickr.com/400/600/advertising,product?lock=100" 
                                     alt="Advertisement" 
                                     class="w-full h-full object-cover"
                                     onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                        </a>
                    </div>

                    <!-- গ্যালারির আরও ছবি (সাইডবার) -->
                    <div class="hidden lg:flex flex-col gap-6 pt-5">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                            <div class="w-1.5 h-6 bg-primary"></div>
                            <h3 class="text-xl font-bold serif text-title">আরও ছবি</h3>
                        </div>

                        <!-- আইটেম ১ -->
                        <a href="/gallery-details" class="group cursor-pointer flex flex-col gap-2">
                            <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                <img src="https://loremflickr.com/600/400/sports,football?lock=11" 
                                     alt="Sports" 
                                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                     onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                মাঠে ফিরছে ফুটবল, সমর্থকদের উপচে পড়া ভিড়
                            </h4>
                        </a>

                        <!-- আইটেম ২ -->
                        <a href="/gallery-details" class="group cursor-pointer flex flex-col gap-2">
                            <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                <img src="https://loremflickr.com/600/400/culture,festival?lock=12" 
                                     alt="Culture" 
                                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                     onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                পহেলা বৈশাখের মিলনমেলা, রঙিন হয়ে উঠেছে রাজপথ
                            </h4>
                        </a>
                    </div>

                </div>

            </section>

            <!-- আরও ছবি (একদম নিচে) -->
            <div class="mt-12 md:mt-[100px] pt-8 md:pt-[60px] ">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-2 h-8 bg-primary"></div>
                    <h3 class="text-xl md:text-3xl font-bold serif text-title">গ্যালারির আরও খবর</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- আইটেম ১ -->
                    <a href="/gallery-details" class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                        <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                            <img src="https://loremflickr.com/600/400/ocean,sunset?lock=20" 
                                 class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                            সাগরের বুকে সূর্যাস্ত: কুয়াকাটা সমুদ্র সৈকতের দৃশ্য
                        </h4>
                    </a>

                    <!-- আইটেম ২ -->
                    <a href="/gallery-details" class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                        <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                            <img src="https://loremflickr.com/600/400/forest?lock=21" 
                                 class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                            সুন্দরবনের গহীনে বন্যপ্রাণীদের জীবনযাপন
                        </h4>
                    </a>

                    <!-- আইটেম ৩ -->
                    <a href="/gallery-details" class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                        <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                            <img src="https://loremflickr.com/600/400/village?lock=22" 
                                 class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                            গ্রাম বাংলার ঐতিহ্যবাহী নৌকাবাইচ উৎসব
                        </h4>
                    </a>

                    <!-- আইটেম ৪ -->
                    <a href="/gallery-details" class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                        <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                            <img src="https://loremflickr.com/600/400/technology?lock=23" 
                                 class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                 onload="this.parentElement.classList.remove('img-placeholder')">
                        </div>
                        <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                            প্রযুক্তির নতুন বিস্ময়: রোবোটিক মেলা ২০২৬
                        </h4>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layout>
