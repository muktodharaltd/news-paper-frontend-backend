<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('front_home_url')) {
    /**
     * ফ্রন্ট হোমপেজের সম্পূর্ণ URL (সাবডিরেক্টরি যেমন …/public/ সহ)।
     * ওয়েব রিকোয়েস্টে `url('/')` — URL::forceRootUrl() থাকলে লোগো/লিংক ব্রাউজারের হোস্টের সাথে মিলবে।
     * কনসোলে config('app.url') প্রাধান্য।
     */
    function front_home_url(): string
    {
        if (! app()->runningInConsole()) {
            try {
                return rtrim((string) url('/'), '/') . '/';
            } catch (\Throwable) {
                // fall through
            }
        }

        $configured = rtrim((string) config('app.url', ''), '/');
        if ($configured !== '') {
            return $configured . '/';
        }

        if (! app()->runningInConsole()) {
            try {
                $root = request()->root();
                if (is_string($root) && $root !== '') {
                    return rtrim($root, '/') . '/';
                }
            } catch (\Throwable) {
                // fall through
            }
        }

        try {
            return rtrim((string) url('/'), '/') . '/';
        } catch (\Throwable) {
            return '/';
        }
    }
}

if (! function_exists('storage_image_url')) {
    /**
     * ইমেজ URL: নতুন আপলোড public/posts ইত্যাদিতে; পুরনো storage/app/public → /storage/...
     * শুধু is_file() নির্ভর না — কিছু সার্ভারে false হলেও ফাইল public এ থাকে, তখন ভুলে /storage/ চলে যেত।
     */
    function storage_image_url(?string $path): string
    {
        if (! $path) {
            return '';
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        $directPublicPrefixes = ['posts/', 'videos/', 'galleries/', 'users/', 'advertisements/', 'pages/', 'meta/'];
        $isManagedUploadPath = false;
        foreach ($directPublicPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isManagedUploadPath = true;
                break;
            }
        }

        if ($isManagedUploadPath && is_file(public_path($path))) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        if ($isManagedUploadPath) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}

if (! function_exists('share_og_image_url')) {
    /**
     * Facebook/Open Graph শেয়ার ইমেজ — absolute HTTPS URL।
     * একই ফাইল অনেক পোস্টে থাকলেও FB cache আলাদা রাখতে ?og=&v= যোগ।
     */
    function share_og_image_url(?string $path, int|string|null $entityKey = null, int|string|null $version = null): string
    {
        if (! $path) {
            return '';
        }

        $url = storage_image_url($path);
        if ($url === '') {
            return '';
        }

        if (! Str::startsWith($url, ['http://', 'https://'])) {
            $url = url($url);
        }

        $url = str_replace('http://', 'https://', $url);

        $query = [];
        if ($entityKey !== null && (string) $entityKey !== '') {
            $query['og'] = (string) $entityKey;
        }
        if ($version !== null && (string) $version !== '') {
            $query['v'] = (string) $version;
        }

        if ($query !== []) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        return $url;
    }
}

if (! function_exists('storage_image_src')) {
    /**
     * ফ্রন্টএন্ড embed — storage_image_url এর মতোই path (live এ ভুল /storage/ path রোধ)।
     */
    function storage_image_src(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $parts = parse_url($path);

            return ($parts['path'] ?? '/') . (isset($parts['query']) && $parts['query'] !== '' ? '?' . $parts['query'] : '');
        }

        $path = ltrim($path, '/');

        $directPublicPrefixes = ['posts/', 'videos/', 'galleries/', 'users/', 'advertisements/', 'pages/', 'meta/'];
        $isManagedUploadPath = false;
        foreach ($directPublicPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isManagedUploadPath = true;
                break;
            }
        }

        if ($isManagedUploadPath && is_file(public_path($path))) {
            return '/' . $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return '/storage/' . $path;
        }

        if ($isManagedUploadPath) {
            return '/' . $path;
        }

        return '/storage/' . $path;
    }
}

if (! function_exists('photocard_icon_src')) {
    /**
     * ফটোকার্ড ওয়াটারমার্ক — শুধু সাইট আইকন (favicon); লোগো footer-এ আলাদা।
     */
    function photocard_icon_src(?\App\Models\SiteMeta $meta): string
    {
        if (! $meta || ! filled($meta->site_icon)) {
            return '';
        }

        return storage_image_src($meta->site_icon);
    }
}

if (! function_exists('store_public_upload')) {
    /**
     * আপলোড ফাইল public/{directory}/ এ সেভ করে DB তে রাখার জন্য রিলেটিভ পাথ রিটার্ন করে।
     */
    function store_public_upload(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $targetDir = public_path($directory);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $filename = $file->hashName();
        $file->move($targetDir, $filename);

        return $directory . '/' . $filename;
    }
}

if (! function_exists('post_featured_image_spec')) {
    /**
     * পোস্ট ফিচার্ড ইমেজ — ১৬:৯, সাইটে সেভ/দেখানোর স্ট্যান্ডার্ড সাইজ।
     */
    function post_featured_image_spec(): array
    {
        return [
            'width' => 600,
            'height' => 338,
            'jpeg_quality' => 85,
        ];
    }
}

if (! function_exists('apply_exif_orientation_to_gd')) {
    function apply_exif_orientation_to_gd(\GdImage $image, int $orientation): \GdImage
    {
        $rotated = match ($orientation) {
            2 => (imageflip($image, IMG_FLIP_HORIZONTAL) ? $image : $image),
            3 => imagerotate($image, 180, 0),
            4 => (imageflip($image, IMG_FLIP_VERTICAL) ? $image : $image),
            5 => tap(imagerotate($image, -90, 0), function ($img) {
                if ($img instanceof \GdImage) {
                    imageflip($img, IMG_FLIP_HORIZONTAL);
                }
            }),
            6 => imagerotate($image, -90, 0),
            7 => tap(imagerotate($image, 90, 0), function ($img) {
                if ($img instanceof \GdImage) {
                    imageflip($img, IMG_FLIP_HORIZONTAL);
                }
            }),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        if ($rotated instanceof \GdImage && $rotated !== $image) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }
}

if (! function_exists('create_gd_image_from_path')) {
    function create_gd_image_from_path(string $path, ?string $mime = null): ?\GdImage
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $mime = $mime ?: (mime_content_type($path) ?: '');

        $image = match (true) {
            str_contains($mime, 'jpeg'), str_contains($mime, 'jpg') => @imagecreatefromjpeg($path),
            str_contains($mime, 'png') => @imagecreatefrompng($path),
            str_contains($mime, 'webp') => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            str_contains($mime, 'gif') => @imagecreatefromgif($path),
            default => false,
        };

        if (! $image instanceof \GdImage) {
            return null;
        }

        if (
            function_exists('exif_read_data')
            && (str_contains($mime, 'jpeg') || str_contains($mime, 'jpg'))
        ) {
            $exif = @exif_read_data($path);
            $orientation = (int) ($exif['Orientation'] ?? 0);
            if ($orientation > 1) {
                $image = apply_exif_orientation_to_gd($image, $orientation);
            }
        }

        return $image;
    }
}

if (! function_exists('encode_post_featured_jpeg')) {
    function encode_post_featured_jpeg(\GdImage $image): ?string
    {
        ob_start();
        $quality = (int) post_featured_image_spec()['jpeg_quality'];
        $ok = imagejpeg($image, null, $quality);
        $data = ob_get_clean();

        return ($ok && is_string($data) && $data !== '') ? $data : null;
    }
}

