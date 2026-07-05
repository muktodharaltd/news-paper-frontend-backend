@extends('admin.layout')

@section('title', 'Advertisement')
@section('header_title', 'Advertisement')

@section('content')
<div class="py-1 w-full mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4">
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
        @if($googleClientConfigured && $googleSlotCount === 0)
        <div class="mb-4 px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-200 border border-amber-200 dark:border-amber-800 text-sm">
            <strong>Google Client ID আছে</strong>, কিন্তু কোনো slot-এ Slot ID save হয়নি।
            নিচের <strong>Google Slot ID (সব slot)</strong> ফর্মে প্রতিটি জায়গার জন্য AdSense-এ তৈরি করা <strong>আলাদা Slot ID</strong> দিন।
        </div>
        @elseif(! $googleClientConfigured)
        <div class="mb-4 px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-200 border border-amber-200 dark:border-amber-800 text-sm">
            <strong>SEO & Meta</strong>-তে Google AdSense Client ID (<code class="font-mono">pub-2602475216171666</code>) save করুন।
        </div>
        @endif
        @if($googleClientConfigured && $duplicateGoogleSlotIds->isNotEmpty())
        <div class="mb-4 px-4 py-3 rounded-lg bg-rose-50 dark:bg-rose-900/20 text-rose-800 dark:text-rose-200 border border-rose-200 dark:border-rose-800 text-sm">
            <strong>সতর্ক:</strong> একই Google Slot ID একাধিক advertisement slot-এ আছে:
            @foreach($duplicateGoogleSlotIds as $dupId)
            <code class="font-mono mx-1">{{ $dupId }}</code>
            @endforeach
            — এক পেজে শুধু একটাই ad fill হবে। প্রতিটি slot-এ <strong>আলাদা ID</strong> দিন।
        </div>
        @endif

        @if($googleClientConfigured && $advertisements->isNotEmpty())
        <div class="mb-6 p-4 sm:p-5 rounded-xl border border-blue-200 dark:border-blue-800/60 bg-blue-50/50 dark:bg-blue-900/10">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Google Slot ID (সব slot)</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 max-w-3xl">
                        এক পেজে ৫–৭টা ad দেখাতে <strong>প্রতিটি row-তে আলাদা Slot ID</strong> দিন।
                        AdSense → Ads → By ad unit → প্রতিটি জায়গার জন্য নতুন unit তৈরি করে ID কপি করুন।
                        Slot ID save করলে Google Auto স্বয়ংক্রিয় চালু হবে।
                    </p>
                </div>
                <span class="text-xs font-mono text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40 px-2 py-1 rounded">
                    {{ $googleSlotCount }} / {{ $advertisements->where('slug', '!=', 'home_video')->count() }} configured
                </span>
            </div>
            <form action="{{ route('admin.advertisements.google-slots.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase text-slate-500 w-8">#</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase text-slate-500">Slug</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase text-slate-500">Name</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase text-slate-500 min-w-[180px]">Google Slot ID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($advertisements->where('slug', '!=', 'home_video') as $ad)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30">
                                <td class="py-2 px-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="py-2 px-3">
                                    <code class="text-[10px] font-mono text-slate-600 dark:text-slate-400">{{ $ad->slug }}</code>
                                </td>
                                <td class="py-2 px-3 text-slate-800 dark:text-slate-200 text-xs">{{ $ad->name }}</td>
                                <td class="py-2 px-3">
                                    <input type="text"
                                           name="google_slots[{{ $ad->id }}]"
                                           value="{{ old('google_slots.'.$ad->id, $ad->google_ad_slot ?? '') }}"
                                           placeholder="AdSense unit ID"
                                           class="w-full min-w-[140px] px-2 py-1.5 rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 font-mono text-xs text-slate-900 dark:text-white focus:ring-1 focus:ring-indigo-500 outline-none">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                        সব Google Slot ID Save
                    </button>
                </div>
            </form>
        </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6 sm:mb-8">
            <p class="text-sm text-slate-600 dark:text-slate-400 min-w-0">ফিক্সড অ্যাড স্লটগুলো এখানে তালিকাভুক্ত। নতুন অ্যাড যোগ করা যাবে না; শুধু প্রতিটি স্লটের ইমেজ/লিংক আপডেট করুন।</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border-x border-slate-200 dark:border-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr class="border-y border-slate-200 dark:border-slate-700 divide-x divide-slate-200 dark:divide-slate-700">
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider w-16 text-center">SL</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider w-24">Banner</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">Name</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">Location (slug)</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">URL</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">সময়সূচি</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">সোর্স</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider">Google Slot</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-black dark:text-slate-300 uppercase tracking-wider text-right w-40">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($advertisements as $ad)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-200 dark:border-slate-700 divide-x divide-slate-200 dark:divide-slate-700">
                        <td class="py-3 px-4 text-center">
                            <span class="text-sm font-medium text-slate-500">
                                {{ $loop->iteration }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @php $preview = $ad->adminListPreview(); @endphp
                            <div class="h-10 w-20 rounded-lg bg-slate-100 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                                @if($preview?->video_youtube_id)
                                <span class="text-[10px] font-mono text-slate-500">YT</span>
                                @elseif($preview?->video)
                                <span class="text-[10px] text-slate-500">MP4</span>
                                @elseif($preview?->image)
                                <img src="{{ storage_image_url($preview->image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm font-normal text-black dark:text-white font-medium">{{ $ad->name }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded text-[10px] font-mono">{{ $ad->slug }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($preview?->link)
                            <a href="{{ $preview->link }}" target="_blank" rel="noopener" class="text-xs text-indigo-500 hover:text-indigo-700 underline truncate max-w-[150px] inline-block">{{ $preview->link }}</a>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                            $s = $ad->starts_at;
                            $e = $ad->ends_at;
                            $active = $ad->isActiveForDisplay();
                            $slotWindowPast = $s && $e && $e->isPast();
                            $hasQueue = $ad->hasActiveQueueItems();
                            @endphp
                            @if($ad->hasRunningLocalAd())
                            @if($ad->is_auto && $s)
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Auto · চলছে</span>
                            @elseif(!$s || (!$e && ! $ad->is_auto))
                            <span class="text-xs font-medium text-amber-700 dark:text-amber-400">মেয়াদ নেই — ফ্রন্টে দেখাবে না</span>
                            @elseif($active && $slotWindowPast && $hasQueue)
                            <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">স্লট মেয়াদ শেষ · কিউ চলছে</span>
                            @elseif($active)
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">চলছে</span>
                            @elseif($e && $e->isPast() && ! $hasQueue)
                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">মেয়াদ শেষ</span>
                            @elseif($s && $s->isFuture())
                            <span class="text-xs font-medium text-amber-600 dark:text-amber-400">আসন্ন</span>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                            @elseif(($ad->google_ad_auto ?? false) && filled($ad->google_ad_slot))
                            <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Google Auto · fallback</span>
                            @elseif($ad->is_auto && $s)
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Auto · চলছে</span>
                            @elseif(!$s || (!$e && ! $ad->is_auto))
                            <span class="text-xs font-medium text-amber-700 dark:text-amber-400">মেয়াদ নেই — ফ্রন্টে দেখাবে না</span>
                            @elseif($active && $slotWindowPast && $hasQueue)
                            <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">স্লট মেয়াদ শেষ · কিউ চলছে</span>
                            @elseif($active)
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">চলছে</span>
                            @elseif($e && $e->isPast() && ! $hasQueue)
                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">মেয়াদ শেষ</span>
                            @elseif($s && $s->isFuture())
                            <span class="text-xs font-medium text-amber-600 dark:text-amber-400">আসন্ন</span>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                            @if($s || $e || $ad->is_auto)
                            <div class="text-[10px] text-slate-400 mt-0.5 font-mono leading-tight">
                                @if($s)<span>থেকে {{ $s->format('d M, H:i') }}</span>@endif
                                @if($ad->is_auto)
                                <span class="block">Auto</span>
                                @elseif($e)<span class="block">পর্যন্ত {{ $e->format('d M, H:i') }}</span>@endif
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($ad->hasRunningLocalAd())
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">Local</span>
                            @if(($ad->google_ad_auto ?? false) && filled($ad->google_ad_slot))
                            <span class="block text-[10px] text-blue-600 dark:text-blue-400 mt-0.5">+ Google fallback</span>
                            @endif
                            @elseif(($ad->google_ad_auto ?? false) && filled($ad->google_ad_slot))
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">Google Auto</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">Local</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if(filled($ad->google_ad_slot))
                            <code class="text-[10px] font-mono text-blue-700 dark:text-blue-300">{{ $ad->google_ad_slot }}</code>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.advertisements.edit', $ad->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 px-4 text-center text-slate-500 dark:text-slate-400 text-sm">কোনো অ্যাড স্লট নেই। সিডার চালান: <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">php artisan db:seed --class=AdvertisementSeeder</code></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection