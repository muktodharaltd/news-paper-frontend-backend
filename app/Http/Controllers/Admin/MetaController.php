<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetaController extends Controller
{
    /**
     * Display the meta settings page.
     */
    public function index(): View
    {
        $meta = SiteMeta::first() ?? new SiteMeta;

        return view('admin.meta.index', compact('meta'));
    }

    /**
     * Update the meta settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_name_bn' => ['nullable', 'string', 'max:255'],
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_keywords' => ['nullable', 'string', 'max:500'],
            'site_email' => ['nullable', 'email', 'max:255'],
            'site_number' => ['nullable', 'string', 'max:50'],
            'site_logo' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,webp,svg', 'max:2048'],
            'site_icon' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,ico,webp,svg', 'max:1024'],
            'site_description' => ['nullable', 'string'],
            'primary_color' => ['nullable', 'string', 'max:7'],
            'facebook_link' => ['nullable', 'string', 'max:500'],
            'twitter_link' => ['nullable', 'string', 'max:500'],
            'instagram_link' => ['nullable', 'string', 'max:500'],
            'youtube_link' => ['nullable', 'string', 'max:500'],
            'map_link' => ['nullable', 'string', 'max:500'],
            'map_desc' => ['nullable', 'string', 'max:255'],
            'address_1' => ['nullable', 'string'],
            'editor_name' => ['nullable', 'string', 'max:255'],
            'editor_label' => ['nullable', 'string', 'max:255'],
            'publisher_name' => ['nullable', 'string', 'max:255'],
            'publisher_label' => ['nullable', 'string', 'max:255'],
            'google_adsense_client' => ['nullable', 'string', 'max:64'],
            'google_adsense_default_slot' => ['nullable', 'string', 'max:32'],
            'google_adsense_box_slot' => ['nullable', 'string', 'max:32'],
            'google_adsense_inline_slot' => ['nullable', 'string', 'max:32'],
        ]);

        $meta = SiteMeta::first();

        if ($request->hasFile('site_logo')) {
            if ($meta && $meta->site_logo) {
                delete_uploaded_media($meta->site_logo);
            }
            $validated['site_logo'] = store_public_upload($request->file('site_logo'), 'meta');
        } else {
            unset($validated['site_logo']);
        }

        if ($request->hasFile('site_icon')) {
            if ($meta && $meta->site_icon) {
                delete_uploaded_media($meta->site_icon);
            }
            $validated['site_icon'] = store_public_upload($request->file('site_icon'), 'meta');
        } else {
            unset($validated['site_icon']);
        }

        $validated['extra_social_links'] = [];

        $pc = isset($validated['primary_color']) ? trim((string) $validated['primary_color']) : '';
        if ($pc === '') {
            $validated['primary_color'] = null;
        } elseif (! preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/', $pc)) {
            return redirect()->back()
                ->withErrors(['primary_color' => 'প্রাইমারি রঙ # দিয়ে শুরু করে ৩ বা ৬ অক্ষরের হেক্স দিন (যেমন #2563eb)। খালি রাখলে ডিফল্ট রঙ ব্যবহার হবে।'])
                ->withInput();
        } else {
            $validated['primary_color'] = strtolower($pc);
        }

        $normalizedClient = normalize_google_adsense_client($validated['google_adsense_client'] ?? null);
        if (($validated['google_adsense_client'] ?? '') !== '' && $validated['google_adsense_client'] !== null && $normalizedClient === null) {
            return redirect()->back()
                ->withErrors(['google_adsense_client' => 'Google AdSense Client ID সঠিক ফরম্যাটে দিন (যেমন pub-2602475216171666 বা ca-pub-2602475216171666)।'])
                ->withInput();
        }
        $validated['google_adsense_client'] = $normalizedClient;

        $defaultSlot = normalize_google_adsense_slot($validated['google_adsense_default_slot'] ?? null);
        if (($validated['google_adsense_default_slot'] ?? '') !== '' && $validated['google_adsense_default_slot'] !== null && $defaultSlot === null) {
            return redirect()->back()
                ->withErrors(['google_adsense_default_slot' => 'Default Slot ID শুধু সংখ্যা হতে হবে (যেমন 2436228703)।'])
                ->withInput();
        }
        $validated['google_adsense_default_slot'] = $defaultSlot;

        $boxSlot = normalize_google_adsense_slot($validated['google_adsense_box_slot'] ?? null);
        if (($validated['google_adsense_box_slot'] ?? '') !== '' && $validated['google_adsense_box_slot'] !== null && $boxSlot === null) {
            return redirect()->back()
                ->withErrors(['google_adsense_box_slot' => 'Box Slot ID শুধু সংখ্যা হতে হবে।'])
                ->withInput();
        }
        $validated['google_adsense_box_slot'] = $boxSlot;

        $inlineSlot = normalize_google_adsense_slot($validated['google_adsense_inline_slot'] ?? null);
        if (($validated['google_adsense_inline_slot'] ?? '') !== '' && $validated['google_adsense_inline_slot'] !== null && $inlineSlot === null) {
            return redirect()->back()
                ->withErrors(['google_adsense_inline_slot' => 'Inline Slot ID শুধু সংখ্যা হতে হবে।'])
                ->withInput();
        }
        $validated['google_adsense_inline_slot'] = $inlineSlot;

        if ($meta) {
            $meta->update($validated);
        } else {
            SiteMeta::create($validated);
        }

        if (filled($defaultSlot)) {
            \App\Models\Advertisement::query()
                ->where('slug', '!=', 'home_video')
                ->update(['google_ad_auto' => true]);
        }

        return redirect()->route('admin.meta.index')->with('success', 'Settings updated successfully!');
    }
}
