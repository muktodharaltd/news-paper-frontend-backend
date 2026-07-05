<x-layout>
    <x-slot:title>Unicode to Bijoy - Bangla text Converter - {{ site_name_bn() }}</x-slot>

    {{-- Bijoy font — শুধু এই পেজে; সাইটের global font পরিবর্তন হয় না --}}
    <link rel="preload" href="{{ asset('fonts/SutonnyMJ.ttf') }}" as="font" type="font/ttf" crossorigin>
    <style>
        @font-face {
            font-family: "SutonnyMJ";
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url("{{ asset('fonts/SutonnyMJ.ttf') }}") format("truetype");
        }

        #unicode-input {
            font-family: "SolaimanLipi", ui-sans-serif, sans-serif !important;
            font-weight: 400 !important;
            font-synthesis: none !important;
            -webkit-text-stroke: 0.3px currentColor;
            paint-order: stroke fill;
        }

        #bijoy-input.bijoy-text {
            font-family: "SutonnyMJ", "SutonnyMJ Regular", monospace !important;
            font-weight: 400 !important;
            font-synthesis: none !important;
            letter-spacing: normal;
            line-height: 1.6;
        }

        #unicode-input::placeholder,
        #bijoy-input::placeholder {
            color: #757575;
            opacity: 1;
        }
    </style>

    <div class="py-4 md:py-10 min-h-screen notranslate">
        <div class="container max-w-4xl">

            <div class="mb-6 md:mb-10 text-left">
                <h1 class="text-2xl md:text-3xl font-normal serif text-title mb-2">
                    Unicode to Bijoy - Bangla text Converter
                </h1>

                <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6 mt-4">
                    <a href="{{ front_home_url() }}" class="text-slate-500 hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-black font-bold">
                        <span class="i18n-bn">বাংলা কনভার্টার</span>
                        <span class="i18n-en">Bangla Converter</span>
                    </span>
                </div>

                <div class="w-full border-b border-slate-300 relative mb-4 md:mb-6">
                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-sm">
                    <textarea
                        id="unicode-input"
                        rows="9"
                        placeholder="ইউনিকোডের লেখা পেস্ট করুন"
                        data-placeholder-en="Paste Unicode text"
                        class="w-full px-4 py-3 text-2xl md:text-3xl serif text-title leading-tight bg-blue-50 border border-blue-400 rounded-sm focus:border-2 focus:border-blue-500 focus:ring-0 focus:outline-none outline-none resize-y min-h-[220px] h-[220px] overflow-y-auto notranslate placeholder:serif placeholder:text-2xl md:placeholder:text-3xl placeholder:leading-tight"></textarea>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3">
                    <button
                        type="button"
                        onclick="banglaUnicodeToBijoy()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-primary hover:opacity-90 transition-all rounded-sm">
                        Unicode to Bijoy
                    </button>
                    <button
                        type="button"
                        onclick="banglaBijoyToUnicode()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-primary hover:opacity-90 transition-all rounded-sm">
                        Bijoy to Unicode
                    </button>
                    <button
                        type="button"
                        onclick="banglaFixBroken()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-primary hover:opacity-90 transition-all rounded-sm">
                        Fix Bijoy Broken
                    </button>
                    <button
                        type="button"
                        onclick="banglaClearAll()"
                        class="px-4 py-2 text-sm font-semibold text-white bg-primary hover:opacity-90 transition-all rounded-sm">
                        Clear text
                    </button>
                </div>

                <div class="rounded-sm">
                    <textarea
                        id="bijoy-input"
                        rows="9"
                        placeholder="বিজয়ের লেখা পেস্ট করুন"
                        data-placeholder-en="Paste Bijoy text"
                        class="w-full px-4 py-3 text-xl md:text-2xl leading-relaxed bg-blue-50 border border-blue-400 rounded-sm focus:border-2 focus:border-blue-500 focus:ring-0 focus:outline-none outline-none resize-y min-h-[220px] h-[220px] overflow-y-auto bijoy-text notranslate placeholder:text-xl md:placeholder:text-2xl"></textarea>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/bangla-converter/all.js') }}?v=3" charset="utf-8"></script>
</x-layout>