if (! function_exists('resize_image_to_post_featured_binary')) {
    /**
     * যেকোনো ইমেজ ১৬:৯ center-cover করে ৬০০×৩৩৮ JPEG বাইনারি রিটার্ন করে।
     */
    function resize_image_to_post_featured_binary(string $sourcePath, ?string $mime = null): ?string
    {
        $image = create_gd_image_from_path($sourcePath, $mime);
        if (! $image instanceof \GdImage) {
            return null;
        }

        $spec = post_featured_image_spec();
        $targetW = (int) $spec['width'];
        $targetH = (int) $spec['height'];
        $targetRatio = $targetW / $targetH;

        $srcW = imagesx($image);
        $srcH = imagesy($image);

        if ($srcW < 1 || $srcH < 1) {
            imagedestroy($image);

            return null;
        }

        $srcRatio = $srcW / $srcH;
        $ratioTolerance = 0.03;

        if (abs($srcRatio - $targetRatio) <= $ratioTolerance) {
            $cropX = 0;
            $cropY = 0;
            $cropW = $srcW;
            $cropH = $srcH;
        } elseif ($srcRatio > $targetRatio) {
            $cropH = $srcH;
            $cropW = (int) round($srcH * $targetRatio);
            $cropX = (int) round(($srcW - $cropW) / 2);
            $cropY = 0;
        } else {
            $cropW = $srcW;
            $cropH = (int) round($srcW / $targetRatio);
            $cropX = 0;
            $cropY = (int) round(($srcH - $cropH) / 2);
        }

        $dest = imagecreatetruecolor($targetW, $targetH);
        if (! $dest instanceof \GdImage) {
            imagedestroy($image);

            return null;
        }

        imagecopyresampled(
            $dest,
            $image,
            0,
            0,
            $cropX,
            $cropY,
            $targetW,
            $targetH,
            $cropW,
            $cropH,
        );
        imagedestroy($image);

        $binary = encode_post_featured_jpeg($dest);
        imagedestroy($dest);

        return $binary;
    }
}

if (! function_exists('store_post_featured_upload')) {
    /**
     * পোস্ট ফিচার্ড ইমেজ আপলোড — ৬০০×৩৩৮ JPEG এ রিসাইজ করে public/posts/ এ সেভ।
     */
    function store_post_featured_upload(UploadedFile $file): string
    {
        $directory = 'posts';
        $targetDir = public_path($directory);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $binary = resize_image_to_post_featured_binary(
            $file->getRealPath() ?: '',
            $file->getMimeType() ?: null,
        );

        if ($binary !== null) {
            $filename = Str::uuid()->toString() . '.jpg';
            file_put_contents($targetDir . '/' . $filename, $binary);

            return $directory . '/' . $filename;
        }

        return store_public_upload($file, $directory);
    }
}

