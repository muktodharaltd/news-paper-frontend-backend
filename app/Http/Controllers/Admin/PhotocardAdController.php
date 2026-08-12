<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PhotocardAd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotocardAdController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('type', 'post')
            ->with([
                'photocardAd',
                'children' => function ($query) {
                    $query->orderBy('name')->with('photocardAd');
                },
            ])
            ->orderBy('name')
            ->get();

        return view('admin.advertisements.photocard-ads', compact('categories'));
    }

    public function update(Request $request, int $categoryId): RedirectResponse
    {
        $category = Category::query()
            ->where('type', 'post')
            ->findOrFail($categoryId);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        $ad = PhotocardAd::firstOrNew(['category_id' => $category->id]);

        if ($ad->image) {
            delete_uploaded_media($ad->image);
        }

        $ad->image = store_public_upload($request->file('image'), 'photocard-ads');
        $ad->save();

        return redirect()
            ->route('admin.advertisements.photocard-ads.index', ['preview' => $category->id])
            ->with('success', $category->name . ' ক্যাটাগরির ফটোকার্ড অ্যাড সেভ হয়েছে।');
    }

    public function destroy(int $categoryId): RedirectResponse
    {
        $category = Category::query()
            ->where('type', 'post')
            ->findOrFail($categoryId);

        $ad = PhotocardAd::where('category_id', $category->id)->first();

        if ($ad) {
            delete_uploaded_media($ad->image);
            $ad->delete();
        }

        return redirect()
            ->route('admin.advertisements.photocard-ads.index')
            ->with('success', $category->name . ' ক্যাটাগরির ফটোকার্ড অ্যাড মুছে ফেলা হয়েছে।');
    }
}
