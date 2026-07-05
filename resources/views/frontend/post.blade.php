@php
$postShareTitle = $post->title . ' - ' . (site_name_bn());
$postOgVersion = $post->updated_at?->getTimestamp() ?? $post->id;
$postShareImage = $post->image
    ? share_og_image_url($post->image, $post->id, $postOgVersion)
    : '';
$postShareDescription = share_meta_description($post->description, $post->title);
@endphp
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
                                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <span class="text-lg font-normal text-byline">
                                                    প্রকাশ : {{ published_at($post->created_at) }}
                                                </span>

                                                @php
                                                $shareUrl = news_url($post);
                                                $whatsappShareUrl = news_whatsapp_share_url($post);
                                                @endphp
                                                <!-- সোশ্যাল শেয়ার আইকনসমূহ -->
                                                <div class="flex items-center gap-3">
                                                    <x-post-photocard :post="$post" />
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-[#3b5998] flex items-center justify-center bg-[#3b5998] text-white hover:opacity-90 transition-all" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                                        </svg></a>
                                                    <a href="#" role="button" data-share-url="{{ $shareUrl }}" onclick="shareOnMessenger(event)" class="w-7 h-7 p-0 border border-[#0084ff] flex items-center justify-center bg-[#0084ff] text-white hover:opacity-90 transition-all" title="Messenger" aria-label="Share on Messenger"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z" />
                                                        </svg></a>
                                                    <a href="https://wa.me/?text={{ urlencode($whatsappShareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-[#25D366] flex items-center justify-center bg-[#25D366] text-white hover:opacity-90 transition-all" title="WhatsApp" aria-label="Share on WhatsApp"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                                                        </svg></a>
                                                    <span class="relative inline-flex shrink-0">
                                                        <span class="copy-btn-toast" style="position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);z-index:50;padding:6px 12px;border-radius:6px;background:#fff;color:#000;font-size:12px;font-weight:600;line-height:1.2;white-space:nowrap;box-shadow:0 2px 10px rgba(0,0,0,0.12);border:1px solid #e2e8f0;opacity:0;pointer-events:none;transition:opacity 0.2s ease">Copied</span>
                                                        <button type="button" onclick="copyPostShareLink(this)" data-copy-url="{{ $whatsappShareUrl }}" class="w-7 h-7 p-0 border border-primary flex items-center justify-center bg-primary text-white hover:opacity-90 transition-all" title="লিংক কপি করুন" aria-label="লিংক কপি করুন">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none">
                                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-black flex items-center justify-center bg-black text-white hover:opacity-90 transition-all" title="X"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z" />
                                                        </svg></a>
                                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-[#0077b5] flex items-center justify-center bg-[#0077b5] text-white hover:opacity-90 transition-all" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true">
                                                            <path d="M100.28 448H7.4V148.9h92.88V448zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z" />
                                                        </svg></a>
                                                    <a href="javascript:window.print()" class="w-7 h-7 p-0 border border-slate-700 flex items-center justify-center bg-slate-700 text-white hover:opacity-90 transition-all" title="Print"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                        </svg></a>
                                                </div>
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

                                    <!-- নিউজ ডেসক্রিপশন — প্রতি ৪ প্যারার পর ইনলাইন অ্যাড (মোবাইল + ডেস্কটপ) -->
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
                    </script>
</x-layout>