if (! function_exists('delete_uploaded_media')) {
    /**
     * public/ বা পুরনো storage disk — যেখানে ফাইল আছে সেখান থেকে মুছে দেয়।
     */
    function delete_uploaded_media(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }
        $path = ltrim($path, '/');
        $full = public_path($path);
        if (is_file($full)) {
            @unlink($full);

            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

if (! function_exists('published_at')) {
    /**
     * Format published date/time for detail pages (বাংলা মাস + বাংলা সংখ্যা).
     * Database এ যে মান সেভ আছে সেটাই দেখায় (কোনো টাইমজোন পরিবর্তন নেই).
     * $date null হলে খালি স্ট্রিং রিটার্ন করে।
     */
    function published_at($date, string $format = 'd M Y, H:i'): string
    {
        if (! $date) {
            return '';
        }

        $months = [
            'Jan' => 'জানুয়ারি',
            'Feb' => 'ফেব্রুয়ারি',
            'Mar' => 'মার্চ',
            'Apr' => 'এপ্রিল',
            'May' => 'মে',
            'Jun' => 'জুন',
            'Jul' => 'জুলাই',
            'Aug' => 'আগস্ট',
            'Sep' => 'সেপ্টেম্বর',
            'Oct' => 'অক্টোবর',
            'Nov' => 'নভেম্বর',
            'Dec' => 'ডিসেম্বর',
        ];

        $formatted = $date->format($format);

        foreach ($months as $en => $bn) {
            $formatted = str_replace($en, $bn, $formatted);
        }

        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace(range(0, 9), $bnDigits, $formatted);
    }
}

if (! function_exists('bangla_diff_for_humans')) {
    function bangla_diff_for_humans($date): string
    {
        if (! $date) {
            return '';
        }

        \Carbon\Carbon::setLocale('bn');
        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace(range(0, 9), $bnDigits, $date->diffForHumans());
    }
}

if (! function_exists('post_list_meta_parts')) {
    /**
     * তালিকায় meta line-এর অংশগুলো: [ক্যাটাগরি, রিপোর্টার/ডেস্ক, সময়]।
     *
     * @return array<int, string>
     */
    function post_list_meta_parts($post): array
    {
        $parts = [];

        $categoryName = trim((string) optional($post->categories->first())->name);
        if ($categoryName !== '') {
            $parts[] = $categoryName;
        }

        $reporterLabel = reporter_display_label($post->reporter);
        if ($reporterLabel !== '') {
            $parts[] = $reporterLabel;
        }

        if ($post->created_at) {
            $parts[] = bangla_diff_for_humans($post->created_at);
        }

        return $parts;
    }
}

if (! function_exists('post_list_meta_line')) {
    /**
     * তালিকায়: ক্যাটাগরি | রিপোর্টার/ডেস্ক | সময় (বাংলায়)।
     */
    function post_list_meta_line($post): string
    {
        return implode(' | ', post_list_meta_parts($post));
    }
}

if (! function_exists('category_url')) {
    /**
     * Build category listing page URL (parent or child category).
     */
    function category_url($category): string
    {
        if (! $category || ! $category->slug) {
            return url('/');
        }
        if ($category->parent_id && $category->relationLoaded('parent') && $category->parent) {
            return route('category.show.child', [$category->parent->slug, $category->slug]);
        }

        return route('category.show', $category->slug);
    }
}

if (! function_exists('ad_queue_display_get')) {
    function ad_queue_display_get(?\App\Models\Advertisement $ad): ?int
    {
        if (! $ad) {
            return null;
        }
        if (! isset($GLOBALS['__adQueueDisplayMap']) || ! is_array($GLOBALS['__adQueueDisplayMap'])) {
            $GLOBALS['__adQueueDisplayMap'] = [];
        }

        return $GLOBALS['__adQueueDisplayMap'][spl_object_id($ad)] ?? null;
    }
}

if (! function_exists('ad_queue_display_set')) {
    function ad_queue_display_set(\App\Models\Advertisement $ad, int $queueItemId): void
    {
        if (! isset($GLOBALS['__adQueueDisplayMap']) || ! is_array($GLOBALS['__adQueueDisplayMap'])) {
            $GLOBALS['__adQueueDisplayMap'] = [];
        }
        $GLOBALS['__adQueueDisplayMap'][spl_object_id($ad)] = $queueItemId;
    }
}

if (! function_exists('advertisement_bump_view_once')) {
    /**
     * একই HTTP রিকোয়েস্টে একই অ্যাড আইডিতে একবারই views_count বাড়ে।
     */
    function advertisement_bump_view_once(?\App\Models\Advertisement $ad): void
    {
        if (! $ad || app()->runningInConsole()) {
            return;
        }
        $visible = filled($ad->image) || filled($ad->video_youtube_id);
        if (! $visible) {
            return;
        }
        try {
            $req = request();
        } catch (\Throwable) {
            return;
        }
        if (! $req) {
            return;
        }
        $queueId = ad_queue_display_get($ad);
        $key = $queueId !== null ? 'q:' . $queueId : 'a:' . $ad->id;
        $keys = $req->attributes->get('advertisement_view_bumped_keys', []);
        if (! is_array($keys)) {
            $keys = [];
        }
        if (in_array($key, $keys, true)) {
            return;
        }
        $req->attributes->set('advertisement_view_bumped_keys', array_merge($keys, [$key]));
        if ($queueId !== null) {
            \App\Models\AdvertisementQueueItem::query()->whereKey($queueId)->increment('views_count');
        } else {
            \App\Models\Advertisement::query()->whereKey($ad->id)->increment('views_count');
        }
    }
}

if (! function_exists('advertisement_record_views_for_models')) {
    /**
     * @param  iterable<\App\Models\Advertisement>  $advertisements
     */
    function advertisement_record_views_for_models(iterable $advertisements): void
    {
        foreach ($advertisements as $ad) {
            if ($ad instanceof \App\Models\Advertisement) {
                advertisement_bump_view_once($ad);
            }
        }
    }
}

if (! function_exists('advertisement_click_url')) {
    /**
     * ফ্রন্টে অ্যাড ক্লিক ট্র্যাকিং URL (রিলেটিভ)। টার্গেট লিংক খালি হলেও ক্লিক কাউন্ট হবে; রিডাইরেক্ট হোমে।
     */
    function advertisement_click_url(?\App\Models\Advertisement $ad): string
    {
        if (! $ad || ! $ad->id) {
            return '#';
        }
        $queueId = ad_queue_display_get($ad);
        if ($queueId !== null) {
            $sig = hash_hmac('sha256', 'queue:' . (string) $queueId, (string) config('app.key'));

            return route('advertisement.queue-click', ['queueItem' => $queueId, 's' => $sig], false);
        }
        $sig = hash_hmac('sha256', (string) $ad->id, (string) config('app.key'));

        return route('advertisement.click', ['advertisement' => $ad->id, 's' => $sig], false);
    }
}

if (! function_exists('normalize_google_adsense_client')) {
    /**
     * AdSense Publisher ID — pub- / ca-pub- / শুধু সংখ্যা → ca-pub-XXXXXXXX
     */
    function normalize_google_adsense_client(?string $raw): ?string
    {
        $raw = trim((string) ($raw ?? ''));

        if ($raw === '') {
            return null;
        }

        if (preg_match('/^(?:ca-pub-|pub-)(\d+)$/i', preg_replace('/\s+/', '', $raw), $m)) {
            return 'ca-pub-' . $m[1];
        }

        if (preg_match('/^\d+$/', preg_replace('/\s+/', '', $raw))) {
            return 'ca-pub-' . preg_replace('/\s+/', '', $raw);
        }

        return null;
    }
}

if (! function_exists('google_adsense_client')) {
    /**
     * Google AdSense publisher client ID (ca-pub-xxxxxxxx).
     */
    function google_adsense_client(): ?string
    {
        return normalize_google_adsense_client(optional(site_meta_record())->google_adsense_client ?? null);
    }
}

if (! function_exists('google_adsense_configured')) {
    /**
     * SEO & Meta-তে Client ID সেট আছে কিনা।
     */
    function google_adsense_configured(): bool
    {
        return filled(google_adsense_client());
    }
}

if (! function_exists('normalize_google_adsense_slot')) {
    /**
     * AdSense unit Slot ID — শুধু সংখ্যা রাখে।
     */
    function normalize_google_adsense_slot(?string $raw): ?string
    {
        $slot = preg_replace('/\D+/', '', trim((string) ($raw ?? '')));

        return $slot !== '' ? $slot : null;
    }
}

if (! function_exists('google_adsense_default_slot')) {
    /**
     * SEO & Meta-তে সেট করা ডিফল্ট Slot ID — সব ad slot-এ fallback।
     */
    function google_adsense_default_slot(): ?string
    {
        return normalize_google_adsense_slot(optional(site_meta_record())->google_adsense_default_slot ?? null);
    }
}

if (! function_exists('google_adsense_slot_for')) {
    /**
     * প্রতি slot-এর Slot ID; খালি থাকলে site default।
     */
    function google_adsense_slot_for(?\App\Models\Advertisement $ad): ?string
    {
        $own = normalize_google_adsense_slot($ad?->google_ad_slot ?? null);
        if ($own !== null) {
            return $own;
        }

        return google_adsense_default_slot();
    }
}

if (! function_exists('ad_show_google')) {
    /**
     * ফ্রন্টে এই স্লটে Google Ad দেখানো হবে কিনা (প্রতি পেজে সীমা + একই slug একবার)।
     */
    function ad_show_google(?\App\Models\Advertisement $ad): bool
    {
        if (! $ad || ! $ad->displayUsesGoogleAd()) {
            return false;
        }

        static $claimed = [];

        $key = (string) ($ad->slug ?? $ad->id);
        if (array_key_exists($key, $claimed)) {
            return $claimed[$key];
        }

        return $claimed[$key] = true;
    }
}

if (! function_exists('ad_media_spec_dimensions')) {
    /**
     * config/advertisement_slots media_specs.size → ['width' => int, 'height' => int]
     *
     * @param  array{ratio?: string, size?: string, note?: string}|null  $spec
     * @return array{width: int, height: int}|null
     */
    function ad_media_spec_dimensions(?array $spec): ?array
    {
        $size = is_array($spec) ? ($spec['size'] ?? null) : null;
        if (! is_string($size) || $size === '') {
            return null;
        }

        if (! preg_match('/(\d+)\s*[×x]\s*(\d+)/u', $size, $matches)) {
            return null;
        }

        return [
            'width' => (int) $matches[1],
            'height' => (int) $matches[2],
        ];
    }
}

if (! function_exists('ad_slot_box_style')) {
    /**
     * Advertisement slot box — config size = min & max (local + Google একই)।
     *
     * @param  'strip'|'full-strip'|'box'  $layout  strip = container banner; full-strip = site full width; box = sidebar/inline
     */
    function ad_slot_box_style(?\App\Models\Advertisement $ad, string $layout = 'strip'): string
    {
        $dims = ad_media_spec_dimensions($ad?->mediaSpec());
        if (! $dims) {
            $stripMobileH = 90;
            $boxMobileH = 240;

            if ($layout === 'full-strip') {
                return "width:100%;max-width:100%;height:90px;min-height:90px;max-height:90px;--ad-max-width:100%;--ad-max-height:90px;--ad-aspect-ratio:1300/90;--ad-mobile-max-width:100%;--ad-mobile-max-height:{$stripMobileH}px;";
            }

            return $layout === 'strip'
                ? "width:100%;height:90px;min-height:90px;max-height:90px;--ad-max-width:100%;--ad-max-height:90px;--ad-aspect-ratio:1300/90;--ad-mobile-max-width:100%;--ad-mobile-max-height:{$stripMobileH}px;"
                : "width:100%;aspect-ratio:4/3;max-height:240px;--ad-max-width:100%;--ad-max-height:240px;--ad-aspect-ratio:4/3;--ad-mobile-max-width:100%;--ad-mobile-max-height:{$boxMobileH}px;";
        }

        $width = $dims['width'];
        $height = $dims['height'];
        $mobileMaxH = $layout === 'strip' || $layout === 'full-strip' ? $height : min($height, 240);
        $mobileVars = "--ad-mobile-max-width:100%;--ad-mobile-max-height:{$mobileMaxH}px;";
        $aspectVar = "--ad-aspect-ratio:{$width}/{$height};";

        if ($layout === 'full-strip') {
            return "width:100%;max-width:100%;height:{$height}px;min-height:{$height}px;max-height:{$height}px;--ad-max-width:100%;--ad-max-height:{$height}px;{$aspectVar}{$mobileVars}";
        }

        if ($layout === 'strip') {
            return "width:100%;max-width:{$width}px;height:{$height}px;min-height:{$height}px;max-height:{$height}px;margin-left:auto;margin-right:auto;--ad-max-width:{$width}px;--ad-max-height:{$height}px;{$aspectVar}{$mobileVars}";
        }

        return "width:100%;max-width:{$width}px;aspect-ratio:{$width}/{$height};max-height:{$height}px;margin-left:auto;margin-right:auto;--ad-max-width:{$width}px;--ad-max-height:{$height}px;{$aspectVar}{$mobileVars}";
    }
}

if (! function_exists('ad_has_media')) {
    /**
     * অ্যাডে দেখানোর মতো মিডিয়া আছে কিনা (ইমেজ, GIF, ভিডিও ফাইল, YouTube)।
     */
    function ad_has_media(?\App\Models\Advertisement $ad): bool
    {
        if (! $ad) {
            return false;
        }

        return filled($ad->video_youtube_id)
            || filled($ad->video)
            || filled($ad->video_mobile)
            || filled($ad->image)
            || filled($ad->image_mobile);
    }
}

if (! function_exists('ad_should_display')) {
    /** ফ্রন্টে এই স্লটে কিছু দেখানোর যোগ্য কিনা (local বা Google eligible)। */
    function ad_should_display(?\App\Models\Advertisement $ad): bool
    {
        if (! $ad) {
            return false;
        }

        return $ad->displayUsesLocalAd() || $ad->displayUsesGoogleAd();
    }
}

if (! function_exists('ad_slot')) {
    /**
     * Get ad slot by frontend slug for display.
     * Returns the Advertisement model or null.
     */
    function ad_slot(string $slug): ?\App\Models\Advertisement
    {
        $ad = \App\Models\Advertisement::getBySlug($slug);
        if (! $ad) {
            return null;
        }

        if ($ad->displayUsesLocalAd()) {
            advertisement_bump_view_once($ad);
        }

        return ad_should_display($ad) ? $ad : null;
    }
}

if (! function_exists('ad_slot_image_href')) {
    /** Local ad image URL for preload (view count বাড়ায় না)। */
    function ad_slot_image_href(string $slug): ?string
    {
        $ad = \App\Models\Advertisement::getBySlug($slug);
        if (! $ad || ! $ad->displayUsesLocalAd()) {
            return null;
        }

        $path = $ad->image ?: $ad->image_mobile;

        return filled($path) ? storage_image_url($path) : null;
    }
}

if (! function_exists('inject_post_detail_ads_between_paragraphs')) {
    /**
     * বিবরণ HTML-এর ভিতরে ইনলাইন অ্যাড বসায় — প্রতি N প্যারার পর একটি (ডিফল্ট ৩)।
     *
     * @param  string  $html  WYSIWYG বিবরণ HTML
     * @param  array<int, string>  $adBlocks  রেন্ডার করা HTML ব্লক (খালি স্ট্রিং বাদ)
     * @param  int  $paragraphInterval  কত প্যারার পর পর একটি অ্যাড
     */
    function inject_post_detail_ads_between_paragraphs(string $html, array $adBlocks, int $paragraphInterval = 3): string
    {
        $adBlocks = array_values(array_filter(
            $adBlocks,
            static fn ($block) => is_string($block) && $block !== ''
        ));
        if ($adBlocks === []) {
            return $html;
        }

        if (! preg_match_all('/<\/p\s*>/i', $html, $matches, PREG_OFFSET_CAPTURE)) {
            return $html;
        }

        $tagMatches = $matches[0];
        $paragraphCount = count($tagMatches);
        $interval = max(1, $paragraphInterval);
        if ($paragraphCount < $interval) {
            return $html;
        }

        $endAfter = static function (array $m): int {
            return $m[1] + strlen($m[0]);
        };

        /** @var array<int, string> */
        $at = [];
        $adIndex = 0;

        for ($afterParagraph = $interval; $afterParagraph <= $paragraphCount && $adIndex < count($adBlocks); $afterParagraph += $interval) {
            $pos = $endAfter($tagMatches[$afterParagraph - 1]);
            $at[$pos] = ($at[$pos] ?? '') . $adBlocks[$adIndex];
            $adIndex++;
        }

        krsort($at, SORT_NUMERIC);
        foreach ($at as $offset => $fragment) {
            $html = substr($html, 0, $offset) . $fragment . substr($html, $offset);
        }

        return $html;
    }
}

if (! function_exists('detail_inline_ad_blocks')) {
    /**
     * @param  array<int, string>  $slugs
     * @return array<int, string>
     */
    function detail_inline_ad_blocks(array $slugs): array
    {
        $blocks = [];
        foreach ($slugs as $slug) {
            $ad = ad_slot($slug);
            if (ad_should_display($ad)) {
                $blocks[] = view('frontend.partials.detail-inline-ad', ['ad' => $ad])->render();
            }
        }

        return $blocks;
    }
}

if (! function_exists('detail_page_description_with_ads')) {
    function detail_page_description_with_ads(?string $html, string $inlineSlugPrefix): string
    {
        $slugs = [];
        for ($i = 1; $i <= 4; $i++) {
            $slugs[] = "{$inlineSlugPrefix}_inline_{$i}";
        }

        $descRaw = strip_empty_post_description_paragraphs($html ?? '');

        return tighten_post_description_paragraph_spacing(
            inject_post_detail_ads_between_paragraphs($descRaw, detail_inline_ad_blocks($slugs))
        );
    }
}

if (! function_exists('is_empty_description_paragraph_inner')) {
    /**
     * CKEditor-এর খালি প্যারা (<p>&nbsp;</p>, <p><br></p> ইত্যাদি) শনাক্ত করে।
     */
    function is_empty_description_paragraph_inner(string $inner): bool
    {
        $normalized = preg_replace('/<(br|hr)\b[^>]*\/?>/i', '', $inner) ?? $inner;
        $text = html_entity_decode(strip_tags($normalized), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\xc2\xa0", "\u{00a0}"], '', $text);

        return trim($text) === '';
    }
}

if (! function_exists('strip_empty_post_description_paragraphs')) {
    /**
     * বিবরণ HTML থেকে খালি প্যারা সরায় — live-এ প্যারার মাঝে অতিরিক্ত gap এড়ায়।
     */
    function strip_empty_post_description_paragraphs(string $html): string
    {
        if ($html === '' || ! str_contains(strtolower($html), '<p')) {
            return $html;
        }

        return preg_replace_callback(
            '/<p\b([^>]*)>(.*?)<\/p>/is',
            static function (array $match): string {
                return is_empty_description_paragraph_inner($match[2]) ? '' : $match[0];
            },
            $html
        ) ?? $html;
    }
}

if (! function_exists('strip_post_description_bold_styles')) {
    /**
     * যেকোনো ট্যাগের style attribute থেকে bold font-weight সরায় (CKEditor wrapper div ইত্যাদি)।
     */
    function strip_post_description_bold_styles(string $html): string
    {
        $prev = null;

        while ($prev !== $html) {
            $prev = $html;
            $html = preg_replace_callback(
                '/\sstyle=(["\'])(.*?)\1/i',
                static function (array $m): string {
                    $style = preg_replace(
                        '/(?:^|;\s*)font-weight\s*:\s*(?:bold|700|800|900)\s*;?/i',
                        '',
                        $m[2]
                    ) ?? $m[2];
                    $style = trim($style, '; ');

                    return $style === '' ? '' : ' style="'.$style.'"';
                },
                $html
            ) ?? $html;
        }

        return $html;
    }
}

if (! function_exists('strip_post_description_inline_bold')) {
    /**
     * প্যারার ভিতরের <b>/<strong> ও inline bold style সরায়।
     */
    function strip_post_description_inline_bold(string $inner): string
    {
        $prev = null;
        while ($prev !== $inner) {
            $prev = $inner;
            $inner = preg_replace('/<(b|strong)\b[^>]*>(.*?)<\/\1>/is', '$2', $inner) ?? $inner;
        }

        return preg_replace_callback(
            '/\sstyle=(["\'])(.*?)\1/i',
            static function (array $m): string {
                $style = preg_replace('/(?:^|;\s*)font-weight\s*:\s*(?:bold|700|800|900)\s*/i', '', $m[2]) ?? $m[2];
                $style = trim($style, '; ');
                if ($style === '') {
                    return '';
                }

                return ' style="'.$style.'"';
            },
            $inner
        ) ?? $inner;
    }
}

if (! function_exists('normalize_post_description_rest_paragraph_inner_html')) {
    /** @deprecated Use strip_post_description_inline_bold() */
    function normalize_post_description_rest_paragraph_inner_html(string $inner): string
    {
        return strip_post_description_inline_bold($inner);
    }
}

if (! function_exists('split_post_description_double_br_paragraphs')) {
    /**
     * একটিমাত্র <p>-এর ভিতর <br><br> দিয়ে আলাদা প্যারা থাকলে আলাদা <p>-তে ভাগ করে।
     */
    function split_post_description_double_br_paragraphs(string $html): string
    {
        if ($html === '' || ! str_contains(strtolower($html), '<p')) {
            return $html;
        }

        return preg_replace_callback(
            '/<p\b([^>]*)>(.*?)<\/p>/is',
            static function (array $match): string {
                $attrs = $match[1];
                $inner = strip_post_description_inline_bold($match[2]);

                if (! preg_match('/<br\s*\/?>\s*<br\s*\/?>/i', $inner)) {
                    if (is_empty_description_paragraph_inner($inner)) {
                        return '';
                    }

                    return '<p'.$attrs.'>'.$inner.'</p>';
                }

                $parts = preg_split('/<br\s*\/?>\s*<br\s*\/?>/i', $inner) ?: [];
                $blocks = [];

                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($part === '' || is_empty_description_paragraph_inner($part)) {
                        continue;
                    }
                    $blocks[] = '<p'.$attrs.'>'.$part.'</p>';
                }

                return $blocks === [] ? '' : implode('', $blocks);
            },
            $html
        ) ?? $html;
    }
}

if (! function_exists('sanitize_post_description_for_storage')) {
    /**
     * সেভের আগে বিবরণ পরিষ্কার — ম্যানুয়াল bold সরায়; ফ্রন্টে শুধু প্রথম প্যারা bold হবে।
     */
    function sanitize_post_description_for_storage(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $html = strip_post_description_bold_styles($html);
        $html = split_post_description_double_br_paragraphs($html);
        $html = strip_empty_post_description_paragraphs($html);

        if ($html === '' || ! str_contains(strtolower($html), '<p')) {
            return $html;
        }

        return preg_replace_callback(
            '/<p\b([^>]*)>(.*?)<\/p>/is',
            static function (array $match): string {
                if (is_empty_description_paragraph_inner($match[2])) {
                    return '';
                }

                $attrs = trim($match[1]);
                $attrs = trim(preg_replace('/\sstyle=(["\']).*?\1/i', '', ' '.$attrs) ?? $attrs);
                $attrs = trim(preg_replace('/\sclass=(["\']).*?\1/i', '', ' '.$attrs) ?? $attrs);
                $inner = strip_post_description_inline_bold($match[2]);
                $attrStr = $attrs !== '' ? ' '.$attrs : '';

                return '<p'.$attrStr.'>'.$inner.'</p>';
            },
            $html
        ) ?? $html;
    }
}

if (! function_exists('tighten_post_description_paragraph_spacing')) {
    /**
     * পোস্ট বিবরণ — প্রথম প্যারা bold, বাকি normal (inline + class; CSS build ছাড়াও কাজ করে)।
     */
    function tighten_post_description_paragraph_spacing(string $html): string
    {
        if ($html === '' || ! str_contains(strtolower($html), '<p')) {
            return $html;
        }

        $html = strip_post_description_bold_styles($html);
        $html = split_post_description_double_br_paragraphs($html);
        $html = strip_empty_post_description_paragraphs($html);
        if ($html === '' || ! str_contains(strtolower($html), '<p')) {
            return $html;
        }

        $index = 0;

        return preg_replace_callback(
            '/<p\b([^>]*)>(.*?)<\/p>/is',
            static function (array $match) use (&$index): string {
                if (is_empty_description_paragraph_inner($match[2])) {
                    return '';
                }

                $index++;
                $isFirst = $index === 1;
                $className = $isFirst ? 'post-desc-p-first' : 'post-desc-p-rest';
                $inner = strip_post_description_inline_bold($match[2]);

                $style = 'margin:0!important;padding:0!important;';
                $style .= $isFirst
                    ? 'font-weight:700!important;'
                    : 'font-weight:400!important;padding-top:0.7em!important;';

                $attrs = trim($match[1]);
                $attrs = trim(preg_replace('/\sstyle=(["\']).*?\1/i', '', ' '.$attrs) ?? $attrs);
                $attrs = trim(preg_replace('/\sclass=(["\']).*?\1/i', '', ' '.$attrs) ?? $attrs);

                $attrStr = ' class="'.$className.'" style="'.$style.'"';
                if ($attrs !== '') {
                    $attrStr .= ' '.$attrs;
                }

                return '<p'.$attrStr.'>'.$inner.'</p>';
            },
            $html
        ) ?? $html;
    }
}

if (! function_exists('share_meta_description')) {
    /**
     * Open Graph share description — article excerpt (title duplicate বাদ)।
     */
    function share_meta_description(?string $html, ?string $title = null, int $limit = 200): string
    {
        $desc = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html ?? ''))) ?? '');

        if ($desc === '') {
            return '';
        }

        if ($title !== null && trim($title) !== '') {
            $normTitle = mb_strtolower(preg_replace('/\s+/u', ' ', trim($title)));
            $normDesc = mb_strtolower(preg_replace('/\s+/u', ' ', $desc));

            if ($normDesc === $normTitle) {
                return '';
            }

            if (str_starts_with($normDesc, $normTitle)) {
                $rest = trim(mb_substr($desc, mb_strlen(trim($title))));
                $rest = ltrim($rest, " \t\n\r\0\x0B.,;:-–—");
                if (mb_strlen($rest) >= 10) {
                    $desc = $rest;
                } else {
                    $sentences = preg_split('/(?<=[।.!?])\s+/u', $desc) ?: [];
                    $desc = '';
                    foreach ($sentences as $sentence) {
                        $sentence = trim($sentence);
                        if ($sentence === '' || mb_strtolower($sentence) === $normTitle) {
                            continue;
                        }
                        $desc = $sentence;
                        break;
                    }
                    if ($desc === '') {
                        return '';
                    }
                }
            }
        }

        $limited = \Illuminate\Support\Str::limit($desc, $limit);

        if ($title !== null && trim($title) !== '' && mb_strtolower(trim($limited)) === mb_strtolower(trim($title))) {
            return '';
        }

        return $limited;
    }
}

