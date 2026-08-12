@extends('admin.layout')

@section('title', 'Photocard Ads')
@section('header_title', 'Photocard Ads')

@section('content')
@php
    $previewCategoryId = (int) request('preview');
    $siteDomain = photocard_site_domain(front_home_url(), false) ?: '127.0.0.1';
@endphp
<div class="py-1 w-full mx-auto">
    <div class="mb-5">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            প্রতিটি ক্যাটাগরিতে আলাদা ফটোকার্ড অ্যাড ইমেজ দিন। সেই ক্যাটাগরির পোস্টে ফটোকার্ডের নিচে
            (তারিখ, বিস্তারিত কমেন্টে, ডোমেইনের নিচে) এই ইমেজ দেখাবে। সাব-ক্যাটাগরিতে ইমেজ না থাকলে প্যারেন্টের ইমেজ ব্যবহার হবে।
        </p>
        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
            সাজেস্টেড সাইজ: <span class="font-medium">১০৮০ × ২০০ px</span>
            (অন্য সাইজও চলবে — ইমেজের অনুপাত অনুযায়ী পুরো দেখাবে, ক্রপ হবে না)
        </p>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-4 px-4 py-2 rounded-lg bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr class="border-y border-slate-200 dark:border-slate-700">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100 w-16 text-center">SL</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">Category</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">Ad Image</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">Upload</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100 text-right w-40">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @php $row = 0; @endphp
                    @forelse($categories as $category)
                        @include('admin.advertisements.partials.photocard-ad-row', [
                            'category' => $category,
                            'indent' => false,
                            'row' => ++$row,
                            'previewCategoryId' => $previewCategoryId,
                        ])
                        @foreach($category->children as $child)
                            @include('admin.advertisements.partials.photocard-ad-row', [
                                'category' => $child,
                                'indent' => true,
                                'row' => ++$row,
                                'previewCategoryId' => $previewCategoryId,
                            ])
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-400 dark:text-slate-500 text-sm">কোনো পোস্ট ক্যাটাগরি পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="photocard-ad-preview-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-preview-close></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 pointer-events-auto overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">ফটোকার্ড প্রিভিউ</h3>
                    <p id="photocard-ad-preview-name" class="text-xs text-slate-500 mt-0.5"></p>
                </div>
                <button type="button" data-preview-close class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-4 bg-slate-100 dark:bg-slate-950 flex justify-center">
                <div class="w-[280px] shadow-lg overflow-hidden bg-[#2d0505] text-white" style="font-family: SolaimanLipi, sans-serif;">
                    <div class="w-full aspect-video bg-gradient-to-br from-slate-700 to-slate-900"></div>
                    <div class="px-3 pt-4 pb-2 text-center">
                        <p class="text-[11px] font-semibold leading-snug">নমুনা সংবাদের শিরোনাম এখানে দেখাবে</p>
                    </div>
                    <div class="px-2 py-2 flex items-end justify-between gap-1 bg-[rgba(96,28,28,0.96)]">
                        <span class="text-[8px] leading-none">১১ জুন ২০২৬</span>
                        <span class="text-[8px] leading-none">বিস্তারিত কমেন্টে</span>
                        <span class="text-[8px] leading-none">{{ $siteDomain }}</span>
                    </div>
                    <div class="w-full bg-white leading-none">
                        <img id="photocard-ad-preview-img" src="" alt="Photocard ad" class="block w-full h-auto object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('photocard-ad-preview-modal');
    var img = document.getElementById('photocard-ad-preview-img');
    var nameEl = document.getElementById('photocard-ad-preview-name');
    if (!modal || !img) return;

    function openPreview(src, name) {
        img.src = src;
        nameEl.textContent = name || '';
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closePreview() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.querySelectorAll('[data-photocard-preview]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openPreview(btn.getAttribute('data-src'), btn.getAttribute('data-name'));
        });
    });

    modal.querySelectorAll('[data-preview-close]').forEach(function (el) {
        el.addEventListener('click', closePreview);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closePreview();
        }
    });

    var autoBtn = document.querySelector('[data-photocard-preview][data-auto-open="1"]');
    if (autoBtn) {
        openPreview(autoBtn.getAttribute('data-src'), autoBtn.getAttribute('data-name'));
    }
})();
</script>
@endsection
