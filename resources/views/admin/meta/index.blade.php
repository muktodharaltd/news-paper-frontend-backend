@extends('admin.layout')

@section('title', 'SEO & Meta Settings')
@section('header_title', 'SEO & Meta Settings')

@section('content')
<div class="py-1 w-full mx-auto">
    <div class="max-w-6xl mx-auto">
        @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 font-medium">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('admin.meta.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            @csrf
            
            <div class="p-4 sm:p-8 space-y-8 sm:space-y-12">
                {{-- 1. Site Info --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-8">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Site Information
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $meta->site_name ?? '') }}" placeholder="E.g. My Newspaper" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">সাইট নাম (বাংলা)</label>
                            <input type="text" name="site_name_bn" value="{{ old('site_name_bn', $meta->site_name_bn ?? '') }}" placeholder="যেমন: নিউজ টুয়েন্টি ফোর বিডি" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Title</label>
                            <input type="text" name="site_title" value="{{ old('site_title', $meta->site_title ?? '') }}" placeholder="SEO Title" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">ফ্রন্টএন্ড প্রাইমারি রঙ</label>
                            
                            <div class="flex flex-wrap items-center gap-3">
                                <input type="color" id="primary_color_picker" value="{{ old('primary_color', $meta->primary_color ?? '') ?: '#2563eb' }}" class="h-10 w-14 cursor-pointer rounded border border-slate-200 dark:border-slate-700 bg-white p-0.5" title="রঙ বাছাই" aria-label="Primary color picker">
                                <input type="text" name="primary_color" id="primary_color_hex" value="{{ old('primary_color', $meta->primary_color ?? '') }}" placeholder="#2563eb (ঐচ্ছিক)" maxlength="7" autocomplete="off" class="w-40 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-mono text-sm text-slate-900">
                            </div>
                            @error('primary_color')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Keywords</label>
                            <input type="text" name="site_keywords" value="{{ old('site_keywords', $meta->site_keywords ?? '') }}" placeholder="Keyword1, Keyword2, Keyword3" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Email</label>
                            <input type="email" name="site_email" value="{{ old('site_email', $meta->site_email ?? '') }}" placeholder="info@example.com" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Contact Number</label>
                            <input type="text" name="site_number" value="{{ old('site_number', $meta->site_number ?? '') }}" placeholder="+880123456789" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>

                        {{-- Logo & Icon --}}
                        <div class="grid grid-cols-2 gap-6 md:col-span-2">
                            <div>
                                <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Logo</label>
                                <div class="relative h-24 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col items-center justify-center gap-1.5 hover:bg-slate-50 transition-all cursor-pointer overflow-hidden font-normal text-slate-600 text-[10px] shadow-sm">
                                    <input type="file" name="site_logo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    @if(!empty($meta->site_logo))
                                    <img src="{{ storage_image_url($meta->site_logo) }}" alt="Logo" class="absolute inset-0 w-full h-full object-contain p-1">
                                    @endif
                                    <svg class="w-5 h-5 text-indigo-500 {{ !empty($meta->site_logo) ? 'opacity-50' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ !empty($meta->site_logo) ? 'Change Logo' : 'Upload Logo' }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Icon (Favicon)</label>
                                <div class="relative h-24 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col items-center justify-center gap-1.5 hover:bg-slate-50 transition-all cursor-pointer overflow-hidden font-normal text-slate-600 text-[10px] shadow-sm">
                                    <input type="file" name="site_icon" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    @if(!empty($meta->site_icon))
                                    <img src="{{ storage_image_url($meta->site_icon) }}" alt="Favicon" class="absolute inset-0 w-full h-full object-contain p-1">
                                    @endif
                                    <svg class="w-5 h-5 text-indigo-500 {{ !empty($meta->site_icon) ? 'opacity-50' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ !empty($meta->site_icon) ? 'Change Icon' : 'Upload Icon' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Website Description</label>
                            <textarea name="site_description" rows="3" placeholder="Brief website description..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">{{ old('site_description', $meta->site_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 2. Social Info --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-8">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            Social Information
                        </h3>
                    </div>

                    <div id="social-links-container" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Facebook Link</label>
                            <input type="text" name="facebook_link" value="{{ old('facebook_link', $meta->facebook_link ?? '') }}" placeholder="https://facebook.com/..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Twitter Link</label>
                            <input type="text" name="twitter_link" value="{{ old('twitter_link', $meta->twitter_link ?? '') }}" placeholder="https://twitter.com/..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Instagram Link</label>
                            <input type="text" name="instagram_link" value="{{ old('instagram_link', $meta->instagram_link ?? '') }}" placeholder="https://instagram.com/..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">YouTube Link</label>
                            <input type="text" name="youtube_link" value="{{ old('youtube_link', $meta->youtube_link ?? '') }}" placeholder="https://youtube.com/..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                    </div>
                </div>

                {{-- 3. Google Map --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-8">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Google Map Settings
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Map Link</label>
                            <input type="text" name="map_link" value="{{ old('map_link', $meta->map_link ?? '') }}" placeholder="https://google.com/maps/..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Map Description</label>
                            <input type="text" name="map_desc" value="{{ old('map_desc', $meta->map_desc ?? '') }}" placeholder="Location description..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                    </div>
                </div>

                {{-- 4. Address --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-8">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Office Address
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-sm font-normal text-slate-900 mb-2 ml-0.5">Address 1</label>
                        <div class="border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden bg-white shadow-sm">
                            <textarea id="editor" name="address_1" placeholder="Enter full address here...">{{ old('address_1', $meta->address_1 ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Google AdSense --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-6">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            Google AdSense
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">AdSense Client ID + Slot ID। <strong>গুরুত্বপূর্ণ:</strong> Google একই Slot ID পেজে একবারই ad দেখায় — তাই Strip, Box, Inline তিনটা আলাদা unit ID দিন (AdSense-এ ৩টি ad unit তৈরি করুন)।</p>
                    </div>
                    <div>
                        <label class="block text-sm font-normal text-slate-900 dark:text-white mb-2 ml-0.5">AdSense Client ID</label>
                        <input type="text" name="google_adsense_client" value="{{ old('google_adsense_client', $meta->google_adsense_client ?? '') }}" placeholder="pub-2602475216171666 বা ca-pub-2602475216171666" class="w-full max-w-lg px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-mono text-sm text-slate-900 dark:text-white">
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">AdSense Account Information-এর Publisher ID (pub-... বা ca-pub-...)</p>
                        
                        @error('google_adsense_client')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-normal text-slate-900 dark:text-white mb-2 ml-0.5">Strip / Banner Slot ID <span class="text-slate-400 font-normal">(header, below menu)</span></label>
                        <input type="text" name="google_adsense_default_slot" value="{{ old('google_adsense_default_slot', $meta->google_adsense_default_slot ?? '') }}" placeholder="2436228703" class="w-full max-w-lg px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-mono text-sm text-slate-900 dark:text-white">
                        @error('google_adsense_default_slot')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-normal text-slate-900 dark:text-white mb-2 ml-0.5">Box / Sidebar Slot ID <span class="text-slate-400 font-normal">(hero_right, details_right)</span></label>
                        <input type="text" name="google_adsense_box_slot" value="{{ old('google_adsense_box_slot', $meta->google_adsense_box_slot ?? '') }}" placeholder="2182248692" class="w-full max-w-lg px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-mono text-sm text-slate-900 dark:text-white">
                        @error('google_adsense_box_slot')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-normal text-slate-900 dark:text-white mb-2 ml-0.5">Inline Slot ID <span class="text-slate-400 font-normal">(বিবরণের ভিতর)</span></label>
                        <input type="text" name="google_adsense_inline_slot" value="{{ old('google_adsense_inline_slot', $meta->google_adsense_inline_slot ?? '') }}" placeholder="AdSense-এ নতুন In-article unit" class="w-full max-w-lg px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-mono text-sm text-slate-900 dark:text-white">
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">AdSense → Ads → Display ads → In-article / Rectangle unit। না দিলে Box Slot ID ব্যবহার হবে।</p>
                        @error('google_adsense_inline_slot')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 5. Editor & Publisher Information --}}
                <div class="p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 space-y-8">
                    <div class="pb-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Editor & Publisher Information
                        </h3>
                    </div>

                    @php
                        $editorLabel = old('editor_label', $meta->editor_label ?? 'সম্পাদক');
                        $publisherLabel = old('publisher_label', $meta->publisher_label ?? 'প্রকাশক');
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2 ml-0.5" data-role-label-wrap>
                                <span class="meta-role-label-text text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $editorLabel }}</span>
                                <input
                                    type="text"
                                    name="editor_label"
                                    value="{{ $editorLabel }}"
                                    data-default="সম্পাদক"
                                    class="meta-role-label-input hidden w-full max-w-[220px] px-2 py-1 rounded-md border border-indigo-200 dark:border-indigo-500/40 bg-white dark:bg-slate-900 text-sm font-normal text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500/30"
                                >
                                <button type="button" class="meta-role-label-edit group shrink-0 inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 hover:bg-indigo-600 hover:text-white hover:ring-indigo-600 transition-all" title="লেবেল সম্পাদনা" aria-label="সম্পাদক লেবেল সম্পাদনা">
                                    <svg class="w-3.5 h-3.5 pointer-events-none transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5H11a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="editor_name" value="{{ old('editor_name', $meta->editor_name ?? '') }}" placeholder="নাম লিখুন..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2 ml-0.5" data-role-label-wrap>
                                <span class="meta-role-label-text text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $publisherLabel }}</span>
                                <input
                                    type="text"
                                    name="publisher_label"
                                    value="{{ $publisherLabel }}"
                                    data-default="প্রকাশক"
                                    class="meta-role-label-input hidden w-full max-w-[220px] px-2 py-1 rounded-md border border-indigo-200 dark:border-indigo-500/40 bg-white dark:bg-slate-900 text-sm font-normal text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500/30"
                                >
                                <button type="button" class="meta-role-label-edit group shrink-0 inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 hover:bg-indigo-600 hover:text-white hover:ring-indigo-600 transition-all" title="লেবেল সম্পাদনা" aria-label="প্রকাশক লেবেল সম্পাদনা">
                                    <svg class="w-3.5 h-3.5 pointer-events-none transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5H11a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="publisher_name" value="{{ old('publisher_name', $meta->publisher_name ?? '') }}" placeholder="নাম লিখুন..." class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 focus:ring-1 focus:ring-indigo-500 transition-all outline-none font-normal text-slate-900 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Unified Save Button --}}
                <div class="flex items-center justify-end pt-12 border-t border-slate-100 dark:border-slate-800 mt-12">
                    <button type="submit" class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-indigo-500/25 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save All Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function initCKEditor() {
        adminLoadCkeditor(function () {
            adminCkeditorReplace('editor', { height: 200 });
        });
    }

    if (document.readyState === 'complete') {
        initCKEditor();
    } else {
        window.addEventListener('load', initCKEditor);
    }

    (function () {
        var picker = document.getElementById('primary_color_picker');
        var hex = document.getElementById('primary_color_hex');
        if (!picker || !hex) return;
        function validHex(v) {
            return /^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test((v || '').trim());
        }
        function syncPicker() {
            var v = hex.value.trim();
            if (validHex(v)) picker.value = v.length === 4 ? ('#' + v[1] + v[1] + v[2] + v[2] + v[3] + v[3]) : v;
        }
        picker.addEventListener('input', function () { hex.value = picker.value; });
        hex.addEventListener('input', syncPicker);
        syncPicker();
    })();

    (function () {
        function finishLabelEdit(input) {
            const wrap = input.closest('[data-role-label-wrap]');
            if (!wrap) return;

            const text = wrap.querySelector('.meta-role-label-text');
            const value = input.value.trim() || input.dataset.default || '';
            input.value = value;
            if (text) text.textContent = value;
            input.classList.add('hidden');
            if (text) text.classList.remove('hidden');
        }

        document.querySelectorAll('.meta-role-label-edit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const wrap = btn.closest('[data-role-label-wrap]');
                if (!wrap) return;

                const text = wrap.querySelector('.meta-role-label-text');
                const input = wrap.querySelector('.meta-role-label-input');
                if (!text || !input) return;

                text.classList.add('hidden');
                input.classList.remove('hidden');
                input.focus();
                input.select();
            });
        });

        document.querySelectorAll('.meta-role-label-input').forEach(function (input) {
            input.addEventListener('blur', function () {
                finishLabelEdit(input);
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    input.blur();
                }

                if (event.key === 'Escape') {
                    const wrap = input.closest('[data-role-label-wrap]');
                    const text = wrap ? wrap.querySelector('.meta-role-label-text') : null;
                    input.value = text ? text.textContent.trim() : input.dataset.default || '';
                    input.blur();
                }
            });
        });
    })();
</script>
@endpush