if (! function_exists('site_meta_record')) {
    function site_meta_record(): ?\App\Models\SiteMeta
    {
        if (! app()->runningInConsole()) {
            try {
                $shared = view()->shared('siteMeta');
                if ($shared instanceof \App\Models\SiteMeta) {
                    return $shared;
                }
            } catch (\Throwable) {
            }
        }

        return \App\Models\SiteMeta::first();
    }
}

if (! function_exists('site_name')) {
    /** Site metadata থেকে site_name (না থাকলে site_title)। */
    function site_name(): string
    {
        $meta = site_meta_record();
        $name = trim((string) (optional($meta)->site_name ?? ''));

        if ($name !== '') {
            return $name;
        }

        return trim((string) (optional($meta)->site_title ?? ''));
    }
}

if (! function_exists('site_browser_title')) {
    /** Browser tab title — site_title প্রাধান্য, না থাকলে বাংলা site_name_bn, তারপর site_name। */
    function site_browser_title(): string
    {
        $meta = site_meta_record();
        $title = trim((string) (optional($meta)->site_title ?? ''));

        if ($title !== '') {
            return $title;
        }

        return site_name_bn();
    }
}

if (! function_exists('site_search_sitelinks')) {
    /**
     * Google Search sitelinks — গুরুত্বপূর্ণ category লিংক (সর্বশেষ + ৫টি category)।
     *
     * @return array<int, array{name: string, url: string}>
     */
    function site_search_sitelinks(): array
    {
        static $resolved = null;

        if ($resolved !== null) {
            return $resolved;
        }

        $definitions = [
            ['label' => 'সর্বশেষ', 'route' => 'latest'],
            ['label' => 'আন্তর্জাতিক', 'names' => ['আন্তর্জাতিক'], 'slug' => 'international'],
            ['label' => 'জাতীয়', 'names' => ['জাতীয়', 'জাতীয়'], 'slug' => 'national'],
            ['label' => 'চট্টগ্রাম', 'names' => ['চট্টগ্রাম'], 'slug' => 'chattogram'],
            ['label' => 'কক্সবাজার', 'names' => ['কক্সবাজার'], 'slug' => 'coxbazar'],
            ['label' => 'খেলাধুলা', 'names' => ['খেলাধুলা', 'খেলা'], 'slug' => 'sports'],
        ];

        $categories = collect();
        try {
            $categories = \App\Models\Category::query()
                ->where('status', 'active')
                ->where('type', 'post')
                ->get(['name', 'slug']);
        } catch (\Throwable) {
            // DB unavailable (e.g. during install)
        }

        $links = [];

        foreach ($definitions as $definition) {
            if (($definition['route'] ?? '') === 'latest') {
                try {
                    $links[] = [
                        'name' => $definition['label'],
                        'url' => route('latest'),
                    ];
                } catch (\Throwable) {
                    // Route not registered yet
                }

                continue;
            }

            $category = null;
            foreach ($definition['names'] ?? [] as $name) {
                $category = $categories->first(fn ($item) => $item->name === $name);
                if ($category) {
                    break;
                }
            }

            if (! $category && ! empty($definition['slug'])) {
                $category = $categories->first(fn ($item) => $item->slug === $definition['slug']);
            }

            if (! $category) {
                continue;
            }

            try {
                $links[] = [
                    'name' => $definition['label'],
                    'url' => route('category.show', $category->slug),
                ];
            } catch (\Throwable) {
                continue;
            }
        }

        $resolved = $links;

        return $resolved;
    }
}

