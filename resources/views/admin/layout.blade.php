<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-font-preload />
    <title>@yield('title', 'Admin Panel')</title>
    @if(!empty(optional($siteMeta)->site_icon))
    <link rel="icon" href="{{ storage_image_url($siteMeta->site_icon) }}" type="image/png">
    @endif
    <x-admin.theme-init />
    @vite(['resources/css/app.css', 'resources/js/admin-theme.js'])
    <style>
        :root { --site-name: "{{ site_name() }}"; }
        /* মোবাইলে সাইডবার লুকানো; মেনু খুললে .sidebar-open দিয়ে দেখানো। টেইলউইন্ডের ওপর নির্ভর না করে নিশ্চিত কাজের জন্য। */
        @media (max-width: 767px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }

            #admin-sidebar.sidebar-open {
                transform: translateX(0);
            }

            #admin-sidebar-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
        }

        @media (min-width: 768px) {
            #admin-sidebar {
                transform: translateX(0);
            }

            #admin-sidebar-toggle {
                display: none !important;
            }

            #admin-sidebar-backdrop {
                display: none !important;
            }
        }

        /* সাইডবার ও স্ক্রল এলাকার scrollbar — লাইট/ডার্ক মোড */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .dark .custom-scrollbar {
            scrollbar-color: #475569 transparent;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
    <script>
        window.toggleSubmenu = function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const arrow = document.getElementById(id + '-arrow');
            const isOpen = el.classList.contains('grid-rows-[1fr]');

            // Close all other open submenus
            if (!isOpen) {
                document.querySelectorAll('[id$="-menu"]').forEach(menu => {
                    if (menu.id !== id) {
                        menu.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
                        const otherArrow = document.getElementById(menu.id + '-arrow');
                        if (otherArrow) otherArrow.classList.remove('rotate-180');
                    }
                });
            }

            // Toggle current submenu
            if (isOpen) {
                el.classList.replace('grid-rows-[1fr]', 'grid-rows-[0fr]');
                if (arrow) arrow.classList.remove('rotate-180');
            } else {
                el.classList.replace('grid-rows-[0fr]', 'grid-rows-[1fr]');
                if (arrow) arrow.classList.add('rotate-180');
            }
        };

        // Sync submenu arrows on load (e.g. when Users menu is open on role-permissions page)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id$="-menu"]').forEach(menu => {
                const arrow = document.getElementById(menu.id + '-arrow');
                if (arrow && menu.classList.contains('grid-rows-[1fr]')) {
                    arrow.classList.add('rotate-180');
                }
            });
            // Close mobile sidebar when a sidebar link is clicked (navigation)
            var sidebar = document.getElementById('admin-sidebar');
            if (sidebar) {
                sidebar.addEventListener('click', function(e) {
                    if (window.matchMedia('(max-width: 767px)').matches && e.target.closest('a[href]')) {
                        window.closeAdminSidebar();
                    }
                });
            }
        });

        // Heartbeat to keep session alive while working (disabled)
        // setInterval(function() {
        //     fetch('{{ route('admin.heartbeat') }}')
        //         .then(response => response.json())
        //         .catch(error => console.error('Heartbeat failed:', error));
        // }, 5 * 60 * 1000); // Ping every 5 minutes

        // Mobile sidebar: মোবাইলে লুকানো, মেনু আইকনে ক্লিক করলে খুলবে; বন্ধ করলে আবার লুকাবে
        window.toggleAdminSidebar = function() {
            var sidebar = document.getElementById('admin-sidebar');
            var backdrop = document.getElementById('admin-sidebar-backdrop');
            var toggle = document.getElementById('admin-sidebar-toggle');
            if (!sidebar || !backdrop) return;
            var isOpen = sidebar.classList.contains('sidebar-open');
            if (isOpen) {
                sidebar.classList.remove('sidebar-open');
                sidebar.setAttribute('data-sidebar-open', 'false');
                sidebar.setAttribute('aria-hidden', 'true');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
                document.body.classList.remove('overflow-hidden');
            } else {
                sidebar.classList.add('sidebar-open');
                sidebar.setAttribute('data-sidebar-open', 'true');
                sidebar.setAttribute('aria-hidden', 'false');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'true');
                }
                document.body.classList.add('overflow-hidden');
            }
        };
        window.closeAdminSidebar = function() {
            var sidebar = document.getElementById('admin-sidebar');
            var backdrop = document.getElementById('admin-sidebar-backdrop');
            var toggle = document.getElementById('admin-sidebar-toggle');
            if (!sidebar || !backdrop) return;
            sidebar.classList.remove('sidebar-open');
            sidebar.setAttribute('data-sidebar-open', 'false');
            sidebar.setAttribute('aria-hidden', 'true');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100', 'pointer-events-auto');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
            document.body.classList.remove('overflow-hidden');
        };
    </script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="flex min-h-screen">
        {{-- Sidebar: ডেস্কটপে সবসময় দৃশ্যমান। মোবাইলে লুকানো, হেডারের মেনু (☰) আইকনে ক্লিক করলে .sidebar-open দিয়ে খুলে (উপরের style ব্লক দিয়ে নিয়ন্ত্রণ) --}}
        <aside id="admin-sidebar" aria-hidden="true" data-sidebar-open="false"
            class="admin-sidebar-drawer w-64 bg-white border-r border-slate-200 dark:bg-slate-900/90 dark:border-slate-800/80 backdrop-blur fixed top-0 left-0 bottom-0 z-50 h-screen flex flex-col transition-transform duration-300 ease-out">
            <div class="h-20 flex items-center px-3 md:px-6 border-b border-slate-200 dark:border-slate-800/50">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    @if(!empty(optional($siteMeta)->site_logo))
                    <img src="{{ storage_image_url($siteMeta->site_logo) }}"
                        alt="{{ optional($siteMeta)->site_name ?? 'Logo' }}"
                        class="h-10 w-auto object-contain"
                        onerror="this.onerror=null;this.style.display='none';">
                    @else
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200 dark:shadow-none">
                        <span class="text-sm font-black italic">DN</span>
                    </div>
                    @endif
                </a>
            </div>

            <nav class="px-3 py-4 md:px-4 md:py-4 space-y-1 text-sm overflow-y-auto flex-1 custom-scrollbar">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                {{-- Smooth Dropdown Posts --}}
                @if(auth()->user()->canFeature('posts.view') || auth()->user()->canFeature('posts.manage'))
                @php $postsMenuOpen = request()->routeIs('admin.posts.*'); @endphp
                <div class="relative">
                    <button
                        type="button"
                        onclick="toggleSubmenu('posts-menu')"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl transition-all {{ $postsMenuOpen ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }} group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $postsMenuOpen ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                            <span class="font-medium">Posts</span>
                        </div>
                        <svg id="posts-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 {{ $postsMenuOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="posts-menu" class="{{ $postsMenuOpen ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.posts.create') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">New Post</span>
                                </a>
                                <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All Post</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Smooth Dropdown Category --}}
                @if(auth()->user()->canFeature('categories.manage'))
                @php $categoriesMenuOpen = request()->routeIs('admin.categories.*') || request()->routeIs('admin.sub-categories.*'); @endphp
                <div class="relative">
                    <button
                        type="button"
                        onclick="toggleSubmenu('categories-menu')"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl transition-all {{ $categoriesMenuOpen ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }} group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $categoriesMenuOpen ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.125 1.125 0 001.591 0l4.454-4.454a1.125 1.125 0 000-1.591L9.706 4.318A2.25 2.25 0 008.25 3h1.318z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6a1 1 0 100 2 1 1 0 000-2z"></path>
                            </svg>
                            <span class="font-medium">Categories</span>
                        </div>
                        <svg id="categories-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 {{ $categoriesMenuOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="categories-menu" class="{{ $categoriesMenuOpen ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Category</span>
                                </a>
                                <a href="{{ route('admin.sub-categories.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Sub Category</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Topics --}}
                @if(auth()->user()->canFeature('categories.manage'))
                <a
                    href="{{ route('admin.topics.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.topics.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.topics.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">Topics</span>
                </a>
                @endif



                @if(auth()->user()->canFeature('pages.manage'))
                @php $pagesMenuOpen = request()->routeIs('admin.pages.*'); @endphp
                <div class="mb-1">
                    <button
                        type="button"
                        onclick="toggleSubmenu('pages-menu')"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl transition-all {{ $pagesMenuOpen ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }} group">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $pagesMenuOpen ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                            <span class="font-medium">Pages</span>
                        </div>
                        <svg id="pages-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 {{ $pagesMenuOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="pages-menu" class="{{ $pagesMenuOpen ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.pages.create') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">New Page</span>
                                </a>
                                <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All Pages</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                {{-- Gallery --}}
                @if(auth()->user()->canFeature('galleries.manage'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('gallery-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.galleries.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.galleries.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                            </svg>
                            <span class="font-medium">Gallery</span>
                        </div>
                        <svg id="gallery-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="gallery-menu" class="{{ request()->routeIs('admin.galleries.*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.galleries.create') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.galleries.create') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Add Img</span>
                                </a>
                                <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.galleries.index') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All Img</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Videos --}}
                @if(auth()->user()->canFeature('videos.manage'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('video-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.videos.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.videos.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                            </svg>
                            <span class="font-medium">Video</span>
                        </div>
                        <svg id="video-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="video-menu" class="{{ request()->routeIs('admin.videos.*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.videos.create') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.videos.create') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Add Video</span>
                                </a>
                                <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.videos.index') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All Video</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->canFeature('role_permissions.manage'))
                <a href="{{ route('admin.role-permissions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.role-permissions.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.role-permissions.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z"></path>
                    </svg>
                    <span class="font-medium">Manage Roles</span>
                </a>
                @endif


                {{-- Statistics --}}
                @if(auth()->user()->canFeature('statistics.view'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('statistics-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->is('admin/statistics*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->is('admin/statistics*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
                            </svg>
                            <span class="font-medium">Statistics</span>
                        </div>
                        <svg id="statistics-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="statistics-menu" class="{{ request()->is('admin/statistics*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.statistics.visitors') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.statistics.visitors') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Visitors</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Reporters --}}
                @if(auth()->user()->canFeature('reporters.manage'))
                <a
                    href="{{ route('admin.reporters.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reporters.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.reporters.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 12.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                    </svg>
                    <span class="font-medium">Reporters</span>
                </a>
                @endif

                {{-- Advertisement --}}
                @if(auth()->user()->canFeature('advertisements.manage'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('advertisement-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.advertisements.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.advertisements.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.213c-.079-.335.124-.658.468-.741a4.501 4.501/0 01.445-.07m6.751-4.513c-.058-1.089-.138-2.16-.24-3.217h.746a2.25 2.25 0 012.25 2.25v1.936a2.25 2.25 0 01-2.25 2.25h-.746a21.357 21.357 0 01.24-3.219z"></path>
                            </svg>
                            <span class="font-medium">Advertisement</span>
                        </div>
                        <svg id="advertisement-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="advertisement-menu" class="{{ request()->routeIs('admin.advertisements.*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.advertisements.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.advertisements.index') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All Advertisement</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->canFeature('subscribes.view'))
                <a href="{{ route('admin.subscribes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.subscribes.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.subscribes.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                    </svg>
                    <span class="font-medium">Subscribers</span>
                </a>
                @endif

                {{-- Users --}}
                @if(auth()->user()->canFeature('users.manage'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('users-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.role-permissions.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.role-permissions.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Users</span>
                        </div>
                        <svg id="users-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.role-permissions.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="users-menu" class="{{ request()->routeIs('admin.users.*') || request()->routeIs('admin.role-permissions.*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.users.create') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Add User</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.users.index') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{-- L-shaped connector --}}
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">All User</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->canFeature('settings.meta'))
                <a href="{{ route('admin.meta.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.meta.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.meta.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"></path>
                    </svg>
                    <span class="font-medium">SEO & Meta</span>
                </a>
                @endif

                {{-- Layout --}}
                @if(auth()->user()->canFeature('settings.layout'))
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('layout-menu')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.layout.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.layout.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <span class="font-medium">Layout</span>
                        </div>
                        <svg id="layout-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-400 {{ request()->routeIs('admin.layout.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="layout-menu" class="{{ request()->routeIs('admin.layout.*') ? 'grid grid-rows-[1fr]' : 'grid grid-rows-[0fr]' }} transition-all duration-300 ease-in-out">
                        <div class="overflow-hidden">
                            <div class="ml-4 pl-0 border-l border-slate-200 dark:border-slate-800 space-y-0 py-1">
                                <a href="{{ route('admin.layout.frontend') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.layout.frontend') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Header & Footer</span>
                                </a>
                                <a href="{{ route('admin.layout.home') }}" class="flex items-center gap-0 py-2 text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all group/sub relative {{ request()->routeIs('admin.layout.home') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                    <svg class="w-6 h-6 text-slate-200 dark:text-slate-800 -ml-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M1 12h10m0 0l-4-4m4 4l-4 4"></path>
                                    </svg>
                                    <span class="ml-1">Home</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <a
                    href="{{ route('admin.user-settings.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.user-settings.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.user-settings.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium">User Settings</span>
                </a>
            </nav>
        </aside>

        {{-- Mobile backdrop: visible when sidebar open, click to close --}}
        <div id="admin-sidebar-backdrop"
            role="button"
            tabindex="0"
            aria-label="Close menu"
            class="fixed inset-0 z-40 bg-black/50 md:hidden opacity-0 pointer-events-none transition-opacity duration-300"
            onclick="window.closeAdminSidebar()"
            onkeydown="if(event.key==='Enter'||event.key===' ') { event.preventDefault(); window.closeAdminSidebar(); }"></div>

        {{-- ডেস্কটপে সাইডবারের জায়গা রাখে, যাতে মেইন কন্টেন্ট সাইডবারের ভেতরে না ঢোকে --}}
        <div class="hidden md:block w-64 shrink-0" aria-hidden="true"></div>

        {{-- Main --}}
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="h-20 min-h-[4rem] bg-white/80 border-b border-slate-200 dark:bg-slate-950/80 dark:border-slate-800 backdrop-blur-xl sticky top-0 z-30 flex items-center justify-between gap-3 px-4 md:px-6">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <button type="button"
                        onclick="window.toggleAdminSidebar()"
                        class="md:hidden p-2.5 -ml-1 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors touch-manipulation shrink-0"
                        aria-label="Toggle menu"
                        aria-expanded="false"
                        id="admin-sidebar-toggle">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-lg md:text-xl font-normal tracking-tight text-slate-900 dark:text-white truncate">
                            @yield('header_title', 'Dashboard')
                        </h1>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                            @yield('header_subtitle')
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-4 shrink-0">
                    <x-admin.theme-toggle />

                    <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="flex items-center gap-2 px-4 py-2.5 min-h-[44px] rounded-xl text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 dark:text-red-400 dark:bg-red-500/10 dark:border-red-500/20 dark:hover:bg-red-500/20 transition-all touch-manipulation">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <section class="px-2 py-4 md:px-4 md:py-6 flex-1">
                @yield('content')
            </section>
        </main>
    </div>

    {{-- Post published notification modal --}}
    @if(session('post_published'))
    @php
        $publishedPost = session('post_published');
        $publishedStatus = $publishedPost['status'] ?? 'published';
        $publishedMessage = match ($publishedStatus) {
            'draft' => 'Post saved as draft.',
            'pending' => 'Post saved as pending.',
            default => 'Post published successfully!',
        };
        $publishedPostUrl = $publishedStatus === 'published' && ! empty($publishedPost['slug'])
            ? route('news.show', $publishedPost['slug'])
            : null;
    @endphp
    <div id="post-published-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="document.getElementById('post-published-modal')?.remove()"
            aria-hidden="true"></div>
        <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="post-published-title"
            class="relative w-full max-w-md rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl p-6 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 id="post-published-title" class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                {{ $publishedMessage }}
            </h3>
            @if(! empty($publishedPost['title']))
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 line-clamp-2">
                    {{ $publishedPost['title'] }}
                </p>
            @endif
            <div class="mt-6 flex flex-row items-stretch gap-3">
                @if($publishedPostUrl)
                    <a
                        href="{{ $publishedPostUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                    >
                        See Post
                    </a>
                @endif
                <button
                    type="button"
                    onclick="document.getElementById('post-published-modal')?.remove()"
                    class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Floating flash notification (top modal style) --}}
    @if(session('success') || session('error'))
    @php
    $flashType = session('success') ? 'success' : 'error';
    $flashMessage = session('success') ?? session('error');
    @endphp
    <div
        id="admin-flash-toast"
        class="fixed inset-x-0 top-4 z-50 flex justify-center px-4 pointer-events-none">
        <div
            class="pointer-events-auto max-w-xl w-full md:w-auto flex items-start gap-3 px-4 py-3 rounded-2xl shadow-xl border
                    {{ $flashType === 'success'
                        ? 'bg-emerald-50/95 border-emerald-200 text-emerald-800 dark:bg-emerald-900/90 dark:border-emerald-700 dark:text-emerald-100'
                        : 'bg-rose-50/95 border-rose-200 text-rose-800 dark:bg-rose-900/90 dark:border-rose-700 dark:text-rose-100' }}">
            <div class="mt-0.5">
                @if($flashType === 'success')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @endif
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold leading-snug">
                    {{ $flashMessage }}
                </p>
            </div>
            <button
                type="button"
                onclick="(function(){ const t=document.getElementById('admin-flash-toast'); if(t){ t.classList.add('opacity-0','translate-y-[-8px]'); setTimeout(()=>t.remove(),180); } })()"
                class="ml-2 inline-flex rounded-full p-1 text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                aria-label="Close notification">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Auto-hide flash toast after a short delay
        (function() {
            const toast = document.getElementById('admin-flash-toast');
            if (!toast) return;
            setTimeout(function() {
                if (!toast) return;
                toast.classList.add('opacity-0', 'translate-y-[-8px]');
                setTimeout(function() {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 180);
            }, 3500);
        })();
    </script>
    @endif

    <style>
        /* অ্যাডমিন ফর্ম: ডার্ক মোডে লেবেল, ইনপুট, সিলেক্ট টেক্সট দৃশ্যমান */
        .dark main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]):not([type="hidden"]),
        .dark main select,
        .dark main textarea {
            background-color: rgb(2 6 23);
            color: rgb(248 250 252);
        }

        .dark main input::placeholder,
        .dark main textarea::placeholder {
            color: rgb(100 116 139);
        }

        .dark main select option {
            background-color: rgb(15 23 42);
            color: rgb(248 250 252);
        }

        .dark main label:not([class*="dark:text"]),
        .dark main .text-slate-900:not([class*="dark:text"]),
        .dark main .text-black:not([class*="dark:text"]),
        .dark main .text-slate-800:not([class*="dark:text"]) {
            color: rgb(226 232 240);
        }

        .dark main .text-slate-700:not([class*="dark:text"]) {
            color: rgb(203 213 225);
        }

        .dark main .text-slate-600:not([class*="dark:text"]) {
            color: rgb(148 163 184);
        }

        .dark main .bg-white:not(.dark\:bg-slate-900):not(.dark\:bg-slate-950):not([class*="dark:bg-"]) {
            background-color: rgb(15 23 42);
        }

        .dark main .hover\:bg-slate-50:hover {
            background-color: rgb(30 41 59);
        }

        .dark main .hover\:bg-emerald-50:hover {
            background-color: rgb(6 78 59 / 0.25);
        }

        .dark main .group:hover .group-hover\:text-emerald-700 {
            color: rgb(110 231 183);
        }

        /* CKEditor ডার্ক মোড */
        .dark .cke_chrome {
            border-color: rgb(51 65 85) !important;
        }

        .dark .cke_top,
        .dark .cke_bottom {
            background: rgb(15 23 42) !important;
            border-color: rgb(51 65 85) !important;
        }

        .dark .cke_toolgroup,
        .dark .cke_combo_button {
            background: rgb(30 41 59) !important;
            border-color: rgb(51 65 85) !important;
        }

        .dark .cke_button_icon {
            filter: brightness(1.15);
        }

        .dark .cke_editable,
        .dark .cke_wysiwyg_frame,
        .dark .cke_wysiwyg_div {
            background: rgb(2 6 23) !important;
            color: rgb(241 245 249) !important;
        }

        .dark .cke_path_item,
        .dark .cke_combo_text,
        .dark .cke_combo_inlinelabel {
            color: rgb(203 213 225) !important;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .btn-spinner-svg {
            animation: spin 1s linear infinite;
            height: 18px;
            width: 18px;
        }
    </style>

    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');

            if (btn && !btn.hasAttribute('onclick')) {
                // Save original content to restore if needed (though page usually reloads)
                const originalContent = btn.innerHTML;

                // Set loading state
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.7';

                // Simple pattern matching React-style loader
                btn.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <svg class="btn-spinner-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Processing...</span>
                    </div>
                `;
            }
        });
    </script>
    @stack('scripts')
</body>

</html>