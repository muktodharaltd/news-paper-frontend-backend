<x-layout>
    <x-slot:title>জাতীয় সংসদে গুরুত্বপূর্ণ বিল পাস - {{ site_name_bn() }}</x-slot>

        <div class="py-4 md:py-10 min-h-screen bg-white">
            <div class="container">
                @php
                \Carbon\Carbon::setLocale('bn');
                $categoryName = "জাতীয়";
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <a href="/national" class="text-slate-500 hover:text-primary transition-all">{{ $categoryName }}</a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-black font-bold">সংবাদ বিস্তারিত</span>
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
                            জাতীয় সংসদে গুরুত্বপূর্ণ বিল পাস, নতুন আইন কার্যকর হচ্ছে শীঘ্রই
                        </h1>

                        <div class="flex flex-col gap-1 pb-2 mb-2">
                            <span class="text-lg font-bold text-title leading-tight">ইত্তেফাক ডিজিটাল রিপোর্ট</span>
                            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 gap-4">
                                <span class="text-sm md:text-base text-desc">প্রকাশ : ০৬ মার্চ ২০২৬, ২১:৪৬</span>

                                <!-- সোশ্যাল শেয়ার আইকনসমূহ -->
                                <div class="flex items-center gap-3">
                                    <!-- Facebook -->
                                    <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#3b5998] hover:bg-slate-800 hover:text-white transition-all" title="Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                    </a>
                                    <!-- Messenger -->
                                    <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0084ff] hover:bg-slate-800 hover:text-white transition-all" title="Messenger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z" />
                                        </svg>
                                    </a>
                                    <!-- X -->
                                    <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-black hover:bg-slate-800 hover:text-white transition-all" title="X">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z" />
                                        </svg>
                                    </a>
                                    <!-- Picsart (Using a custom palette color/icon) -->
                                    <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#FF3C5F] hover:bg-slate-800 hover:text-white transition-all" title="Picsart">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13.442 2.558a8 8 0 1 0-10.884 10.884 8 8 0 1 0 10.884-10.884zM7.283 11.821c-1.442 0-2.611-1.169-2.611-2.611s1.169-2.611 2.611-2.611 2.611 1.169 2.611 2.611-1.169 2.611-2.611 2.611zm0-3.917c-.721 0-1.306.585-1.306 1.306s.585 1.306 1.306 1.306 1.306-.585 1.306-1.306-.585-1.306-1.306-1.306z" />
                                        </svg>
                                    </a>
                                    <!-- LinkedIn -->
                                    <a href="#" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-[#0077b5] hover:bg-slate-800 hover:text-white transition-all" title="LinkedIn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H3.362v7.225h1.581zm-1-8.306c.564 0 1.022-.458 1.022-1.022 0-.564-.458-1.022-1.022-1.022-.564 0-1.022.458-1.022 1.022 0 .564.458 1.022 1.022 1.022zm11.035 8.306V9.759c0-1.847-.988-2.706-2.301-2.706-1.059 0-1.532.584-1.797.994V6.169h-1.582c.021.445 0 7.225 0 7.225h1.582V9.759c0-.399.028-.799.145-1.087.32-.799 1.05-1.625 2.275-1.625 1.605 0 2.247 1.223 2.247 3.016v4.185h1.582z" />
                                        </svg>
                                    </a>
                                    <!-- Print -->
                                    <a href="javascript:window.print()" class="w-8 h-8  border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all" title="Print This Article">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- ফিচারড ইমেজ -->
                        <div class="w-full">
                            <div class="img-placeholder w-full aspect-[3/2] overflow-hidden shadow-md">
                                <img src="https://loremflickr.com/1200/800/parliament,building?lock=1"
                                    alt="জাতীয় সংসদ"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <p class="text-sm text-slate-500 mt-3 italic border-l-4 border-primary py-1 bg-slate-50">
                                জাতীয় সংসদ ভবন। ছবি: সংগৃহীত
                            </p>
                        </div>

                        <!-- নিউজ ডেসক্রিপশন -->
                        <div class="prose prose-lg max-w-none text-title text-xl font-medium space-y-6 pt-4 px-0 lg:px-[125px] text-justify leading-[1.8]">
                            <p>
                                আজ জাতীয় সংসদে একটি ঐতিহাসিক বিল পাস হয়েছে যা দেশের নাগরিকদের জীবনে বড় পরিবর্তন আনবে বলে আশা করা হচ্ছে। এই আইনের মাধ্যমে ডিজিটাল সেবার পরিধি আরও বিস্তৃত হবে। সংসদ অধিবেশনে কণ্ঠভোটে এই বিলটি পাস হয়।
                            </p>
                            <p>
                                বিলটি উত্থাপনের সময় সংশ্লিষ্ট মন্ত্রী বলেন, "স্মার্ট বাংলাদেশের যাত্রায় এই আইন একটি মাইলফলক হিসেবে কাজ করবে। এর ফলে সরকারি সেবা প্রান্তিক মানুষের দোরগোড়ায় পৌঁছে দেওয়া অনেক সহজ হবে।"
                            </p>
                            <p>
                                আইনের মূল বৈশিষ্ট্যগুলির মধ্যে রয়েছে তথ্য প্রযুক্তির সঠিক প্রয়োগ এবং অনলাইনে স্বচ্ছতা নিশ্চিত করা। এছাড়াও সাইবার নিরাপত্তা ব্যবস্থাকে আরও শক্তিশালী করার বিধান রাখা হয়েছে এই নতুন আইনে। অনেক আগেই এর খসড়া তৈরির কাজ শুরু হয়েছিল, যা আজ পূর্ণতা পেল।
                            </p>
                            <p>
                                বিরোধী দলীয় সদস্যরা বিলের কিছু ধারার ওপর সংশোধনী প্রস্তাব আনলেও অধিকাংশ সদস্যের সমর্থনে এটি মূল আকারে পাস করা হয়। বিলটি পাসের পর সংশ্লিষ্ট মহলে খুশির জোয়ার লক্ষ্য করা গেছে। আগামী সপ্তাহের মধ্যেই গেজেট আকারে প্রকাশিত হবে বলে আশা করা হচ্ছে।
                            </p>
                        </div>
                    </div>

                    <!-- দ্বিতীয় কলাম (৩ ভাগ) -->
                    <div class="flex flex-col gap-10 w-full">

                        <!-- এ সম্পর্কিত আরও পড়ুন (সাইডবার) -->
                        <div class="hidden lg:flex flex-col gap-6 pt-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                                <div class="w-1.5 h-6 bg-primary"></div>
                                <h3 class="text-xl font-bold serif text-title">এ সম্পর্কিত আরও পড়ুন</h3>
                            </div>

                            <!-- আইটেম ১ -->
                            <div class="group cursor-pointer flex flex-col gap-2">
                                <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                    <img src="https://loremflickr.com/600/400/law?lock=10"
                                        alt="Related News"
                                        class="w-full h-full object-cover"
                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                </div>
                                <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                    নতুন আইন বাস্তবায়নে জেলা প্রশাসকদের বিশেষ নির্দেশনা
                                </h4>
                            </div>

                            <!-- আইটেম ২ -->
                            <div class="group cursor-pointer flex flex-col gap-2">
                                <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                    <img src="https://loremflickr.com/600/400/office?lock=11"
                                        alt="Related News"
                                        class="w-full h-full object-cover"
                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                </div>
                                <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                    স্মার্ট বাংলাদেশ গড়তে ডিজিটাল সেবায় আসছে আমূল পরিবর্তন
                                </h4>
                            </div>
                        </div>

                    </div>

                </section>

                <!-- এ সম্পর্কিত আরও পড়ুন (একদম নিচে, ৪ কলামে) -->
                <div class="mt-12 md:mt-[100px] pt-8 md:pt-[60px] ">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2 h-8 bg-primary"></div>
                        <h3 class="text-xl md:text-3xl font-bold serif text-title">এ সম্পর্কিত আরও পড়ুন</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0 lg:hidden">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/law?lock=10"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                নতুন আইন বাস্তবায়নে জেলা প্রশাসকদের বিশেষ নির্দেশনা
                            </h4>
                        </div>

                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0 lg:hidden">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/office?lock=11"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                স্মার্ট বাংলাদেশ গড়তে ডিজিটাল সেবায় আসছে আমূল পরিবর্তন
                            </h4>
                        </div>

                        <!-- আইটেম ১ -->
                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/law?lock=10"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                নতুন আইন বাস্তবায়নে জেলা প্রশাসকদের বিশেষ নির্দেশনা ও সঠিক নিয়মাবলী
                            </h4>
                        </div>

                        <!-- আইটেম ২ -->
                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/office?lock=11"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                স্মার্ট বাংলাদেশ গড়তে ডিজিটাল সেবায় আসছে আমূল পরিবর্তন ও নতুন পরিকল্পনা
                            </h4>
                        </div>

                        <!-- আইটেম ৩ -->
                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/city?lock=12"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                রাজধানীর যাতায়াত ব্যবস্থায় নতুন দিগন্ত: চালু হচ্ছে আরও একটি ফ্লাইওভার
                            </h4>
                        </div>

                        <!-- আইটেম ৪ -->
                        <div class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3 pb-3 border-b border-gray-100 md:border-0 md:pb-0 last:border-0 last:pb-0">
                            <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                <img src="https://loremflickr.com/600/400/money?lock=13"
                                    alt="Related News"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                            <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                বৈদেশিক মুদ্রার রিজার্ভে বড় স্বস্তি, বাড়তে শুরু করেছে রেমিট্যান্স প্রবাহ
                            </h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</x-layout>