if (! function_exists('site_structured_data_json')) {
    /**
     * Google Search site name — WebSite + NewsMediaOrganization JSON-LD (বাংলা নাম)।
     */
    function site_structured_data_json(): string
    {
        $meta = site_meta_record();
        $siteUrl = front_home_url();
        $name = site_name_bn();
        $englishName = trim((string) (optional($meta)->site_name ?? ''));
        $alternateNames = array_values(array_unique(array_filter([
            $englishName !== '' && $englishName !== $name ? $englishName : null,
            ($domain = site_domain()) !== '' && $domain !== $name ? $domain : null,
        ])));

        $website = [
            '@type' => 'WebSite',
            '@id' => $siteUrl . '#website',
            'url' => $siteUrl,
            'name' => $name,
            'publisher' => ['@id' => $siteUrl . '#organization'],
        ];

        if ($alternateNames !== []) {
            $website['alternateName'] = $alternateNames;
        }

        $sitelinks = site_search_sitelinks();
        if ($sitelinks !== []) {
            $website['hasPart'] = array_map(
                static fn (int $index): array => ['@id' => $siteUrl . '#nav-' . ($index + 1)],
                array_keys($sitelinks),
            );
        }

        $organization = [
            '@type' => 'NewsMediaOrganization',
            '@id' => $siteUrl . '#organization',
            'name' => $name,
            'url' => $siteUrl,
        ];

        $logo = trim((string) (optional($meta)->site_logo ?? ''));
        if ($logo !== '') {
            $organization['logo'] = [
                '@type' => 'ImageObject',
                'url' => str_replace('http://', 'https://', storage_image_url($logo)),
            ];
        }

        $graph = [$website, $organization];

        foreach ($sitelinks as $index => $link) {
            $graph[] = [
                '@type' => 'SiteNavigationElement',
                '@id' => $siteUrl . '#nav-' . ($index + 1),
                'name' => $link['name'],
                'url' => $link['url'],
                'isPartOf' => ['@id' => $siteUrl . '#website'],
            ];
        }

        if ($sitelinks !== []) {
            $graph[] = [
                '@type' => 'ItemList',
                '@id' => $siteUrl . '#primary-nav',
                'name' => 'মূল বিভাগ',
                'itemListElement' => array_map(
                    static fn (int $index, array $link): array => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $link['name'],
                        'url' => $link['url'],
                    ],
                    array_keys($sitelinks),
                    $sitelinks,
                ),
            ];
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}

if (! function_exists('share_site_label')) {
    /**
     * Share preview-তে title-এর নিচে site domain দেখানো (article excerpt নয়)।
     */
    function share_site_label(?string $pageUrl = null): string
    {
        $host = $pageUrl ? parse_url($pageUrl, PHP_URL_HOST) : null;
        if (is_string($host) && $host !== '') {
            return $host;
        }

        $appHost = parse_url(config('app.url', url('/')), PHP_URL_HOST);
        if (is_string($appHost) && $appHost !== '') {
            return $appHost;
        }

        return '';
    }
}

if (! function_exists('photocard_site_domain')) {
    /**
     * Photocard footer-এ domain। $withWww=true হলে www.example.com, false হলে example.com।
     */
    function photocard_site_domain(?string $pageUrl = null, bool $withWww = true): string
    {
        $url = $pageUrl ?? front_home_url();
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            $host = parse_url((string) config('app.url', ''), PHP_URL_HOST);
        }

        if (! is_string($host) || $host === '') {
            return '';
        }

        $host = strtolower($host);
        $host = preg_replace('/^www\./i', '', $host) ?? $host;

        if ($host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) {
            return $host;
        }

        if ($withWww) {
            $host = 'www.' . $host;
        }

        return $host;
    }
}

