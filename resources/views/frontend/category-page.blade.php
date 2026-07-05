<x-layout>
    <x-slot:title>{{ $category->name }} - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen">
        <div class="container">
            @php \Carbon\Carbon::setLocale('bn'); @endphp

            <!-- Category Header -->
            <div class="mb-4 md:mb-10 text-left">
                <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-4">{{ $category->name }}</h1>

                <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                    <a href="/" class="text-slate-500 hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-black font-bold">{{ $category->name }}</span>
                </div>

                <div class="w-full border-b border-slate-300 relative mb-4 md:mb-8">
                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                </div>
            </div>

            <div class="px-0 lg:px-[200px]">

                @forelse($pages as $page)
                <div class="mb-8 pb-8 border-b border-slate-200 last:border-0">
                    <h2 class="text-2xl font-bold serif text-title mb-2">
                        <a href="{{ route('page.show', $page->slug) }}" class="hover:text-primary transition-colors">
                            {{ $page->title }}
                        </a>
                    </h2>
                    <div class="w-10 h-[2px] bg-primary mb-4"></div>

                    <div class="prose prose-slate max-w-none text-gray-700 leading-relaxed text-base font-semibold text-justify line-clamp-5">
                        {!! Str::limit(html_entity_decode(strip_tags($page->content ?? '')), 400) !!}
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center gap-1.5 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-xs font-semibold">{{ $page->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('page.show', $page->slug) }}" class="text-xs font-bold text-primary hover:text-primary/90 transition-colors">
                            বিস্তারিত পড়ুন →
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-desc text-center py-10">এই category-তে কোনো পেজ পাওয়া যায়নি।</p>
                @endforelse

                @if($pages->hasPages())
                <div class="mt-6">{{ $pages->links() }}</div>
                @endif

            </div>
        </div>
    </div>

</x-layout>
