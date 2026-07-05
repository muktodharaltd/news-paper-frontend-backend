<x-layout>
    <x-slot:title>{{ $page->title }} - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen">
        <div class="container">

            <!-- Page Header: big category/page name + breadcrumb -->
            <div class="mb-4 md:mb-8 text-left">
                <h1 class="text-2xl md:text-3xl font-semibold serif text-title mb-3 md:mb-4">
                    {{ $page->category->name ?? $page->title }}
                </h1>

                <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-2 md:mb-3">
                    <a href="/" class="text-slate-500 hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="text-black font-bold">
                        {{ $page->category->name ?? $page->title }}
                    </span>
                </div>

                <div class="w-full border-b border-slate-300 relative mb-4 md:mb-6">
                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                </div>
            </div>

            <div class="px-0 lg:px-[300px]">

                {{-- Main page title above image --}}
                <div class="w-full mb-4 md:mb-5 text-left">
                    <h2 class="text-2xl md:text-3xl font-bold serif text-title mb-3">
                        {{ $page->title }}
                    </h2>
                </div>

                {{-- Optional featured image (full-width) --}}
                @if($page->image)
                    <div class="w-full mb-6 md:mb-8">
                        <div class="img-placeholder w-full aspect-[3/1.4] overflow-hidden shadow-md rounded md:rounded-none">
                            <img
                                src="{{ \Illuminate\Support\Str::startsWith($page->image, ['http://', 'https://']) ? $page->image : storage_image_url($page->image) }}"
                                alt="{{ $page->title }}"
                                class="w-full h-full object-cover"
                                onload="this.parentElement.classList.remove('img-placeholder')"
                            >
                        </div>
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-gray-700 leading-relaxed space-y-6 text-base md:text-lg font-semibold text-justify">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</x-layout>