if (! function_exists('news_url')) {
    /**
     * Build simple news post URL (/{slug}).
     */
    function news_url($post): string
    {
        if (! $post || ! $post->slug) {
            return url('/');
        }

        return route('news.show', [$post->slug]);
    }
}

if (! function_exists('news_photo_url')) {
    /**
     * Full-page featured image URL (/{slug}/photo).
     */
    function news_photo_url($post): string
    {
        if (! $post || ! $post->slug || ! $post->image) {
            return news_url($post);
        }

        return route('news.photo', [$post->slug]);
    }
}

if (! function_exists('news_whatsapp_share_url')) {
    /**
     * WhatsApp শেয়ার লিংক — পোস্ট ID (/{id}), slug নয়।
     */
    function news_whatsapp_share_url($post): string
    {
        if (! $post || ! $post->id) {
            return url('/');
        }

        return url('/' . $post->id);
    }
}

if (! function_exists('person_name_first_word')) {
    function person_name_first_word(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }

        $parts = preg_split('/\s+/u', $name, 2);

        return $parts[0] ?: null;
    }
}

if (! function_exists('person_name_first_two_words')) {
    function person_name_first_two_words(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }

        $parts = preg_split('/\s+/u', $name);
        $words = array_slice($parts, 0, 2);

        return $words !== [] ? implode(' ', $words) : null;
    }
}

