@php
$postShareTitle = $post->title . ' - ' . (site_name_bn());
$postOgVersion = $post->updated_at?->getTimestamp() ?? $post->id;
$postShareImage = $post->image
    ? share_og_image_url($post->image, $post->id, $postOgVersion)
    : '';
$postShareDescription = share_meta_description($post->description, $post->title);
@endphp
@php $detailsMenuAdPreload = ad_slot_image_href('details_below_menu'); @endphp
@if($detailsMenuAdPreload)
@push('preload')
<link rel="preload" as="image" href="{{ $detailsMenuAdPreload }}" fetchpriority="high">
@endpush
@endif
<x-layout>
    <x-slot:title>{{ $postShareTitle }}</x-slot>
    @if($postShareImage !== '')
    <x-slot:metaImage>{{ $postShareImage }}</x-slot>
    <x-slot:metaImageWidth>600</x-slot>
    <x-slot:metaImageHeight>338</x-slot>
    @endif
    <x-slot:ogTitle>{{ $post->title }}</x-slot>
    <x-slot:ogImageAlt>{{ $post->title }}</x-slot>
    @if($postShareDescription !== '')
    <x-slot:ogDescription>{{ $postShareDescription }}</x-slot>
    @endif
    <x-slot:shareUrl>{{ news_url($post) }}</x-slot>

                    <x-ad-slot-display slug="details_below_menu" variant="banner" wrapper-class="no-print" />

                    <div class="pt-2 pb-4 md:pt-4 md:pb-10 min-h-screen bg-white">
                        <div class="container">
                            @php
                            \Carbon\Carbon::setLocale('bn');
                            $primaryCategory = $post->categories->first();
                            $parentCategory = optional($primaryCategory)->parent;
                            $categoryName = $parentCategory ? $parentCategory->name : ($primaryCategory->name ?? 'সংবাদ');
                            @endphp

                            <!-- Header + Breadcrumbs -->
                            <div class="mb-4 md:mb-10 text-left no-print">
                                <!-- বড় header: category / parent name -->
                                <h1 class="text-2xl md:text-3xl font-semibold serif text-title mb-3">
                                    {{ $categoryName }}
                                </h1>

                                {{-- Subcategory strip (same style as category page) --}}
                                @php
                                $subCategorySource = $parentCategory ?: $primaryCategory;
                                @endphp
                                @if($subCategorySource && $subCategorySource->subCategories && $subCategorySource->subCategories->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($subCategorySource->subCategories as $child)
                                    @php
                                    $isActive = $primaryCategory && $primaryCategory->id === $child->id;
                                    $parentSlugForChild = $parentCategory
                                    ? $parentCategory->slug
                                    : $subCategorySource->slug;
                                    @endphp
                                    <a href="{{ route('category.show.child', [$parentSlugForChild, $child->slug]) }}"
                                        class="px-3 py-1 text-xs md:text-sm font-semibold border {{ $isActive ? 'border-primary text-primary' : 'border-slate-200 text-slate-700 hover:text-primary hover:border-primary' }} bg-white">
                                        {{ $child->name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Breadcrumb line -->
                                <div class="flex flex-wrap items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                                    <a href="/" class="text-slate-500 hover:text-primary transition-all flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg>
                                    </a>
                                    @if($parentCategory)
                                    {{-- Home > Parent Category > Subcategory --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                    <a href="{{ route('category.show', $parentCategory->slug) }}" class="text-black hover:text-primary transition-colors">
                                        {{ $parentCategory->name }}
                                    </a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                    <a href="{{ route('category.show.child', [$parentCategory->slug, $primaryCategory->slug]) }}" class="text-black font-bold hover:text-primary transition-colors">
                                        {{ $primaryCategory->name }}
                                    </a>
                                    @elseif($primaryCategory)
                                    {{-- Home > Single Category --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                    <a href="{{ route('category.show', $primaryCategory->slug) }}" class="text-black font-bold hover:text-primary transition-colors">
                                        {{ $primaryCategory->name }}
                                    </a>
                                    @else
                                    {{-- Home > Generic label --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                    <span class="text-black font-bold">
                                        সংবাদ
                                    </span>
                                    @endif
                                </div>

                                <div class="w-full border-b border-slate-300 relative mb-8">
                                    <div class="absolute -bottom-[1px] left-0 w-40 h-[2px] bg-primary"></div>
                                </div>
                            </div>

                            <style>
                                .details-grid {
                                    display: grid;
                                    gap: 0.7rem;
                                    grid-template-columns: 1fr;
                                }

                                @@media (min-width: 1024px) {
                                    .details-grid {
                                        grid-template-columns: 9fr 3fr;
                                    }
                                }

                                /* পোস্ট বিবরণ — প্রথম প্যারা bold, বাকি normal (লাইভে CSS build ছাড়াও) */
                                .post-description p.post-desc-p-first,
                                .post-description p:first-of-type {
                                    font-weight: 700 !important;
                                }

                                .post-description p.post-desc-p-rest,
                                .post-description p:not(.post-desc-p-first):not(:first-of-type) {
                                    font-weight: 400 !important;
                                }

                                .post-description p.post-desc-p-rest :is(b, strong) {
                                    font-weight: 400 !important;
                                }

                                .post-description p {
                                    margin: 0 !important;
                                    padding: 0 !important;
                                }

                                .post-description.prose :where(p, li, blockquote) {
                                    margin-top: 0 !important;
                                    margin-bottom: 0 !important;
                                }

                                .post-description p ~ p,
                                .post-description p.post-desc-p-rest {
                                    padding-top: 0.7em !important;
                                }

                                /* প্রিন্ট সেটিংস */
                                @@media print {

                                    /* হেডার, ফুটার, সাইডবার, বিজ্ঞাপন হিপ করা */
                                    header,
                                    footer,
                                    x-header,
                                    x-footer,
                                    .md\:fixed,
                                    nav,
                                    #globalScrollToTopBtn,
                                    .details-grid>div:nth-child(2),
                                    .mt-12,
                                    .flex.items-center.gap-3,
                                    .ad-section,
                                    .flex.flex-col.gap-1.pb-2,
                                    .sub-nav,
                                    .search-overlay,
                                    .no-print,
                                    [class*="ad-"],
                                    [class*="advertisement"],
                                    .img-placeholder::before,
                                    .img-placeholder::after {
                                        display: none !important;
                                    }

                                    /* মেইন লেআউট ফিক্স করা */
                                    body,
                                    .bg-white {
                                        background: white !important;
                                        color: black !important;
                                    }

                                    .container {
                                        width: 100% !important;
                                        max-width: 100% !important;
                                        padding: 0 !important;
                                        margin: 0 !important;
                                    }

                                    .details-grid {
                                        display: block !important;
                                    }

                                    /* কন্টেন্ট টেক্সট ফিক্স করা */
                                    .prose {
                                        padding-left: 0 !important;
                                        padding-right: 0 !important;
                                        max-width: 100% !important;
                                    }

                                    /* প্রিন্ট হেডার দেখানো */
                                    .print-only-header {
                                        display: flex !important;
                                        justify-content: center;
                                        align-items: center;
                                        padding-bottom: 20px;
                                        margin-bottom: 30px;
                                        border-bottom: 2px solid #eee;
                                    }
                                }

                                .print-only-header {
                                    display: none;
                                }
                            </style>

                            <!-- Print Only Header -->
                            <div class="print-only-header">
                                @if(!empty(optional($siteMeta)->site_logo))
                                <img src="{{ storage_image_url($siteMeta->site_logo) }}" alt="Logo" style="height: 80px; width: auto;">
                                @else
                                <h1 style="font-size: 24px; font-weight: bold; color: #e11d48;">{{ site_name_bn() }}</h1>
                                @endif
                            </div>

                            <!-- Main Layout Grid -->
                            <section class="details-grid">

                                <!-- প্রথম কলাম (৮ ভাগ) -->
                                <div class="flex flex-col gap-6 w-full">
                                    @php
                                    $subTitlePoints = [];
                                    if (! empty($post->sub_title)) {
                                    $decoded = json_decode($post->sub_title, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $subTitlePoints = collect($decoded)
                                    ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                                    ->values()
                                    ->all();
                                    } else {
                                    $subTitlePoints = [$post->sub_title];
                                    }
                                    }
                                    @endphp

                                    <header class="space-y-2">
                                        @if(filled($post->subtitle))
                                        <p class="text-sm font-medium leading-snug text-primary md:text-base">
                                            {{ $post->subtitle }}
                                        </p>
                                        @endif

                                        <h1 class="text-[2rem] md:text-[2.5rem] font-semibold serif text-title leading-tight">
                                            {{ $post->title }}
                                        </h1>

                                        @if(! empty($subTitlePoints))
                                        <ul class="m-0 list-none space-y-1 p-0 pt-1">
                                            @foreach($subTitlePoints as $point)
                                            <li class="flex items-baseline gap-2.5">
                                                <span class="inline-flex w-2 shrink-0 items-center justify-center leading-none" aria-hidden="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-title" viewBox="0 0 24 24" fill="currentColor">
                                                        <circle cx="12" cy="12" r="6" />
                                                    </svg>
                                                </span>
                                                <p class="min-w-0 flex-1 text-base font-bold leading-relaxed text-title md:text-lg whitespace-pre-line">{{ $point }}</p>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </header>

                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col gap-0">
                                            <span class="text-xl font-medium text-byline">
                                                {{ reporter_display_label($post->reporter, 'ডিজিটাল ডেস্ক') }}
                                            </span>
                                            <div class="flex flex-col gap-3">
                                                <span class="text-lg font-normal text-byline">
                                                    প্রকাশ : {{ published_at($post->created_at) }}
                                                </span>

                                                @php
                                                $shareUrl = news_url($post);
                                                $whatsappShareUrl = news_whatsapp_share_url($post);
                                                $emailSubject = $post->title;
                                                $emailBody = $post->title . "\n\n" . $shareUrl;
                                                $telegramShareUrl = "https://t.me/share/url?url=" . urlencode($shareUrl) . "&text=" . urlencode($post->title);
                                                @endphp
                                                {{-- Desktop: inline social buttons --}}
                                                <div class="hidden md:flex flex-wrap items-center gap-3">
                                                    @include('frontend.partials.post-share-buttons', [
                                                        'shareUrl' => $shareUrl,
                                                        'whatsappShareUrl' => $whatsappShareUrl,
                                                        'emailSubject' => $emailSubject,
                                                        'emailBody' => $emailBody,
                                                        'telegramShareUrl' => $telegramShareUrl,
                                                        'btnClass' => 'w-7 h-7',
                                                        'showPhotocard' => true,
                                                        'mobileAnimate' => false,
                                                    ])
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Mobile: bottom-left share FAB — icons rise upward ABOVE share btn --}}
                                        <div id="mobile-share-fab" class="md:hidden fixed bottom-5 left-4 z-[95] no-print relative w-12">
                                            <button
                                                type="button"
                                                id="mobile-share-toggle"
                                                class="relative z-10 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white shadow-lg"
                                                aria-expanded="false"
                                                aria-controls="mobile-share-menu"
                                                aria-label="শেয়ার করুন">
                                                <svg data-share-icon-open xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                                                </svg>
                                                <svg data-share-icon-close class="hidden" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <div
                                                id="mobile-share-menu"
                                                class="absolute left-0 bottom-full mb-3 flex w-12 flex-col-reverse items-center gap-3 pointer-events-none"
                                                style="bottom:100%;margin-bottom:12px;"
                                                aria-hidden="true">
                                                @include('frontend.partials.post-share-buttons', [
                                                    'shareUrl' => $shareUrl,
                                                    'whatsappShareUrl' => $whatsappShareUrl,
                                                    'emailSubject' => $emailSubject,
                                                    'emailBody' => $emailBody,
                                                    'telegramShareUrl' => $telegramShareUrl,
                                                    'btnClass' => 'w-11 h-11 shadow-lg',
                                                    'showPhotocard' => true,
                                                    'mobileAnimate' => true,
                                                ])
                                            </div>
                                        </div>

                                        <!-- ফিচারড ইমেজ -->
                                        @php
                                        $postFeaturedImageUrl = storage_image_url($post->image) ?: 'https://loremflickr.com/1200/800/parliament,building?lock=1';
                                        @endphp
                                        <div class="w-full">
                                            <a
                                                href="{{ news_photo_url($post) }}"
                                                class="group block w-full cursor-zoom-in"
                                                aria-label="পূর্ণ স্ক্রিনে ছবি দেখুন">
                                                <div class="img-placeholder w-full aspect-video overflow-hidden shadow-md">
                                                    <img src="{{ $postFeaturedImageUrl }}"
                                                        alt="{{ $post->title }}"
                                                        class="w-full h-full object-contain transition-opacity group-hover:opacity-95"
                                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                                </div>
                                            </a>
                                            @if($post->image_caption)
                                            <p class="post-image-caption mt-2 text-lg font-bold leading-relaxed" style="color:#787878">
                                                {{ $post->image_caption }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>

                                    @php
                                    $adDetailsRight1 = ad_slot('details_right_1');
                                    $adDetailsRight2 = ad_slot('details_right_2');
                                    $hasDetailsRightAds = ad_should_display($adDetailsRight1)
                                    || ad_should_display($adDetailsRight2);

                                    $descRaw = strip_empty_post_description_paragraphs($post->description ?? '');
                                    $descriptionForBody = detail_page_description_with_ads($descRaw, 'details');
                                    @endphp

                                    <!-- নিউজ ডেসক্রিপশন — প্রতি ৩ প্যারার পর ইনলাইন অ্যাড -->
                                    <div class="post-description prose prose-lg max-w-none text-title text-[1.25rem] md:text-[1.3125rem] font-extralight pt-4 px-0 lg:px-[125px] text-justify leading-[1.5]">
                                        {!! $descriptionForBody !!}
                                    </div>

                                    @if(($postCreditLine = post_credit_line($post)) !== '')
                                    <div class="mt-6 px-0 lg:px-[125px] no-print">
                                        <p class="text-lg md:text-xl font-medium text-byline">{{ $postCreditLine }}</p>
                                    </div>
                                    @endif

                                    <!-- বিষয় (Dynamic Topics) -->
                                    @if($post->topics->isNotEmpty())
                                    <div class="mt-8 pt-6 border-t border-slate-100 px-0 lg:px-[125px] no-print">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-lg font-bold text-slate-700 mr-2">বিষয় :</span>
                                            @foreach($post->topics as $topic)
                                            <a href="{{ route('topic.show', ['slug' => $topic->slug]) }}" class="px-3 py-1 bg-slate-100 hover:bg-primary hover:text-white text-slate-700 text-base font-semibold transition-all rounded-sm">
                                                {{ $topic->name }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- দ্বিতীয় কলাম (৩ ভাগ) — lg+ সাইডবার; বিবরণে ইনলাইন অ্যাড আলাদা -->
                                <div class="hidden lg:flex flex-col gap-10 w-full min-w-0">

                                    @if($hasDetailsRightAds)
                                    <div class="flex flex-col gap-4 w-full min-w-0 ad-section">
                                        <x-ad-slot-display :ad="$adDetailsRight1" variant="sidebar" />
                                        <x-ad-slot-display :ad="$adDetailsRight2" variant="sidebar" />
                                    </div>
                                    @endif

                                    <!-- এ সম্পর্কিত আরও পড়ুন (সাইডবার) -->
                                    @if($related->isNotEmpty())
                                    <div class="flex flex-col gap-6 pt-5">
                                        <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                                            <div class="w-1.5 h-6 bg-primary"></div>
                                            <h3 class="text-xl font-bold serif text-title">এ সম্পর্কিত আরও পড়ুন</h3>
                                        </div>

                                        @foreach($related->take(2) as $rel)
                                        <a
                                            href="{{ route('news.show', [$rel->slug]) }}"
                                            class="group cursor-pointer flex flex-col gap-2">
                                            <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                                <img src="{{ storage_image_url($rel->image) ?: 'https://loremflickr.com/600/400/law?lock='.$rel->id }}"
                                                    alt="{{ $rel->title }}"
                                                    class="w-full h-full object-cover"
                                                    onload="this.parentElement.classList.remove('img-placeholder')">
                                            </div>
                                            <h4 class="text-base font-bold serif leading-snug text-title group-hover:text-primary transition-colors">
                                                {{ \Illuminate\Support\Str::limit($rel->title, 80) }}
                                            </h4>
                                        </a>
                                        @endforeach
                                    </div>
                                    @endif

                                </div>

                            </section>

                            <!-- এ সম্পর্কিত আরও পড়ুন (নিচে পরের ৪টা) -->
                            @if($related->skip(2)->take(4)->isNotEmpty())
                            <div class="mt-6 md:mt-[100px] pt-4 md:pt-[60px] pb-2 md:pb-0 related-section-bottom">
                                <div class="flex items-center gap-3 mb-4 md:mb-8">
                                    <div class="w-2 h-8 bg-primary"></div>
                                    <h3 class="text-xl md:text-3xl font-bold serif text-title">এ সম্পর্কিত আরও পড়ুন</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                                    @foreach($related->skip(2)->take(4) as $rel)
                                    <a
                                        href="{{ route('news.show', [$rel->slug]) }}"
                                        class="group cursor-pointer flex flex-row md:flex-col gap-2 md:gap-3">
                                        <div class="img-placeholder w-36 h-24 md:w-full md:h-auto md:aspect-[3/2] shrink-0 overflow-hidden relative shadow-sm border border-gray-100">
                                            <img src="{{ storage_image_url($rel->image) ?: 'https://loremflickr.com/600/400/news?lock='.$rel->id }}"
                                                alt="{{ $rel->title }}"
                                                class="w-full h-full object-cover"
                                                onload="this.parentElement.classList.remove('img-placeholder')">
                                        </div>
                                        <h4 class="text-base md:text-lg font-bold serif leading-snug text-title group-hover:text-primary transition-colors flex-1">
                                            {{ \Illuminate\Support\Str::limit($rel->title, 90) }}
                                        </h4>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <script>
                        function showCopyLinkToast(btn) {
                            const toast = btn.parentElement ? btn.parentElement.querySelector('.copy-btn-toast') : null;
                            if (!toast) return;

                            toast.style.opacity = '1';

                            clearTimeout(btn._copyToastTimer);
                            btn._copyToastTimer = setTimeout(function() {
                                toast.style.opacity = '0';
                            }, 2000);
                        }

                        function copyPostShareLink(btn) {
                            const url = btn.getAttribute('data-copy-url');
                            if (!url) return;

                            const done = function() {
                                btn.classList.add('bg-primary', 'text-white');
                                showCopyLinkToast(btn);
                                clearTimeout(btn._copyBtnTimer);
                                btn._copyBtnTimer = setTimeout(function() {
                                    btn.classList.remove('bg-primary', 'text-white');
                                }, 1500);
                            };

                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                navigator.clipboard.writeText(url).then(done).catch(function() {
                                    fallbackCopy(url, done);
                                });
                            } else {
                                fallbackCopy(url, done);
                            }
                        }

                        function fallbackCopy(text, done) {
                            const ta = document.createElement('textarea');
                            ta.value = text;
                            ta.style.position = 'fixed';
                            ta.style.left = '-9999px';
                            document.body.appendChild(ta);
                            ta.select();
                            try {
                                document.execCommand('copy');
                                done();
                            } catch (e) {}
                            document.body.removeChild(ta);
                        }

                        (function initMobileShareFab() {
                            var fab = document.getElementById('mobile-share-fab');
                            var toggle = document.getElementById('mobile-share-toggle');
                            var menu = document.getElementById('mobile-share-menu');
                            if (!fab || !toggle || !menu) return;

                            // Keep stuck to the viewport (parents with overflow-x-clip break position:fixed).
                            document.body.appendChild(fab);
                            fab.style.cssText = 'position:fixed;left:16px;bottom:20px;z-index:110;width:3rem;';

                            var openIcon = toggle.querySelector('[data-share-icon-open]');
                            var closeIcon = toggle.querySelector('[data-share-icon-close]');
                            var timers = [];
                            var isOpen = false;

                            function clearTimers() {
                                timers.forEach(clearTimeout);
                                timers = [];
                            }

                            function items() {
                                return Array.prototype.slice.call(menu.querySelectorAll('.mobile-share-item'));
                            }

                            function setItemVisible(el, visible, enableClicks) {
                                el.style.opacity = visible ? '1' : '0';
                                el.style.transform = visible ? 'translateY(0) scale(1)' : 'translateY(12px) scale(0.75)';
                                el.style.pointerEvents = enableClicks ? 'auto' : 'none';
                                el.setAttribute('aria-hidden', visible ? 'false' : 'true');
                                el.querySelectorAll('a, button').forEach(function(node) {
                                    node.style.pointerEvents = enableClicks ? 'auto' : 'none';
                                });
                            }

                            function prepareItems() {
                                items().forEach(function(el) {
                                    el.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                                    setItemVisible(el, false, false);
                                });
                            }

                            function openMenu() {
                                clearTimers();
                                isOpen = true;
                                toggle.setAttribute('aria-expanded', 'true');
                                menu.setAttribute('aria-hidden', 'false');
                                menu.style.pointerEvents = 'auto';
                                if (openIcon) openIcon.classList.add('hidden');
                                if (closeIcon) closeIcon.classList.remove('hidden');

                                // Enable clicks immediately so icons work without waiting for animation.
                                items().forEach(function(el, index) {
                                    setItemVisible(el, false, true);
                                    timers.push(setTimeout(function() {
                                        setItemVisible(el, true, true);
                                    }, 30 + index * 55));
                                });
                            }

                            function closeMenu() {
                                if (!isOpen) return;
                                clearTimers();
                                isOpen = false;
                                toggle.setAttribute('aria-expanded', 'false');
                                menu.setAttribute('aria-hidden', 'true');
                                if (openIcon) openIcon.classList.remove('hidden');
                                if (closeIcon) closeIcon.classList.add('hidden');

                                var list = items().slice().reverse();
                                list.forEach(function(el, index) {
                                    timers.push(setTimeout(function() {
                                        setItemVisible(el, false, false);
                                    }, index * 35));
                                });
                                timers.push(setTimeout(function() {
                                    menu.style.pointerEvents = 'none';
                                }, list.length * 35 + 120));
                            }

                            prepareItems();

                            toggle.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                if (isOpen) closeMenu();
                                else openMenu();
                            });

                            menu.addEventListener('click', function(e) {
                                var target = e.target.closest('a, button, .post-photocard-open');
                                if (!target || !isOpen) return;
                                var isPhoto = !!(target.classList && target.classList.contains('post-photocard-open')) ||
                                    !!e.target.closest('.post-photocard-open');
                                timers.push(setTimeout(closeMenu, isPhoto ? 400 : 220));
                            });

                            document.addEventListener('click', function(e) {
                                if (!isOpen) return;
                                if (fab.contains(e.target)) return;
                                closeMenu();
                            });
                        })();
                    </script>
</x-layout>