@php
    $btnClass = $btnClass ?? 'w-7 h-7';
    $showPhotocard = $showPhotocard ?? true;
    $mobileAnimate = !empty($mobileAnimate);
    $itemClass = $mobileAnimate
        ? 'mobile-share-item inline-flex shrink-0'
        : 'inline-flex shrink-0';
    $baseBtn = trim($btnClass . ' p-0 border flex items-center justify-center text-white hover:opacity-90 transition-all');
@endphp

@if($showPhotocard)
<span class="{{ $itemClass }}">
    <x-post-photocard :post="$post" :class="$btnClass" />
</span>
@endif

<a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="{{ $itemClass }} {{ $baseBtn }} border-[#3b5998] bg-[#3b5998]" title="Facebook" aria-label="Facebook">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
</a>

<a href="#" role="button" data-share-url="{{ $shareUrl }}" onclick="shareOnMessenger(event)" class="{{ $itemClass }} {{ $baseBtn }} border-[#0084ff] bg-[#0084ff]" title="Messenger" aria-label="Share on Messenger">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.441.441 0 0 1 .51-.011l1.802 1.307c.51.37 1.158.27 1.55-.223l2.356-3.728c.226-.359-.214-.761-.551-.506l-2.525 1.917a.441.441 0 0 1-.51.011L6.595 5.893a.903.903 0 0 0-1.049.408z"/></svg>
</a>

<a href="https://wa.me/?text={{ urlencode($whatsappShareUrl) }}" target="_blank" rel="noopener noreferrer" class="{{ $itemClass }} {{ $baseBtn }} border-[#25D366] bg-[#25D366]" title="WhatsApp" aria-label="Share on WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
</a>

<a href="{{ $telegramShareUrl }}" target="_blank" rel="noopener noreferrer" class="{{ $itemClass }} {{ $baseBtn }} border-[#229ED9] bg-[#229ED9]" title="Telegram" aria-label="Share on Telegram">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512" class="block" style="margin-left:-2px"><path d="M446.7 98.6L52.7 251.2c-23.4 9-23.3 22.1-4.3 28l100.9 31.5 37.2 117.3c4.8 15 2.5 20.7 18.5 20.7 12.3 0 17.7-5.6 24.5-12.3 4.3-4.2 29.7-28.8 59.1-57.4l122.8 90.7c22.5 12.4 38.7 6 44.3-20.8l67.1-316.2c8.2-32.8-12.6-47.7-36.1-37.1zM231.4 347.1l-8.3 79.2-35.8-112.1 233.6-147.8-189.5 180.7z"/></svg>
</a>

<a href="mailto:?subject={{ rawurlencode($emailSubject) }}&body={{ rawurlencode($emailBody) }}" class="{{ $itemClass }} {{ $baseBtn }} border-[#EA4335] bg-[#EA4335]" title="Email" aria-label="Share via Email">
    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217L8 8.993.001 4.217V4z"/><path d="M0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.809-1.144l-6.57-4.027L8 9.583l-1.239-.753zM10.197 8.243L16 11.801V4.697l-5.803 3.546z"/></svg>
</a>

<span class="{{ $itemClass }} relative">
    <span class="copy-btn-toast" style="position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);z-index:50;padding:6px 12px;border-radius:6px;background:#fff;color:#000;font-size:12px;font-weight:600;line-height:1.2;white-space:nowrap;box-shadow:0 2px 10px rgba(0,0,0,0.12);border:1px solid #e2e8f0;opacity:0;pointer-events:none;transition:opacity 0.2s ease">Copied</span>
    <button type="button" onclick="copyPostShareLink(this)" data-copy-url="{{ $whatsappShareUrl }}" class="{{ $baseBtn }} border-primary bg-primary" title="লিংক কপি করুন" aria-label="লিংক কপি করুন">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
    </button>
</span>

<a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="{{ $itemClass }} {{ $baseBtn }} border-black bg-black" title="X" aria-label="Share on X">
    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/></svg>
</a>

<a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="{{ $itemClass }} {{ $baseBtn }} border-[#0077b5] bg-[#0077b5]" title="LinkedIn" aria-label="Share on LinkedIn">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 448 512" aria-hidden="true"><path d="M100.28 448H7.4V148.9h92.88V448zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
</a>

<a href="javascript:window.print()" class="{{ $itemClass }} {{ $baseBtn }} border-slate-700 bg-slate-700" title="Print" aria-label="Print">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg>
</a>