if (! function_exists('reporter_display_label')) {
    /**
     * পোস্ট/ভিডিও/গ্যালারিতে রিপোর্টার লেবেল — ডেস্ক/ধরন প্রথমে, নাম পরে (লাইভ reporters রেকর্ড থেকে)।
     */
    function reporter_display_label($reporter, ?string $fallback = null): string
    {
        if (! $reporter) {
            return $fallback ?? '';
        }

        $desk = trim((string) ($reporter->desk ?? ''));
        if ($desk !== '') {
            return $desk;
        }

        $name = reporter_person_name($reporter);
        if ($name !== null && $name !== '') {
            return $name;
        }

        return $fallback ?? '';
    }
}

if (! function_exists('reporter_person_name')) {
    /**
     * রিপোর্টার ধরন/ডেস্ক নয় — রিপোর্টার রেকর্ডের ব্যক্তির নাম (name ফিল্ড)।
     */
    function reporter_person_name($reporter): ?string
    {
        if (! $reporter) {
            return null;
        }

        if ($reporter->relationLoaded('subEditor') || $reporter->sub_editor_id) {
            $name = trim((string) optional($reporter->subEditor)->name);
            if ($name !== '') {
                return $name;
            }
        }

        $name = trim((string) ($reporter->name ?? ''));

        return $name !== '' ? $name : null;
    }
}

if (! function_exists('latin_initial_to_bangla')) {
    function latin_initial_to_bangla(string $letter): string
    {
        return match (mb_strtoupper($letter, 'UTF-8')) {
            'A' => 'এ',
            'B' => 'বি',
            'C' => 'সি',
            'D' => 'ডি',
            'E' => 'ই',
            'F' => 'এফ',
            'G' => 'জি',
            'H' => 'এইচ',
            'I' => 'আই',
            'J' => 'জে',
            'K' => 'কে',
            'L' => 'এল',
            'M' => 'এম',
            'N' => 'এন',
            'O' => 'ও',
            'P' => 'পি',
            'Q' => 'কью',
            'R' => 'আর',
            'S' => 'এস',
            'T' => 'টি',
            'U' => 'ইউ',
            'V' => 'ভি',
            'W' => 'ডব্লিউ',
            'X' => 'এক্স',
            'Y' => 'ওয়াই',
            'Z' => 'জিড',
            default => $letter,
        };
    }
}

if (! function_exists('person_name_first_letter')) {
    function person_name_first_letter(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }

        $first = mb_substr($name, 0, 1, 'UTF-8');

        if (preg_match('/^[A-Za-z]$/', $first)) {
            return latin_initial_to_bangla($first);
        }

        return $first;
    }
}

if (! function_exists('site_name_bn')) {
    /** Site metadata থেকে বাংলা সাইট নাম (না থাকলে site_name)। */
    function site_name_bn(): string
    {
        $meta = site_meta_record();
        $name = trim((string) (optional($meta)->site_name_bn ?? ''));

        if ($name !== '') {
            return $name;
        }

        return site_name();
    }
}

if (! function_exists('site_domain')) {
    /** সাইট ডোমেইন — news24bd.tv (www ছাড়া)। */
    function site_domain(): string
    {
        $host = share_site_label();
        if ($host === '') {
            return '';
        }

        return preg_replace('/^www\./i', '', $host) ?? $host;
    }
}

if (! function_exists('post_credit_line')) {
    /**
     * সাইট নাম (বাংলা) + পোস্টকারীর নামের প্রথম শব্দ — যেমন: কক্সবাজার কণ্ঠ / Md
     */
    function post_credit_line($post): string
    {
        $creatorWord = person_name_first_word(optional($post->creator)->name);
        if ($creatorWord === null || $creatorWord === '') {
            return '';
        }

        $label = site_name_bn();

        return $label !== '' ? $label . ' / ' . $creatorWord : $creatorWord;
    }
}

if (! function_exists('site_role_label')) {
    function site_role_label(string $role, $siteMeta = null): string
    {
        $meta = $siteMeta ?? site_meta_record();
        $defaults = [
            'editor' => 'সম্পাদক',
            'publisher' => 'প্রকাশক',
        ];

        $field = $role === 'publisher' ? 'publisher_label' : 'editor_label';
        $label = trim((string) optional($meta)->{$field});

        if ($label !== '') {
            return $label;
        }

        return $defaults[$role] ?? '';
    }
}

if (! function_exists('site_editor_publisher_lines')) {
    /**
     * সম্পাদক/প্রকাশক ফুটার লাইন — যেটা পূরণ আছে শুধু সেটাই দেখাবে।
     *
     * @return array<int, array{label: string, name: string}>
     */
    function site_editor_publisher_lines($siteMeta = null): array
    {
        $meta = $siteMeta ?? site_meta_record();
        $lines = [];

        $editorName = trim((string) optional($meta)->editor_name);
        if ($editorName !== '') {
            $lines[] = ['label' => site_role_label('editor', $meta), 'name' => $editorName];
        }

        $publisherName = trim((string) optional($meta)->publisher_name);
        if ($publisherName !== '') {
            $lines[] = ['label' => site_role_label('publisher', $meta), 'name' => $publisherName];
        }

        return $lines;
    }
}

