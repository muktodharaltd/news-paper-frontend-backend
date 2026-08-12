<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\HomeLayoutSection;
use App\Models\Post;
use App\Models\Topic;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * বিশেষ সংবাদ পেজ – is_special_news পোস্টগুলো, নতুন প্রথমে।
     */
    public function specialNews(): View|JsonResponse
    {
        $baseQuery = Post::with(['reporter.subEditor', 'categories.parent'])
            ->where('is_special_news', true)
            ->where('status', 'published')
            ->latest();

        // হিরো: সবসময় সর্বশেষ ৪টি — pagination এখানে নয়
        $heroPosts = (clone $baseQuery)->limit(4)->get();

        $belowQuery = (clone $baseQuery)->whereNotIn('id', $heroPosts->pluck('id'));

        $loadMore = $this->specialNewsLoadMore($belowQuery, 10, 20);

        // হোম পেজের মতোই সর্বশেষ ও পঠিত – সব পোস্ট থেকে, একই ডাটা
        $latestSidebarPosts = Post::with('categories.parent')
            ->where('status', 'published')
            ->whereHas('categories', fn($q) => $q->where('type', 'post'))
            ->latest('created_at')
            ->limit(6)
            ->get();
        $popularSidebarPosts = Post::with('categories.parent')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        if ($loadMore instanceof JsonResponse) {
            return $loadMore;
        }

        return view('special-news', array_merge(
            compact('heroPosts', 'latestSidebarPosts', 'popularSidebarPosts'),
            $loadMore,
        ));
    }

    /**
     * বিশেষ সংবাদ: প্রথমে ১০টি, আরও ক্লিক করলে ২০টি করে (category পেজের মতো)।
     */
    private function specialNewsLoadMore($baseQuery, int $initialCount, int $moreCount): JsonResponse|array
    {
        $total = (clone $baseQuery)->count();
        $morePage = (int) request()->input('more_page', 0);

        if (request()->ajax() && $morePage >= 1) {
            $offset = $initialCount + ($morePage - 1) * $moreCount;
            $posts = (clone $baseQuery)->skip($offset)->take($moreCount)->get();
            $hasMore = $total > $initialCount + $morePage * $moreCount;
            $nextUrl = $hasMore ? $this->specialNewsLoadMoreUrl($morePage + 1) : null;

            return response()->json([
                'html'          => view('frontend.partials.special-news-posts', compact('posts'))->render(),
                'next_page_url' => $nextUrl,
                'has_more'      => $hasMore,
            ]);
        }

        $belowPosts = (clone $baseQuery)->take($initialCount)->get();
        $hasMore = $total > $initialCount;
        $nextPageUrl = $hasMore ? $this->specialNewsLoadMoreUrl(1) : null;

        return compact('belowPosts', 'hasMore', 'nextPageUrl');
    }

    private function specialNewsLoadMoreUrl(int $morePage): string
    {
        return request()->fullUrlWithQuery(['more_page' => $morePage]);
    }

    /**
     * Public homepage.
     */
    public function index(): View
    {
        $slotConfig = config('home_layout.slots', []);

        // Hero layers 1-4
        $heroLayers = [];
        for ($layer = 1; $layer <= 4; $layer++) {
            $key   = "hero_layer_{$layer}";
            $limit = $slotConfig[$key] ?? 1;

            $heroLayers[$key] = Post::query()
                ->with('categories.parent')
                ->where('status', 'published')
                ->where('hero_layer', $layer)
                // Order by publish time, so editing a post doesn't make it "new"
                ->latest('created_at')
                ->limit($limit)
                ->get();
        }

        // Mini section (category-based, using HomeLayoutSection)
        $miniSection = HomeLayoutSection::with(['category.parent', 'category.subCategories'])
            ->where('key', 'section-mini')
            ->first();
        $miniPosts   = collect();

        if ($miniSection && $miniSection->category_id) {
            $miniLimit = $slotConfig['mini_section'] ?? 3;

            $miniPosts = Post::with('categories.parent')
                ->whereHas('categories', function ($q) use ($miniSection) {
                    $q->where('categories.id', $miniSection->category_id);
                })
                ->where('status', 'published')
                ->latest('created_at')
                ->limit($miniLimit)
                ->get();
        }

        // Post-based section keys (ভিডিও ও গ্যালারি আলাদা সিস্টেম, এখানে নাই)
        $postSectionKeys = [
            'section-politics',
            'section-national',
            'section-capital',
            'section-sports',
            'section-countrywide',
            'section-world',
            'section-entertainment',
            'section-lifestyle',
            'section-tech',
            'section-different-eye',
            'section-generation',
            'section-campus',
            'section-job',
            'section-triple-col-1',
            'section-triple-col-2',
            'section-triple-col-3',
            'section-triple-col-4',
            'section-triple-col-5',
            'section-triple-col-6',
        ];

        $sectionKeys = array_merge($postSectionKeys, ['section-video', 'section-gallery']);
        $sections = HomeLayoutSection::with(['category.parent', 'category.subCategories'])
            ->whereIn('key', $sectionKeys)
            ->get()
            ->keyBy('key');

        $sectionPosts = [];

        foreach ($sections as $key => $section) {
            // ভিডিও ও গ্যালারি আলাদা মডেল থেকে লোড হবে, পোস্ট লুপে নাই
            if ($key === 'section-video' || $key === 'section-gallery') {
                $sectionPosts[$key] = collect();
                continue;
            }

            if (! $section->category_id) {
                $sectionPosts[$key] = collect();
                continue;
            }

            $categoryIds = [$section->category_id];
            if ($section->category && $section->category->subCategories->isNotEmpty()) {
                $categoryIds = array_merge(
                    $categoryIds,
                    $section->category->subCategories->pluck('id')->all()
                );
            }

            $limit = $slotConfig[$key] ?? 4;

            $sectionPosts[$key] = Post::with('categories.parent')
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                })
                ->where('status', 'published')
                ->latest('created_at')
                ->limit($limit)
                ->get();
        }

        // section-video: ভিডিও টাইপের ডেটা শুধু Video মডেল থেকে (যে ক্যাটাগরি লেআউটে সিলেক্ট করা)
        $sectionVideos = collect();
        $videoSection = $sections->get('section-video');
        if ($videoSection && $videoSection->category_id) {
            $videoLimit = $slotConfig['section-video'] ?? 5;
            $sectionVideos = Video::with('category')
                ->where('category_id', $videoSection->category_id)
                ->where('status', 'active')
                ->latest('created_at')
                ->limit($videoLimit)
                ->get();
        }

        // section-gallery: গ্যালারি টাইপের ডেটা শুধু Gallery মডেল থেকে (যে ক্যাটাগরি লেআউটে সিলেক্ট করা)
        $sectionGalleries = collect();
        $gallerySection = $sections->get('section-gallery');
        if ($gallerySection && $gallerySection->category_id) {
            $galleryLimit = $slotConfig['section-gallery'] ?? 4;
            $sectionGalleries = Gallery::with('images')
                ->where('category_id', $gallerySection->category_id)
                ->where('status', 'active')
                ->latest('created_at')
                ->limit($galleryLimit)
                ->get();
        }

        // ২ নং সেকশনের শেষ কলাম: সর্বশেষ (লেটেস্ট ৫ পোস্ট, পোস্ট-টাইপ ক্যাটাগরি) ও পঠিত (ভিউ অনুযায়ী টপ ৫)
        $latestSidebarPosts = Post::with('categories.parent')
            ->where('status', 'published')
            ->whereHas('categories', fn($q) => $q->where('type', 'post'))
            ->latest('created_at')
            ->limit(5)
            ->get();
        $popularSidebarPosts = Post::with('categories.parent')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        // Fetch permanent divisions (Topics where can_delete is false/0)
        $divisions = Topic::where('can_delete', false)
            ->orderBy('name', 'asc')
            ->get();

        return view('welcome', [
            'hero_layer_1_posts' => $heroLayers['hero_layer_1'] ?? collect(),
            'hero_layer_2_posts' => $heroLayers['hero_layer_2'] ?? collect(),
            'hero_layer_3_posts' => $heroLayers['hero_layer_3'] ?? collect(),
            'hero_layer_4_posts' => $heroLayers['hero_layer_4'] ?? collect(),
            'miniSection'        => $miniSection,
            'mini_posts'         => $miniPosts,
            'sectionPosts'       => $sectionPosts,
            'sectionVideos'       => $sectionVideos,
            'sectionGalleries'    => $sectionGalleries,
            'layoutSections'      => $sections,
            'latestSidebarPosts'  => $latestSidebarPosts,
            'popularSidebarPosts' => $popularSidebarPosts,
            'divisions'           => $divisions,
        ]);
    }
}
