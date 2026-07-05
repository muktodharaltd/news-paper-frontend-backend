<footer class="bg-white text-black pt-2 md:pt-3 pb-10 md:pb-12">
    <div class="container overflow-visible px-4 md:px-0">
        <!-- Logo + Email Subscribe Row -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-2 pb-2 border-b border-gray-300">
            <!-- Logo (Left) -->
            <a href="{{ front_home_url() }}" class="shrink-0 text-left">
                @if(!empty(optional($siteMeta)->site_logo))
                <img src="{{ storage_image_url($siteMeta->site_logo) }}" alt="{{ optional($siteMeta)->site_name ?? 'Logo' }}" class="h-14 md:h-20 w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling && this.nextElementSibling.classList.remove('hidden');">
                <h2 class="text-4xl md:text-5xl font-black serif tracking-tighter uppercase underline decoration-primary underline-offset-8 decoration-4 hidden">
                    {{ site_name_bn() }}
                </h2>
                @else
                <h2 class="text-4xl md:text-5xl font-black serif tracking-tighter uppercase underline decoration-primary underline-offset-8 decoration-4">
                    {{ site_name_bn() }}
                </h2>
                @endif
            </a>

            <!-- Email Subscribe (Right, replacing app links) -->
            <div class="w-full md:w-auto md:max-w-sm flex-shrink-0">
                @if(session('subscribe_success'))
                <p class="text-sm text-green-600 font-medium mb-2">{{ session('subscribe_success') }}</p>
                @endif
                <form action="{{ route('frontend.subscribe') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="email" name="email" required placeholder="ইমেইল লিখুন" class="flex-1 min-w-[220px] px-4 py-2.5 border border-gray-300 bg-white text-black placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                    <button type="submit" class="shrink-0 px-5 py-2.5 bg-black text-white font-semibold text-sm hover:bg-slate-800 transition-colors">
                        সাবস্ক্রাইব
                    </button>
                </form>
            </div>
        </div>

        <!-- 12-Column Grid for precise control -->
        <div class="grid grid-cols-2 md:grid-cols-12 gap-8 pt-2 items-stretch">
            <!-- Column 1: Info and Copyright (Span 4) -->
            <div class="col-span-2 md:col-span-4 space-y-6 md:pr-4 py-3 md:py-4 md:border-r md:border-slate-300">
                <div class="text-base md:text-lg font-md space-y-1">
                    @foreach(site_editor_publisher_lines($siteMeta) as $line)
                    <p>{{ $line['label'] }}: {{ $line['name'] }}</p>
                    @endforeach
                </div>
                @if(!empty(optional($siteMeta)->address_1))
                <div class="text-sm md:text-base text-gray-900 leading-relaxed font-md prose prose-sm md:prose-base max-w-none">
                    {!! $siteMeta->address_1 !!}
                </div>
                @endif
                <p class="text-xs md:text-sm font-md text-gray-900 uppercase tracking-widest !mt-3 pt-1">
                    © প্রকাশক কর্তৃক সর্বস্বত্ব সংরক্ষিত
                </p>
            </div>

            <!-- Column 2: Category Links (Span 4) -->
            <div class="col-span-2 md:col-span-4 md:px-1 py-3 md:py-4 md:border-r md:border-slate-300">
                @if(isset($footerCol2) && $footerCol2->isNotEmpty())
                <div class="grid w-full grid-cols-2 gap-x-4 gap-y-1 text-base md:text-xl">
                    @foreach($footerCol2 as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="block w-full font-medium hover:text-primary transition-all">{{ $cat->name }}</a>
                    @endforeach
                </div>
                @else
                <div class="grid w-full grid-cols-2 gap-x-4 gap-y-1 text-base md:text-xl">
                    <a href="{{ route('gallery.index') }}" class="block w-full font-medium hover:text-primary transition-all">ছবি</a>
                    <a href="{{ route('videos.index') }}" class="block w-full font-medium hover:text-primary transition-all">ভিডিও</a>
                </div>
                @endif
            </div>

            <!-- Column 3: Custom Links (Span 2 — same as Column 4) -->
            <div class="md:col-span-2 md:px-4 py-3 md:py-4 md:border-r md:border-slate-300">
                @if(isset($footerCol3) && $footerCol3->isNotEmpty())
                <ul class="space-y-1 text-base md:text-xl">
                    @foreach($footerCol3 as $cat)
                    <li><a href="{{ route('category.show', $cat->slug) }}" class="font-medium hover:text-primary transition-all">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
                @else
                <ul class="space-y-1 text-base md:text-xl">
                    <li><a href="#" class="font-medium hover:text-primary transition-all">বিজ্ঞাপন</a></li>
                    <li><a href="#" class="font-medium hover:text-primary transition-all">যোগাযোগ</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="font-medium hover:text-primary transition-all">গোপনীয়তা নীতি</a></li>
                    <li><a href="{{ route('terms') }}" class="font-medium hover:text-primary transition-all">শর্তাবলী</a></li>
                </ul>
                @endif
            </div>

            <!-- Column 4: Social Links – backend (site meta) থেকে ডাইনামিক -->
            <div class="md:col-span-2 md:pl-4 py-3 md:py-4">
                <ul class="list-none p-0 m-0 text-base md:text-xl">
                    @if(!empty(optional($siteMeta)->facebook_link))
                    <li class="border-b border-dotted border-custom pb-1 mb-1 last:border-b-0 last:mb-0 last:pb-0"><a href="{{ $siteMeta->facebook_link }}" target="_blank" rel="noopener" class="block w-full font-medium hover:text-primary transition-all">Facebook</a></li>
                    @endif
                    @if(!empty(optional($siteMeta)->twitter_link))
                    <li class="border-b border-dotted border-custom pb-1 mb-1 last:border-b-0 last:mb-0 last:pb-0"><a href="{{ $siteMeta->twitter_link }}" target="_blank" rel="noopener" class="block w-full font-medium hover:text-primary transition-all">Twitter</a></li>
                    @endif
                    @if(!empty(optional($siteMeta)->instagram_link))
                    <li class="border-b border-dotted border-custom pb-1 mb-1 last:border-b-0 last:mb-0 last:pb-0"><a href="{{ $siteMeta->instagram_link }}" target="_blank" rel="noopener" class="block w-full font-medium hover:text-primary transition-all">Instagram</a></li>
                    @endif
                    @if(!empty(optional($siteMeta)->youtube_link))
                    <li class="border-b border-dotted border-custom pb-1 mb-1 last:border-b-0 last:mb-0 last:pb-0"><a href="{{ $siteMeta->youtube_link }}" target="_blank" rel="noopener" class="block w-full font-medium hover:text-primary transition-all">YouTube</a></li>
                    @endif
                    @if(!empty(optional($siteMeta)->extra_social_links) && is_array($siteMeta->extra_social_links))
                    @foreach($siteMeta->extra_social_links as $extraLink)
                    @if(!empty($extraLink))
                    <li class="border-b border-dotted border-custom pb-1 mb-1 last:border-b-0 last:mb-0 last:pb-0"><a href="{{ $extraLink }}" target="_blank" rel="noopener" class="block w-full font-medium hover:text-primary transition-all">লিংক</a></li>
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</footer>