if (! function_exists('bangladesh_division_districts_map')) {
    /**
     * বিভাগ অনুযায়ী জেলার তালিকা (বাংলা নাম কী হিসেবে)।
     *
     * @return array<string, list<string>>
     */
    function bangladesh_division_districts_map(): array
    {
        return [
            'ঢাকা' => [
                'ঢাকা',
                'ফরিদপুর',
                'গাজীপুর',
                'গোপালগঞ্জ',
                'কিশোরগঞ্জ',
                'মাদারীপুর',
                'মানিকগঞ্জ',
                'মুন্সিগঞ্জ',
                'নারায়ণগঞ্জ',
                'নরসিংদী',
                'রাজবারী',
                'শরীয়তপুর',
                'টাঙ্গাইল',
            ],
            'চট্টগ্রাম' => [
                'বান্দরবান',
                'ব্রাহ্মণবাড়িয়া',
                'চাঁদপুর',
                'চট্টগ্রাম',
                'কুমিল্লা',
                'কক্সবাজার',
                'ফেনী',
                'খাগড়াছড়ি',
                'লক্ষ্মীপুর',
                'নোয়াখালী',
                'রাঙ্গামাটি',
            ],
            'রাজশাহী' => [
                'বগুড়া',
                'চাঁপাইনবাবগঞ্জ',
                'জয়পুরহাট',
                'নওগাঁ',
                'নাটোর',
                'পাবনা',
                'রাজশাহী',
                'সিরাজগঞ্জ',
            ],
            'খুলনা' => [
                'বাগেরহাট',
                'চুয়াডাঙ্গা',
                'যশোর',
                'ঝিনাইদহ',
                'খুলনা',
                'কুষ্টিয়া',
                'মাগুরা',
                'মেহেরপুর',
                'নড়াইল',
                'সাতক্ষীরা',
            ],
            'বরিশাল' => [
                'বরগুনা',
                'বরিশাল',
                'ভোলা',
                'ঝালকাঠি',
                'পটুয়াখালী',
                'পিরোজপুর',
            ],
            'সিলেট' => [
                'হবিগঞ্জ',
                'মৌলভীবাজার',
                'সুনামগঞ্জ',
                'সিলেট',
            ],
            'রংপুর' => [
                'দিনাজপুর',
                'গাইবান্ধা',
                'কুড়িগ্রাম',
                'লালমনিরহাট',
                'নীলফামারী',
                'পঞ্চগড়',
                'রংপুর',
                'ঠাকুরগাঁও',
            ],
            'ময়মনসিংহ' => [
                'জামালপুর',
                'ময়মনসিংহ',
                'নেত্রকোণা',
                'শেরপুর',
            ],
        ];
    }
}

if (! function_exists('bangladesh_districts_for_division')) {
    /**
     * নির্দিষ্ট বিভাগের জেলা (বাংলা বর্ণানুক্রমিক)।
     *
     * @return list<string>
     */
    function bangladesh_districts_for_division(?string $division): array
    {
        if ($division === null || $division === '') {
            return [];
        }

        $districts = bangladesh_division_districts_map()[$division] ?? [];

        if (class_exists(\Collator::class)) {
            $collator = new \Collator('bn_BD');
            $collator->sort($districts);
        } else {
            sort($districts, SORT_STRING);
        }

        return $districts;
    }
}

if (! function_exists('bangladesh_districts')) {
    /**
     * বাংলাদেশের ৬৪টি জেলার তালিকা (বাংলায়, বর্ণানুক্রমিক)।
     *
     * @return list<string>
     */
    function bangladesh_districts(): array
    {
        $districts = array_merge(...array_values(bangladesh_division_districts_map()));

        if (class_exists(\Collator::class)) {
            $collator = new \Collator('bn_BD');
            $collator->sort($districts);
        } else {
            sort($districts, SORT_STRING);
        }

        return $districts;
    }
}

if (! function_exists('bangladesh_district_upazilas_map')) {
    /**
     * জেলা অনুযায়ী উপজেলার তালিকা (বাংলা নাম কী হিসেবে)।
     *
     * @return array<string, list<string>>
     */
    function bangladesh_district_upazilas_map(): array
    {
        static $map = null;

        if ($map !== null) {
            return $map;
        }

        $path = config_path('bangladesh_district_upazilas.json');
        $decoded = is_readable($path)
            ? json_decode((string) file_get_contents($path), true)
            : null;

        $map = is_array($decoded) ? $decoded : [];

        return $map;
    }
}

if (! function_exists('bangladesh_upazilas_for_district')) {
    /**
     * নির্দিষ্ট জেলার উপজেলা (বাংলা বর্ণানুক্রমিক)।
     *
     * @return list<string>
     */
    function bangladesh_upazilas_for_district(?string $district): array
    {
        if ($district === null || $district === '') {
            return [];
        }

        $map = bangladesh_district_upazilas_map();

        if (isset($map[$district])) {
            $upazilas = $map[$district];
        } else {
            $target = bangladesh_normalize_location_name($district);
            $upazilas = [];

            foreach ($map as $key => $list) {
                if (bangladesh_normalize_location_name($key) === $target) {
                    $upazilas = $list;
                    break;
                }
            }
        }

        if ($upazilas === []) {
            return [];
        }

        $upazilas = array_values($upazilas);

        if (class_exists(\Collator::class)) {
            $collator = new \Collator('bn_BD');
            $collator->sort($upazilas);
        } else {
            sort($upazilas, SORT_STRING);
        }

        return $upazilas;
    }
}

if (! function_exists('bangladesh_regional_search_location_names')) {
    /**
     * এলাকার খবর সার্চ: বিভাগ/জেলা/উপজেলা অনুযায়ী মিলতে হবে এমন সব লোকেশন ট্যাগের নাম।
     *
     * @return list<string>
     */
    function bangladesh_regional_search_location_names(string $division, ?string $district = null, ?string $upazila = null): array
    {
        if ($upazila !== null && $upazila !== '') {
            return [$upazila];
        }

        if ($district !== null && $district !== '') {
            return array_values(array_unique(array_merge([$district], bangladesh_upazilas_for_district($district))));
        }

        $names = [$division];

        foreach (bangladesh_districts_for_division($division) as $districtName) {
            $names[] = $districtName;
            $names = array_merge($names, bangladesh_upazilas_for_district($districtName));
        }

        return array_values(array_unique($names));
    }
}

if (! function_exists('bangladesh_regional_search_label')) {
    /**
     * এলাকার খবর সার্চ রেজাল্ট পেজের শিরোনাম।
     */
    function bangladesh_regional_search_label(string $division, ?string $district = null, ?string $upazila = null): string
    {
        if ($upazila !== null && $upazila !== '') {
            return $upazila;
        }

        if ($district !== null && $district !== '') {
            return $district;
        }

        return $division . ' বিভাগ';
    }
}

if (! function_exists('bangladesh_normalize_location_name')) {
    /**
     * লোকেশন নাম তুলনার জন্য স্বাভাবিকীকরণ (বাংলা ইউনিকোড পার্থক্য সামলাতে)।
     */
    function bangladesh_normalize_location_name(string $name): string
    {
        $name = trim($name);

        if (class_exists(\Normalizer::class)) {
            $name = \Normalizer::normalize($name, \Normalizer::FORM_KD) ?: $name;
            $name = preg_replace('/\p{Mn}/u', '', $name) ?? $name;
        }

        $name = str_replace(['ড়', 'ড়', 'য়', 'য়'], ['র', 'র', 'য', 'য'], $name);

        return mb_strtolower($name, 'UTF-8');
    }
}

if (! function_exists('bangladesh_topic_ids_for_location_names')) {
    /**
     * লোকেশন নাম অনুযায়ী topic id (ইউনিকোড-নিরাপদ মিল)।
     *
     * @param  list<string>  $names
     * @return list<int>
     */
    function bangladesh_topic_ids_for_location_names(array $names): array
    {
        if ($names === []) {
            return [];
        }

        $targets = [];
        foreach ($names as $name) {
            $targets[bangladesh_normalize_location_name($name)] = true;
        }

        $ids = [];
        foreach (\App\Models\Topic::query()->get(['id', 'name']) as $topic) {
            if (isset($targets[bangladesh_normalize_location_name($topic->name)])) {
                $ids[] = (int) $topic->id;
            }
        }

        return array_values(array_unique($ids));
    }
}
