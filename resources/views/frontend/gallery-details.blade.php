@php
\Carbon\Carbon::setLocale('bn');
$categoryName = $gallery->category->name ?? 'গ্যালারি';

$galleryShareTitle = $gallery->title . ' - ' . (site_name_bn());
$galleryFirstImage = $gallery->images->first();
$galleryOgVersion = $gallery->updated_at?->getTimestamp() ?? $gallery->id;
$galleryShareImage = $galleryFirstImage
    ? share_og_image_url($galleryFirstImage->image, $gallery->id, $galleryOgVersion)
    : '';
$galleryShareDescription = share_meta_description($gallery->description ?? '', $gallery->title);
@endphp
<x-layout>
    <x-slot:title>{{ $galleryShareTitle }}</x-slot>
    @if($galleryShareImage !== '')
    <x-slot:metaImage>{{ $galleryShareImage }}</x-slot>
    @endif
    <x-slot:ogTitle>{{ $gallery->title }}</x-slot>
    @if($galleryShareDescription !== '')
    <x-slot:ogDescription>{{ $galleryShareDescription }}</x-slot>
    @endif
    <x-slot:ogImageAlt>{{ $gallery->title }}</x-slot>
    <x-slot:shareUrl>{{ route('gallery.show', $gallery->slug) }}</x-slot>

    <x-ad-slot-display slug="gallery_details_below_menu" variant="banner" wrapper-class="no-print" />

        <div class="py-4 md:py-10 min-h-screen bg-white">
            <div class="container">

                <!-- Header + Breadcrumb -->
                <div class="mb-4 md:mb-10 text-left no-print">
                    <h1 class="text-2xl md:text-3xl font-semibold serif text-title mb-3">
                        {{ $categoryName }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-1 text-sm font-bold text-slate-500 mb-4 md:mb-6">
                        <a href="/" class="text-slate-500 hover:text-primary transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="text-black font-bold line-clamp-1">{{ $categoryName }}</span>
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

                    @media (min-width: 1024px) {
                        .details-grid {
                            grid-template-columns: 9fr 3fr;
                        }
                    }

                    /* প্রিন্ট সেটিংস */
                    @media print {
                        header, footer, x-header, x-footer, .md\:fixed, nav, 
                        #globalScrollToTopBtn, .details-grid > div:nth-child(2), 
                        .mt-12, .flex.items-center.gap-3, .ad-section, 
                        .flex.flex-col.gap-1.pb-2, .sub-nav, .search-overlay,
                        .no-print,
                        [class*="ad-"], [class*="advertisement"], .img-placeholder::after {
                            display: none !important;
                        }

                        body, .bg-white {
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

                <section class="details-grid">

                    <!-- প্রথম কলাম -->
                    <div class="flex flex-col gap-6 w-full">
                        <h1 class="text-2xl md:text-4xl font-bold serif text-title leading-tight">
                            {{ $gallery->title }}
                        </h1>

                        <div class="flex flex-col gap-1 pb-2 mb-2">
                            <span class="text-lg font-bold text-title leading-tight">{{ reporter_display_label($gallery->reporter, 'ফটো ডেস্ক') }}</span>
                            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 gap-4">
                                <span class="text-sm md:text-base text-desc">
                                    প্রকাশ : {{ published_at($gallery->created_at) }}
                                </span>
                                @php $shareUrl = route('gallery.show', $gallery->slug); @endphp
                                <div class="flex items-center gap-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-[#3b5998] flex items-center justify-center bg-[#3b5998] text-white hover:opacity-90 transition-all" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg></a>
                                    <a href="#" role="button" data-share-url="{{ $shareUrl }}" onclick="shareOnMessenger(event)" class="w-7 h-7 p-0 border border-[#0084ff] flex items-center justify-center bg-[#0084ff] text-white hover:opacity-90 transition-all" title="Messenger" aria-label="Share on Messenger"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z" />
                                        </svg></a>
                                    <a href="https://wa.me/?text={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-[#25D366] flex items-center justify-center bg-[#25D366] text-white hover:opacity-90 transition-all" title="WhatsApp"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($gallery->title) }}" target="_blank" rel="noopener noreferrer" class="w-7 h-7 p-0 border border-black flex items-center justify-center bg-black text-white hover:opacity-90 transition-all" title="X"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16">
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

                        @if($gallery->description)
                        <div class="w-full prose prose-lg max-w-none text-desc text-lg md:text-xl font-medium leading-relaxed">
                            {!! detail_page_description_with_ads($gallery->description, 'gallery_details') !!}
                        </div>
                        @endif

                        <!-- ছবি গ্যালারি: প্রতিটি ছবির নিচে বর্ণনা (প্রথমটার মতো) -->
                        @if($gallery->images->isNotEmpty())
                        <div class="w-full space-y-6">
                            @foreach($gallery->images as $image)
                            <div>
                                <div class="img-placeholder w-full aspect-[3/2] overflow-hidden shadow-md">
                                    <img src="{{ storage_image_url($image->image) }}"
                                        alt="{{ $image->description ?: $gallery->title }}"
                                        class="w-full h-full object-cover"
                                        onload="this.parentElement.classList.remove('img-placeholder')">
                                </div>
                                @if($image->description)
                                <p class="text-base font-medium text-desc mt-3">{{ $image->description }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- দ্বিতীয় কলাম (৩ ভাগ) -->
                    <div class="hidden lg:flex flex-col gap-10 w-full min-w-0">
                        @php
                        $adGalleryRight1 = ad_slot('gallery_details_right_1');
                        $adGalleryRight2 = ad_slot('gallery_details_right_2');
                        $hasGallerySidebarAds = ad_should_display($adGalleryRight1) || ad_should_display($adGalleryRight2);
                        @endphp
                        @if($hasGallerySidebarAds)
                        <div class="flex flex-col gap-4 w-full min-w-0 ad-section">
                            <x-ad-slot-display :ad="$adGalleryRight1" variant="sidebar" />
                            <x-ad-slot-display :ad="$adGalleryRight2" variant="sidebar" />
                        </div>
                        @endif

                        <!-- আরও গ্যালারি (সাইডবার) -->
                        @if($related->isNotEmpty())
                        <div class="flex flex-col gap-6 pt-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                                <div class="w-1.5 h-6 bg-primary"></div>
                                <h3 class="text-xl font-bold serif text-title">আরও গ্যালারি</h3>
                            </div>
                            @foreach($related as $rel)
                            @php $relThumb = $rel->images->first(); @endphp
                            <a href="{{ route('gallery.show', $rel->slug) }}" class="group cursor-pointer flex flex-col gap-2">
                                <div class="img-placeholder aspect-[16/9] overflow-hidden">
                                    <img src="{{ $relThumb ? storage_image_url($relThumb->image) : 'https://images.unsplash.com/photo-1542038784456-1ea8e935640e?w=400' }}"
                                        alt="{{ $rel->title }}"
                                        class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
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
            </div>
        </div>
</x-layout>