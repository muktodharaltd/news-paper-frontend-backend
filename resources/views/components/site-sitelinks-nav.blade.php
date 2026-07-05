@php
    $sitelinks = site_search_sitelinks();
@endphp

@if($sitelinks !== [])
<nav aria-label="মূল বিভাগ" class="site-primary-nav border-b border-slate-200 bg-white">
    <div class="container">
        <ul class="flex flex-wrap items-center gap-x-4 gap-y-2 py-2 text-sm md:text-base">
            @foreach($sitelinks as $link)
            <li>
                <a href="{{ $link['url'] }}" class="font-medium text-slate-700 hover:text-primary transition-colors whitespace-nowrap">
                    {{ $link['name'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</nav>
@endif
