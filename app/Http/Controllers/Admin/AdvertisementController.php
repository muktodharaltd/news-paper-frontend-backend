<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AdvertisementQueueItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function index(): View
    {
        Advertisement::archiveAllExpiredSlots();

        $advertisements = Advertisement::orderBy('slug')->get();
        $googleClientConfigured = filled(google_adsense_client());
        $googleSlotCount = Advertisement::query()
            ->where('google_ad_auto', true)
            ->whereNotNull('google_ad_slot')
            ->where('google_ad_slot', '!=', '')
            ->count();

        $duplicateGoogleSlotIds = Advertisement::query()
            ->where('slug', '!=', 'home_video')
            ->whereNotNull('google_ad_slot')
            ->where('google_ad_slot', '!=', '')
            ->select('google_ad_slot')
            ->selectRaw('COUNT(*) as slot_count')
            ->groupBy('google_ad_slot')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('google_ad_slot');

        return view('admin.advertisements.index', compact(
            'advertisements',
            'googleClientConfigured',
            'googleSlotCount',
            'duplicateGoogleSlotIds',
        ));
    }

    public function edit(int $id): View
    {
        $advertisement = Advertisement::findOrFail($id);

        Advertisement::archiveExpiredSlotForId((int) $advertisement->id);
        $advertisement->refresh();

        if (! $advertisement->isWithinSlotScheduleWindow()
            && ((int) ($advertisement->views_count ?? 0) > 0 || (int) ($advertisement->clicks_count ?? 0) > 0)) {
            $advertisement->update(['views_count' => 0, 'clicks_count' => 0]);
            $advertisement->refresh();
        }

        AdvertisementQueueItem::reconcileExpiredForAdvertisementId((int) $advertisement->id);

        $queueItems = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisement->id)
            ->whereNull('expired_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $expiredAds = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisement->id)
            ->whereNotNull('expired_at')
            ->orderByDesc('expired_at')
            ->orderByDesc('id')
            ->get();

        $slotFormDisplay = ($advertisement->isWithinSlotScheduleWindow() || $advertisement->hasPausedLocalAd())
            ? $advertisement
            : null;

        $liveQueueItem = $advertisement->findQueueItemForFrontDisplayMerge();

        return view('admin.advertisements.edit', compact(
            'advertisement',
            'slotFormDisplay',
            'queueItems',
            'expiredAds',
            'liveQueueItem',
        ));
    }

    public function toggleAdSource(int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->slug === 'home_video') {
            return redirect()->back()
                ->withErrors(['google_ad_auto' => 'হোম ভিডিও স্লটে Google Ad ব্যবহার করা যায় না।']);
        }

        $newAuto = ! $advertisement->googleAdAutoEnabled();

        if ($newAuto && ! filled($advertisement->google_ad_slot)) {
            return redirect()
                ->route('admin.advertisements.edit', $advertisement->id)
                ->withErrors(['google_ad_slot' => 'Auto চালু করতে Google Slot ID দিন।']);
        }

        if ($newAuto && ! filled(google_adsense_client())) {
            return redirect()->back()
                ->withErrors(['google_ad_auto' => 'আগে SEO & Meta সেটিংসে Google AdSense Client ID সেট করুন।']);
        }

        $advertisement->update(['google_ad_auto' => $newAuto]);

        $message = $newAuto
            ? 'Local ad না থাকলে এখন Google Ad auto চলবে।'
            : 'Google Ad auto বন্ধ — শুধু Local ad চলবে।';

        return redirect()->back()->with('success', $message);
    }

    public function updateGoogleSettings(Request $request, int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->slug === 'home_video') {
            return redirect()->back()
                ->withErrors(['google_ad_auto' => 'হোম ভিডিও স্লটে Google Ad ব্যবহার করা যায় না।']);
        }

        $slotId = preg_replace('/\D+/', '', trim((string) $request->input('google_ad_slot', '')));
        $googleAdAuto = $request->boolean('google_ad_auto');

        if ($googleAdAuto && $slotId === '') {
            return redirect()->back()
                ->withErrors(['google_ad_slot' => 'Auto চালু করতে Google Slot ID দিন।'])
                ->withInput();
        }

        if ($googleAdAuto && ! filled(google_adsense_client())) {
            return redirect()->back()
                ->withErrors(['google_ad_auto' => 'আগে SEO & Meta সেটিংসে Google AdSense Client ID সেট করুন।'])
                ->withInput();
        }

        $request->merge(['google_ad_slot' => $slotId]);

        $request->validate([
            'google_ad_auto' => 'nullable|boolean',
            'google_ad_slot' => 'nullable|string|max:32|regex:/^\d*$/',
        ]);

        $advertisement->update([
            'google_ad_slot' => $slotId !== '' ? $slotId : null,
            // Slot ID save = Google fallback চালু (Auto checkbox ভুলে uncheck থাকলেও)
            'google_ad_auto' => $slotId !== '' ? true : false,
        ]);

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'Google Ad সেটিংস সংরক্ষণ করা হয়েছে।');
    }

    public function updateBulkGoogleSlots(Request $request): RedirectResponse
    {
        if (! filled(google_adsense_client())) {
            return redirect()->back()
                ->withErrors(['google_slots' => 'আগে SEO & Meta সেটিংসে Google AdSense Client ID সেট করুন।']);
        }

        $request->validate([
            'google_slots' => 'required|array',
            'google_slots.*' => 'nullable|string|max:32|regex:/^\d*$/',
        ]);

        $updated = 0;

        foreach ($request->input('google_slots', []) as $id => $rawSlot) {
            $advertisement = Advertisement::find((int) $id);
            if (! $advertisement || $advertisement->slug === 'home_video') {
                continue;
            }

            $slotId = preg_replace('/\D+/', '', trim((string) $rawSlot));

            $advertisement->update([
                'google_ad_slot' => $slotId !== '' ? $slotId : null,
                'google_ad_auto' => $slotId !== '',
            ]);

            if ($slotId !== '') {
                $updated++;
            }
        }

        return redirect()
            ->route('admin.advertisements.index')
            ->with('success', "Google Slot ID সংরক্ষণ হয়েছে ({$updated} slot)। প্রতিটি slot-এ আলাদা ID দিন — এক পেজে সব ad দেখাবে।");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        $hasGoogleFallback = $advertisement->googleAdAutoEnabled() && filled($advertisement->google_ad_slot);
        $requireMedia = ! $hasGoogleFallback;

        $request->validate(array_merge($this->mediaValidationRules($requireMedia), [
            'slot_auto' => 'nullable|in:0,1',
            'slot_duration_days' => 'nullable|integer|min:0|max:365',
            'slot_duration_hours' => 'nullable|integer|min:0|max:23',
        ]));

        $data = [
            'link' => $request->input('link'),
            'caption' => $request->input('caption'),
            'ad_source' => 'local',
        ];

        if ($mediaError = $this->applyMediaFromRequest($request, $data, $advertisement, requireMedia: $requireMedia)) {
            return $mediaError;
        }

        $isAuto = $request->input('slot_auto', '0') === '1';

        if ($isAuto) {
            $data['is_auto'] = true;
            $data['starts_at'] = ($advertisement->is_auto && $advertisement->starts_at)
                ? $advertisement->starts_at
                : now();
            $data['ends_at'] = null;
        } else {
            $days = min(365, max(0, (int) $request->input('slot_duration_days', 0)));
            $hours = min(23, max(0, (int) $request->input('slot_duration_hours', 0)));

            if ($days + $hours === 0) {
                return redirect()->back()
                    ->withErrors(['slot_duration_hours' => 'Auto বন্ধ থাকলে কমপক্ষে ১ ঘণ্টা বা ১ দিন মেয়াদ দিন।'])
                    ->withInput();
            }

            $data['is_auto'] = false;
            $data['starts_at'] = now();
            $data['ends_at'] = now()->copy()->addDays($days)->addHours($hours);
        }

        $data['ad_source'] = 'local';
        $data['local_ad_paused'] = false;
        $data['local_ad_paused_remaining_seconds'] = null;

        $advertisement->update($data);

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'Local অ্যাড সংরক্ষণ করা হয়েছে।');
    }

    public function pauseLocalAd(int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->isLocalAdPaused()) {
            return redirect()
                ->route('admin.advertisements.edit', $advertisement->id)
                ->with('success', 'Local ad ইতিমধ্যে বন্ধ আছে।');
        }

        if (! $advertisement->hasRunningLocalAd()) {
            return redirect()
                ->route('admin.advertisements.edit', $advertisement->id)
                ->withErrors(['media_type' => 'চালু Local ad নেই — বন্ধ করার মতো কিছু নেই।']);
        }

        $remaining = null;
        if (! $advertisement->is_auto && $advertisement->ends_at && $advertisement->ends_at->gt(now())) {
            $remaining = max(1, (int) now()->diffInSeconds($advertisement->ends_at));
        }

        $advertisement->update([
            'local_ad_paused' => true,
            'local_ad_paused_remaining_seconds' => $remaining,
        ]);

        $message = 'Local ad বন্ধ করা হয়েছে — মিডিয়া ও মেয়াদ সংরক্ষিত আছে।';
        if ($advertisement->googleAdAutoEnabled() && filled($advertisement->google_ad_slot)) {
            $message .= ' Google fallback এখন চালু হবে।';
        }

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', $message);
    }

    public function resumeLocalAd(int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        if (! $advertisement->hasPausedLocalAd()) {
            return redirect()
                ->route('admin.advertisements.edit', $advertisement->id)
                ->withErrors(['media_type' => 'বন্ধ Local ad নেই — চালু করার মতো কিছু নেই।']);
        }

        $data = [
            'local_ad_paused' => false,
            'local_ad_paused_remaining_seconds' => null,
        ];

        $remaining = (int) ($advertisement->local_ad_paused_remaining_seconds ?? 0);
        if (! $advertisement->is_auto && $remaining > 0) {
            $data['starts_at'] = now();
            $data['ends_at'] = now()->copy()->addSeconds($remaining);
        }

        $advertisement->update($data);

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'Local ad আবার চালু হয়েছে — বাকি মেয়াদ থেকে টাইমার চলবে।');
    }

    public function deleteLocalAd(int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        if (! $advertisement->hasDisplayableMedia() && ! $advertisement->isLocalAdPaused() && ! filled($advertisement->link)) {
            return redirect()
                ->route('admin.advertisements.edit', $advertisement->id)
                ->with('success', 'মুছার মতো Local ad ডেটা নেই।');
        }

        $hasQueue = $advertisement->hasActiveQueueItems();
        $hasGoogleFallback = $advertisement->googleAdAutoEnabled() && filled($advertisement->google_ad_slot);

        $this->deleteAllMediaPaths($advertisement);

        $now = now();
        $advertisement->update([
            'image' => null,
            'image_mobile' => null,
            'video' => null,
            'video_mobile' => null,
            'video_youtube_id' => null,
            'link' => null,
            'caption' => null,
            'is_auto' => false,
            'local_ad_paused' => false,
            'local_ad_paused_remaining_seconds' => null,
            'starts_at' => $now,
            'ends_at' => $now,
        ]);

        $message = 'Local ad সম্পূর্ণ মুছে ফেলা হয়েছে।';

        if ($hasQueue) {
            $message .= ' কিউতে ad থাকলে সেটা এখনও চলতে পারে।';
        } elseif ($hasGoogleFallback) {
            $message .= ' Google fallback চালু হবে।';
        }

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', $message);
    }

    public function storeQueueItem(Request $request, int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate(array_merge($this->mediaValidationRules(), [
            'title' => 'nullable|string|max:255',
            'duration_days' => 'required|integer|min:0|max:365',
            'duration_hours' => 'required|integer|min:0|max:23',
        ]));
        if (((int) $request->input('duration_days', 0)) + ((int) $request->input('duration_hours', 0)) === 0) {
            return redirect()->back()
                ->withErrors(['duration_hours' => 'কমপক্ষে ১ ঘণ্টা বা ১ দিন মেয়াদ দিন।'])
                ->withInput();
        }

        $data = [
            'advertisement_id' => $advertisement->id,
            'title' => $request->input('title'),
            'link' => $request->input('link'),
            'caption' => $request->input('caption'),
        ];

        if ($mediaError = $this->applyMediaFromRequest($request, $data, null, requireMedia: true)) {
            return $mediaError;
        }

        $data = array_merge($data, $this->queueItemDurationFromRequest($request));

        // নতুন আইটেম কিউর শীর্ষে — ফ্রন্টে `applyQueueItemDisplayOverride` sort অনুযায়ী প্রথমটি মিলায়; শেষে যোগ করলে পুরনো সারি সবসময় আগে থাকত।
        DB::transaction(function () use ($advertisement, $data): void {
            AdvertisementQueueItem::query()
                ->where('advertisement_id', $advertisement->id)
                ->whereNull('expired_at')
                ->increment('sort_order');

            $data['sort_order'] = 1;
            AdvertisementQueueItem::query()->create($data);
        });

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'কিউতে নতুন অ্যাড যোগ করা হয়েছে (সবার উপরে — ফ্রন্টে এটিই আগে দেখাবে)।');
    }

    public function updateQueueItem(Request $request, int $id, int $itemId): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        $item = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisement->id)
            ->whereKey($itemId)
            ->firstOrFail();

        $request->validate(array_merge($this->mediaValidationRules(), [
            'title' => 'nullable|string|max:255',
            'duration_days' => 'required|integer|min:0|max:365',
            'duration_hours' => 'required|integer|min:0|max:23',
        ]));
        if (((int) $request->input('duration_days', 0)) + ((int) $request->input('duration_hours', 0)) === 0) {
            return redirect()->back()
                ->withErrors(['duration_hours' => 'কমপক্ষে ১ ঘণ্টা বা ১ দিন মেয়াদ দিন।'])
                ->withInput();
        }

        $data = [
            'title' => $request->input('title'),
            'link' => $request->input('link'),
            'caption' => $request->input('caption'),
        ];

        if ($mediaError = $this->applyMediaFromRequest($request, $data, $item, requireMedia: true)) {
            return $mediaError;
        }

        $data = array_merge($data, $this->queueItemDurationFromRequest($request));

        $item->update($data);

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'কিউ আইটেম আপডেট করা হয়েছে।');
    }

    public function destroyQueueItem(int $id, int $itemId): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        $item = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisement->id)
            ->whereKey($itemId)
            ->firstOrFail();

        $this->deleteAllMediaPaths($item);
        $item->delete();

        $this->renumberQueueSortOrder($advertisement->id);

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'কিউ আইটেম মুছে ফেলা হয়েছে।');
    }

    public function reorderQueueItems(Request $request, int $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:advertisement_queue_items,id',
        ]);

        /** @var array<int, int> $order */
        $order = array_values(array_map('intval', $request->input('order', [])));
        $expected = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisement->id)
            ->whereNull('expired_at')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn (int|string $v) => (int) $v)
            ->sort()
            ->values()
            ->all();
        $sortedSubmitted = $order;
        sort($sortedSubmitted);

        if ($sortedSubmitted !== $expected || count($order) !== count($expected)) {
            return redirect()->back()->withErrors(['order' => 'অবৈধ ক্রম।']);
        }

        foreach ($order as $index => $itemId) {
            AdvertisementQueueItem::query()->whereKey($itemId)->update(['sort_order' => $index]);
        }

        return redirect()
            ->route('admin.advertisements.edit', $advertisement->id)
            ->with('success', 'কিউ ক্রম আপডেট করা হয়েছে।');
    }

    /**
     * @return array<string, string>
     */
    protected function mediaValidationRules(bool $requireMedia = true): array
    {
        return [
            'media_type' => ($requireMedia ? 'required' : 'nullable').'|in:image,video,youtube',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video' => 'nullable|file|mimes:mp4,webm,mov,ogv,quicktime|max:51200',
            'video_mobile' => 'nullable|file|mimes:mp4,webm,mov,ogv,quicktime|max:51200',
            'link' => ($requireMedia ? 'required' : 'nullable').'|url|max:500',
            'caption' => 'nullable|string|max:500',
            'video_youtube_id' => 'nullable|string|max:500',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function applyMediaFromRequest(Request $request, array &$data, Advertisement|AdvertisementQueueItem|null $existing, bool $requireMedia): ?RedirectResponse
    {
        $type = $request->input('media_type', $existing?->resolvedMediaType() ?? 'image');

        if ($type === 'youtube') {
            $videoId = $this->normalizeYoutubeId($request->input('video_youtube_id'));
            if (! filled($videoId)) {
                return redirect()->back()
                    ->withErrors(['video_youtube_id' => 'YouTube ভিডিও ID বা URL দিন।'])
                    ->withInput();
            }
            if ($existing) {
                $this->clearImageMedia($existing, $data);
                $this->clearVideoMedia($existing, $data);
            } else {
                $data['image'] = null;
                $data['image_mobile'] = null;
                $data['video'] = null;
                $data['video_mobile'] = null;
            }
            $data['video_youtube_id'] = $videoId;
            $data['caption'] = $request->input('caption');

            return null;
        }

        if ($type === 'video') {
            $hasNewDesktop = $request->hasFile('video');
            $removingDesktop = $request->boolean('remove_video');
            $finalDesktop = $existing?->video;
            if ($request->hasFile('video')) {
                if ($existing?->video) {
                    delete_uploaded_media($existing->video);
                }
                $data['video'] = store_public_upload($request->file('video'), 'advertisements');
                $finalDesktop = $data['video'];
            } elseif ($removingDesktop && $existing?->video) {
                delete_uploaded_media($existing->video);
                $data['video'] = null;
                $finalDesktop = null;
            }

            if ($request->filled('remove_video_mobile') && $existing?->video_mobile) {
                delete_uploaded_media($existing->video_mobile);
                $data['video_mobile'] = null;
            } elseif ($request->hasFile('video_mobile')) {
                delete_uploaded_media($existing?->video_mobile);
                $data['video_mobile'] = store_public_upload($request->file('video_mobile'), 'advertisements');
            }

            if ($requireMedia && ! filled($finalDesktop) && ! $request->hasFile('video_mobile')) {
                return redirect()->back()
                    ->withErrors(['video' => 'ডেস্কটপ ভিডিও ফাইল আপলোড করুন।'])
                    ->withInput();
            }

            if ($existing) {
                $this->clearImageMedia($existing, $data);
            } else {
                $data['image'] = null;
                $data['image_mobile'] = null;
            }
            $data['video_youtube_id'] = null;
            $data['caption'] = $request->input('caption');

            return null;
        }

        // image / gif
        $hasNewDesktop = $request->hasFile('image');
        $removingDesktop = $request->boolean('remove_image');
        $finalDesktop = $existing?->image;
        if ($request->hasFile('image')) {
            delete_uploaded_media($existing?->image);
            $data['image'] = store_public_upload($request->file('image'), 'advertisements');
            $finalDesktop = $data['image'];
        } elseif ($removingDesktop && $existing?->image) {
            delete_uploaded_media($existing->image);
            $data['image'] = null;
            $finalDesktop = null;
        }

        if ($request->filled('remove_image_mobile') && $existing?->image_mobile) {
            delete_uploaded_media($existing->image_mobile);
            $data['image_mobile'] = null;
        } elseif ($request->hasFile('image_mobile')) {
            delete_uploaded_media($existing?->image_mobile);
            $data['image_mobile'] = store_public_upload($request->file('image_mobile'), 'advertisements');
        }

        if ($requireMedia && ! filled($finalDesktop)) {
            return redirect()->back()
                ->withErrors(['image' => 'ডেস্কটপ ইমেজ বা GIF আপলোড করুন।'])
                ->withInput();
        }

        if ($existing) {
            $this->clearVideoMedia($existing, $data);
        } else {
            $data['video'] = null;
            $data['video_mobile'] = null;
        }
        $data['video_youtube_id'] = null;
        $data['caption'] = $request->input('caption');

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function clearImageMedia(Advertisement|AdvertisementQueueItem $existing, array &$data): void
    {
        if ($existing->image) {
            delete_uploaded_media($existing->image);
        }
        if ($existing->image_mobile) {
            delete_uploaded_media($existing->image_mobile);
        }
        $data['image'] = null;
        $data['image_mobile'] = null;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function clearVideoMedia(Advertisement|AdvertisementQueueItem $existing, array &$data): void
    {
        if ($existing->video) {
            delete_uploaded_media($existing->video);
        }
        if ($existing->video_mobile) {
            delete_uploaded_media($existing->video_mobile);
        }
        $data['video'] = null;
        $data['video_mobile'] = null;
    }

    protected function deleteAllMediaPaths(Advertisement|AdvertisementQueueItem $model): void
    {
        delete_uploaded_media($model->image);
        delete_uploaded_media($model->image_mobile);
        delete_uploaded_media($model->video);
        delete_uploaded_media($model->video_mobile);
    }

    protected function normalizeYoutubeId(mixed $videoRaw): ?string
    {
        if ($videoRaw === null || $videoRaw === '') {
            return null;
        }
        $videoRaw = trim((string) $videoRaw);
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoRaw, $m)) {
            return $m[1];
        }

        return strlen($videoRaw) <= 20 ? $videoRaw : null;
    }

    /**
     * @return array{duration_days: int, duration_hours: int, starts_at: null, ends_at: null}
     */
    protected function queueItemDurationFromRequest(Request $request): array
    {
        $days = min(365, max(0, (int) $request->input('duration_days', 0)));
        $hours = min(23, max(0, (int) $request->input('duration_hours', 0)));

        return [
            'duration_days' => $days,
            'duration_hours' => $hours,
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    protected function renumberQueueSortOrder(int $advertisementId): void
    {
        $ids = AdvertisementQueueItem::query()
            ->where('advertisement_id', $advertisementId)
            ->whereNull('expired_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id');
        foreach ($ids as $index => $itemId) {
            AdvertisementQueueItem::query()->whereKey($itemId)->update(['sort_order' => $index]);
        }
    }
}
