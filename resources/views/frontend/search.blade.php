<x-layout>
    <x-slot:title>অনুসন্ধান: {{ $query ?: request('q') }} - {{ site_name_bn() }}</x-slot>

    <div class="py-4 md:py-10 min-h-screen">
        <div class="container">
            @php \Carbon\Carbon::setLocale('bn'); @endphp

            <!-- Header (category-style) -->
            <div class="mb-4 md:mb-10 text-left">
                <h1 class="text-4xl md:text-3xl font-semibold serif text-title mb-3">
                    {{ $query ?: 'অনুসন্ধান' }}
                </h1>

                <div class="flex items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                    <a href="/" class="text-slate-500 hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                    <span class="text-black font-bold uppercase tracking-widest">{{ $query ?: 'অনুসন্ধান' }}</span>
                </div>

                <div class="w-full border-b border-slate-100 relative mb-4 md:mb-8">
                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                </div>
            </div>

            <style>
                .national-grid {
                    display: grid;
                    gap: 0rem;
                    grid-template-columns: 1fr;
                }
                @media (min-width: 768px) {
                    .national-grid {
                        grid-template-columns: 1.7fr 7.4fr 2.9fr;
                    }
                }
            </style>

            <section class="national-grid">
                <div class="p-0 md:p-4"></div>

                <div class="bg-white p-0 md:p-4 flex flex-col gap-3 md:gap-5">
                    @forelse($items as $item)
                    <article class="flex flex-col md:flex-row gap-2 md:gap-4 last:pb-0">
                        <a href="{{ $item->url }}" class="w-full md:w-auto flex-shrink-0">
                            <div class="img-placeholder w-full md:w-[305px] h-[200px] md:h-[170px] overflow-hidden">
                                <img src="{{ $item->image }}"
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover"
                                    onload="this.parentElement.classList.remove('img-placeholder')">
                            </div>
                        </a>
                        <div class="flex flex-col justify-start gap-2 pt-1 flex-1">
                            <a href="{{ $item->url }}">
                                <h3 class="text-xl md:text-xl font-bold serif text-title leading-snug hover:text-primary transition-colors">
                                    {{ $item->title }}
                                </h3>
                            </a>
                            @if($item->snippet)
                            <p class="hidden md:block text-sm md:text-base font-normal text-desc leading-relaxed line-clamp-1">
                                {!! $item->snippet !!}
                            </p>
                            @endif
                            <div class="flex items-center gap-1.5 mt-auto text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <span class="text-xs font-medium text-gray-500">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @empty
                    <p class="text-desc text-center py-10">কোন ফলাফল পাওয়া যায়নি।</p>
                    @endforelse
                </div>

            </section>
        </div>
    </div>
</x-layout>
