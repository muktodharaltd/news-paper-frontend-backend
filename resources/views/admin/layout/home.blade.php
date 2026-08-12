@extends('admin.layout')

@section('title', 'Home Layout Settings')
@section('header_title', 'Home Layout')
@section('header_subtitle', 'Hero section এর layer গুলো')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <div class="lg:col-span-3">
                <div id="hero-layer-4" class="relative h-24 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span class="absolute left-3 top-1 text-[10px] text-slate-400">4th News</span>
                    <span class="hero-layer-label">
                        {{ optional(optional($sections->get('hero_layer_4'))->category)->name ?? '4th Layer' }}
                    </span>
                </div>
            </div>
            <div class="lg:col-span-6 space-y-3">
                <div id="hero-layer-1" class="relative h-16 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span class="absolute left-3 top-1 text-[10px] text-slate-400">Top News</span>
                    <span class="hero-layer-label">
                        {{ optional(optional($sections->get('hero_layer_1'))->category)->name ?? '1st Layer' }}
                    </span>
                </div>
                <div id="hero-layer-2" class="relative h-16 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span class="absolute left-3 top-1 text-[10px] text-slate-400">Second News</span>
                    <span class="hero-layer-label">
                        {{ optional(optional($sections->get('hero_layer_2'))->category)->name ?? '2nd Layer' }}
                    </span>
                </div>
                <div id="hero-layer-3" class="relative h-16 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span class="absolute left-3 top-1 text-[10px] text-slate-400">Third News</span>
                    <span class="hero-layer-label">
                        {{ optional(optional($sections->get('hero_layer_3'))->category)->name ?? '3rd Layer' }}
                    </span>
                </div>
            </div>
            <div class="lg:col-span-3 flex justify-center items-center">
                <div id="section-mini" class="relative h-24 w-full lg:w-3/4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                    <span class="absolute left-3 top-1 text-[10px] text-slate-400">Mini (Post)</span>
                    <span class="section-label">
                        {{ optional(optional($sections->get('section-mini'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                    </span>
                    <button
                        type="button"
                        class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                        data-section-id="section-mini">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3">
            <div id="section-politics" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 1 (Post)</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-politics'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-politics">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-national" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 2 (Post)</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-national'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-national">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-capital" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 3 (Post)</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-capital'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-capital">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-sports" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 4</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-sports'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-sports">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-countrywide" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 5</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-countrywide'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-countrywide">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-world" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 6</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-world'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-world">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-entertainment" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 7</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-entertainment'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-entertainment">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div id="section-lifestyle" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 8</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-lifestyle'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-lifestyle">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>

            <div id="section-tech" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 9</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-tech'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-tech">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>

            <div id="section-different-eye" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 10</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-different-eye'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-different-eye">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div id="section-generation" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 11</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-generation'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-generation">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>

            <div id="section-campus" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 12</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-campus'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-campus">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>

            <div id="section-job" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 13</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-job'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-job">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div id="section-triple-col-1" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 14</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-1'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-triple-col-2" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 15</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-2'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-triple-col-3" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 16</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-3'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            <div id="section-triple-col-4" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 17</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-4'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-4">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-triple-col-5" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 18</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-5'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-triple-col-6" class="relative h-20 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="post">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">Section 19</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-triple-col-6'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-triple-col-6">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3">

            <div id="section-video" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="video">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">ভিডিও (Video only)</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-video'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-video">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
            <div id="section-gallery" class="relative h-14 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center px-3 text-xs font-semibold text-slate-600 dark:text-slate-300" data-allowed-type="gallery">
                <span class="absolute left-3 top-1 text-[10px] text-slate-400">ছবি (Gallery only)</span>
                <span class="section-label">
                    {{ optional(optional($sections->get('section-gallery'))->category)->name ?? 'ক্যাটাগরি যোগ করুন' }}
                </span>
                <button
                    type="button"
                    class="section-config-edit mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                    data-section-id="section-gallery">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487l3.651 3.651M4.5 19.5l4.223-.469a2 2 0 001.15-.566l9.488-9.488a1.5 1.5 0 000-2.121L16.4 3.05a1.5 1.5 0 00-2.121 0L4.79 12.54a2 2 0 00-.566 1.15L3.75 17.914A1 1 0 004.5 19.5z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@php
    $initialSectionState = isset($sections)
        ? $sections->mapWithKeys(fn($s) => [$s->key => $s->category_id])
        : [];
@endphp
<div id="initial-section-state" class="hidden" data-initial='{{ json_encode($initialSectionState) }}'></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const initialSectionState = (() => {
            const el = document.getElementById('initial-section-state');
            if (!el) return {};
            const raw = el.getAttribute('data-initial') || '{}';
            try {
                return JSON.parse(raw);
            } catch (e) {
                return {};
            }
        })();
        const layerOptions = [{
                value: '1st_layer',
                label: '1st Layer'
            },
            {
                value: '2nd_layer',
                label: '2nd Layer'
            },
            {
                value: '3rd_layer',
                label: '3rd Layer'
            },
            {
                value: '4th_layer',
                label: '4th Layer'
            },
        ];

        const boxes = ['hero-layer-1', 'hero-layer-2', 'hero-layer-3', 'hero-layer-4'];
        const state = {};

        // Initialize state from DOM
        boxes.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const labelEl = el.querySelector('.hero-layer-label');
            if (!labelEl) return;
            const text = labelEl.textContent.trim();
            const found = layerOptions.find(o => o.label === text);
            state[id] = found ? found.value : null;
        });

        let activeBoxId = null;

        // Create modal once
        const modal = document.createElement('div');
        modal.id = 'hero-layer-modal';
        modal.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur';
        modal.innerHTML = `
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 w-full max-w-md p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Hero Layer নির্বাচন করুন</h2>
                <button type="button" id="hero-layer-modal-close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="hero-layer-modal-options" class="space-y-2 text-xs text-slate-700 dark:text-slate-200"></div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" id="hero-layer-modal-cancel" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Cancel
                </button>
                <button type="button" id="hero-layer-modal-save" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700">
                    Save
                </button>
            </div>
        </div>
    `;
        document.body.appendChild(modal);

        const optionsContainer = modal.querySelector('#hero-layer-modal-options');

        function renderOptions(selectedValue) {
            optionsContainer.innerHTML = '';
            layerOptions.forEach(opt => {
                const wrapper = document.createElement('label');
                wrapper.className = 'flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer';
                wrapper.innerHTML = `
                <input type="radio" name="hero-layer-choice" value="${opt.value}" class="w-3.5 h-3.5" ${opt.value === selectedValue ? 'checked' : ''} />
                <span>${opt.label}</span>
            `;
                optionsContainer.appendChild(wrapper);
            });
        }

        function openModal(boxId) {
            activeBoxId = boxId;
            const current = state[boxId] || '1st_layer';
            renderOptions(current);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            activeBoxId = null;
        }

        // Attach edit button listeners
        document.querySelectorAll('.hero-layer-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-layer-id');
                if (!id) return;
                openModal(id);
            });
        });

        modal.querySelector('#hero-layer-modal-close').addEventListener('click', closeModal);
        modal.querySelector('#hero-layer-modal-cancel').addEventListener('click', closeModal);

        modal.querySelector('#hero-layer-modal-save').addEventListener('click', () => {
            if (!activeBoxId) return;
            const selectedInput = modal.querySelector('input[name="hero-layer-choice"]:checked');
            if (!selectedInput) return;

            const newValue = selectedInput.value;
            const oldValue = state[activeBoxId];

            // If selecting one of the fixed 4 layers, ensure uniqueness by swapping
            const isLayerValue = ['1st_layer', '2nd_layer', '3rd_layer', '4th_layer'].includes(newValue);
            if (isLayerValue) {
                for (const otherId of boxes) {
                    if (otherId === activeBoxId) continue;
                    if (state[otherId] === newValue) {
                        // swap labels
                        state[otherId] = oldValue;
                        const otherEl = document.getElementById(otherId);
                        const otherLabel = otherEl ? otherEl.querySelector('.hero-layer-label') : null;
                        const oldOpt = layerOptions.find(o => o.value === oldValue);
                        if (otherLabel && oldOpt) {
                            otherLabel.textContent = oldOpt.label;
                        }
                        break;
                    }
                }
            }

            state[activeBoxId] = newValue;
            const activeEl = document.getElementById(activeBoxId);
            const activeLabel = activeEl ? activeEl.querySelector('.hero-layer-label') : null;
            const selectedOpt = layerOptions.find(o => o.value === newValue);
            if (activeLabel && selectedOpt) {
                activeLabel.textContent = selectedOpt.label;
            }

            closeModal();
        });

        // ===== Section config (category/post/gallery/video) =====
        const sectionModal = document.createElement('div');
        sectionModal.id = 'section-config-modal';
        sectionModal.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur';
        sectionModal.innerHTML = `
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 w-full max-w-md p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100" id="section-modal-title">Section এর জন্য category নির্বাচন করুন</h2>
                <button type="button" id="section-config-close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="text-xs text-slate-700 dark:text-slate-200 space-y-2">
                <div class="flex px-2 border-b border-slate-200 dark:border-slate-700 items-center justify-between text-[11px] font-semibold text-slate-500 uppercase tracking-wide mb-1">
                    <span>Category</span>
                    <span class="text-[10px] font-medium text-slate-400 normal-case">Type</span>
                </div>
                <div class="space-y-1 max-h-64 overflow-y-auto pr-1 custom-scrollbar" id="section-category-list">
                    @php
                        $postCategories    = $categories->where('type', 'post');
                        $galleryCategories = $categories->where('type', 'gallery');
                        $videoCategories   = $categories->where('type', 'video');
                        $hasAny            = $postCategories->isNotEmpty() || $galleryCategories->isNotEmpty() || $videoCategories->isNotEmpty();
                        $usedCategoryIds   = isset($sections) ? $sections->pluck('category_id')->filter()->values()->all() : [];
                    @endphp

                    @if($hasAny)
                        @foreach($postCategories as $category)
                            @php $alreadyInLayout = in_array($category->id, $usedCategoryIds); @endphp
                            <label class="section-category-option flex items-center gap-2 py-0.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer {{ $alreadyInLayout ? 'text-slate-400 dark:text-slate-500' : '' }}" data-category-type="post">
                                <input type="radio" name="section_category" class="w-3.5 h-3.5" value="{{ $category->id }}">
                                <span class="flex-1">{{ $category->name }}</span>
                                @if($alreadyInLayout)
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">ইতিমধ্যে যুক্ত</span>
                                @endif
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 uppercase">post</span>
                            </label>
                        @endforeach
                        @foreach($galleryCategories as $category)
                            @php $alreadyInLayout = in_array($category->id, $usedCategoryIds); @endphp
                            <label class="section-category-option flex items-center gap-2 py-0.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer {{ $alreadyInLayout ? 'text-slate-400 dark:text-slate-500' : '' }}" data-category-type="gallery">
                                <input type="radio" name="section_category" class="w-3.5 h-3.5" value="{{ $category->id }}">
                                <span class="flex-1">{{ $category->name }}</span>
                                @if($alreadyInLayout)
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">ইতিমধ্যে যুক্ত</span>
                                @endif
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 uppercase">gallery</span>
                            </label>
                        @endforeach
                        @foreach($videoCategories as $category)
                            @php $alreadyInLayout = in_array($category->id, $usedCategoryIds); @endphp
                            <label class="section-category-option flex items-center gap-2 py-0.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer {{ $alreadyInLayout ? 'text-slate-400 dark:text-slate-500' : '' }}" data-category-type="video">
                                <input type="radio" name="section_category" class="w-3.5 h-3.5" value="{{ $category->id }}">
                                <span class="flex-1">{{ $category->name }}</span>
                                @if($alreadyInLayout)
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">ইতিমধ্যে যুক্ত</span>
                                @endif
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 uppercase">video</span>
                            </label>
                        @endforeach
                        <p class="section-category-empty hidden text-[11px] text-slate-400 pt-2" id="section-category-empty-msg">এই সেকশনের জন্য উপযুক্ত কোনো category নেই।</p>
                    @else
                        <p class="text-[11px] text-slate-400">কোনো category পাওয়া যায়নি।</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" id="section-config-cancel" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Cancel
                </button>
                <button type="button" id="section-config-save" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700">
                    Save
                </button>
            </div>
        </div>
        `;
        document.body.appendChild(sectionModal);

        // Track which section is being edited + per-section category mapping
        const sectionState = {
            ...initialSectionState
        };
        const sectionDefaults = {};
        let activeSectionId = null;

        document.querySelectorAll('[id^="section-"]').forEach(box => {
            const id = box.id;
            const label = box.querySelector('.section-label');
            if (!label) return;
            sectionDefaults[id] = label.textContent.trim();
            if (typeof sectionState[id] === 'undefined') {
                sectionState[id] = null;
            }
        });

        const sectionTypeLabels = { post: 'Post', video: 'ভিডিও', gallery: 'ছবি (Gallery)' };

        function openSectionModal(sectionId) {
            activeSectionId = sectionId;
            const sectionBox = document.getElementById(sectionId);
            const allowedType = sectionBox ? (sectionBox.getAttribute('data-allowed-type') || 'post') : 'post';

            // Show only categories matching this section's type
            const options = sectionModal.querySelectorAll('.section-category-option');
            let visibleCount = 0;
            options.forEach(label => {
                const type = label.getAttribute('data-category-type');
                const match = type === allowedType;
                label.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            const emptyMsg = sectionModal.querySelector('#section-category-empty-msg');
            if (emptyMsg) {
                emptyMsg.classList.toggle('hidden', visibleCount > 0);
            }

            const titleEl = sectionModal.querySelector('#section-modal-title');
            if (titleEl) {
                titleEl.textContent = sectionTypeLabels[allowedType] 
                    ? sectionTypeLabels[allowedType] + ' type এর category নির্বাচন করুন' 
                    : 'Section এর জন্য category নির্বাচন করুন';
            }

            // Pre-select currently chosen category (only if it matches allowed type)
            const currentCategoryId = sectionState[sectionId];
            const radios = sectionModal.querySelectorAll('input[name="section_category"]');
            radios.forEach(r => {
                const label = r.closest('.section-category-option');
                const typeOk = label && label.getAttribute('data-category-type') === allowedType;
                r.checked = typeOk && currentCategoryId && r.value === String(currentCategoryId);
            });

            sectionModal.classList.remove('hidden');
            sectionModal.classList.add('flex');
        }

        function closeSectionModal() {
            sectionModal.classList.add('hidden');
            sectionModal.classList.remove('flex');
            activeSectionId = null;
        }

        document.querySelectorAll('.section-config-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const sectionId = btn.getAttribute('data-section-id');
                if (!sectionId) return;
                openSectionModal(sectionId);
            });
        });

        sectionModal.querySelector('#section-config-close').addEventListener('click', closeSectionModal);
        sectionModal.querySelector('#section-config-cancel').addEventListener('click', closeSectionModal);

        sectionModal.querySelector('#section-config-save').addEventListener('click', () => {
            if (!activeSectionId) return;

            const selectedRadio = sectionModal.querySelector('input[name="section_category"]:checked');
            if (!selectedRadio) return;

            const newCategoryId = selectedRadio.value;
            const oldCategoryId = sectionState[activeSectionId];

            const emptySectionLabel = 'ক্যাটাগরি যোগ করুন';

            // Ensure a category can be assigned to only ONE section at a time — আগের জায়গা খালি করে "ক্যাটাগরি যোগ করুন" দেখাবে
            Object.keys(sectionState).forEach(sectionId => {
                if (sectionId === activeSectionId) return;
                if (sectionState[sectionId] === newCategoryId) {
                    sectionState[sectionId] = null;
                    const box = document.getElementById(sectionId);
                    if (!box) return;
                    const label = box.querySelector('.section-label');
                    if (label) {
                        label.textContent = emptySectionLabel;
                    }
                }
            });

            // Find selected category name text from the list
            let selectedCategoryName = '';
            const radios = sectionModal.querySelectorAll('input[name="section_category"]');
            radios.forEach(radio => {
                if (radio.value === newCategoryId) {
                    const labelEl = radio.closest('label');
                    if (!labelEl) return;
                    const nameSpan = labelEl.querySelector('span.flex-1');
                    if (nameSpan) {
                        selectedCategoryName = nameSpan.textContent.trim();
                    }
                }
            });

            // Update active section mapping + label (frontend)
            sectionState[activeSectionId] = newCategoryId;
            const activeBox = document.getElementById(activeSectionId);
            if (activeBox) {
                const label = activeBox.querySelector('.section-label');
                if (label && selectedCategoryName) {
                    label.textContent = selectedCategoryName;
                }
            }

            // Persist to backend
            fetch("{{ route('admin.layout.home.save') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    section_id: activeSectionId,
                    category_id: newCategoryId,
                }),
            }).finally(() => {
                closeSectionModal();
            });
        });
    });
</script>
@endpush