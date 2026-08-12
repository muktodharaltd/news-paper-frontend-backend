<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Video;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Unified search: Post, Gallery, Video by title/description.
     * Results merged into one list (category-page style), sorted by date.
     */
    public function index(Request $request, $slug = null): View|JsonResponse
    {
        $district = trim((string) $request->get('district', ''));
        $upazila = trim((string) $request->get('upazila', ''));
        $topic = null;
        $isDivisionTopic = false;
        $divisionName = '';

        if ($slug) {
            $topic = Topic::where('slug', $slug)->first();
            $divisionName = $topic ? $topic->name : str_replace('-', ' ', $slug);
            $isDivisionTopic = ($topic && ! $topic->can_delete)
                || array_key_exists($divisionName, bangladesh_division_districts_map());

            if ($isDivisionTopic) {
                $query = bangladesh_regional_search_label($divisionName, $district ?: null, $upazila ?: null);
            } else {
                $query = $topic ? $topic->name : $divisionName;
            }
        } else {
            $query = trim((string) $request->get('q', ''));
        }

        $limit = $isDivisionTopic ? 50 : 20;

        $items = collect();
        $hasMore = false;
        $nextPageUrl = null;

        if ($query !== '' || $slug) {
            $term = '%' . $query . '%';

            $postsQuery = Post::with(['categories.parent', 'topics'])
                ->where('status', 'published');

            if ($slug) {
                if ($isDivisionTopic) {
                    $locationNames = bangladesh_regional_search_location_names(
                        $divisionName,
                        $district ?: null,
                        $upazila ?: null
                    );
                    $topicIds = bangladesh_topic_ids_for_location_names($locationNames);

                    if ($topicIds !== []) {
                        $postsQuery->whereHas('topics', function ($topicQuery) use ($topicIds) {
                            $topicQuery->whereIn('topics.id', $topicIds);
                        });
                    } else {
                        $postsQuery->whereRaw('1 = 0');
                    }
                } else {
                    $postsQuery->whereHas('topics', function ($topicQuery) use ($slug) {
                        $topicQuery->where('slug', $slug);
                    });
                }
            } else {
                $postsQuery->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('seo_keywords', 'like', $term);
                });
            }

            $postsQuery->latest();

            // Topic/tag page: category-style load more (প্রথমে ২০, পরে প্রতি ক্লিকে ২০)
            if ($slug && ! $isDivisionTopic) {
                [$items, $hasMore, $nextPageUrl, $ajax] = $this->topicItemsWithLoadMore($postsQuery, 20, 20);

                if ($ajax instanceof JsonResponse) {
                    return $ajax;
                }
            } else {
                $posts = $postsQuery->limit($limit)->get();
                $items = $this->mapPostsToSearchItems($posts);
            }

            if (!$slug) {
                // Galleries (active)
                $galleries = Gallery::with('images')
                    ->where('status', 'active')
                    ->where(function ($q) use ($term) {
                        $q->where('title', 'like', $term)
                            ->orWhere('description', 'like', $term);
                    })
                    ->latest()
                    ->limit($limit)
                    ->get();

                foreach ($galleries as $gallery) {
                    $firstImage = $gallery->images->first();
                    $imageUrl = $firstImage && $firstImage->image
                        ? storage_image_url($firstImage->image)
                        : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600';
                    $items->push((object) [
                        'type'       => 'gallery',
                        'url'        => route('gallery.show', $gallery->slug),
                        'title'      => $gallery->title,
                        'image'      => $imageUrl,
                        'snippet'    => html_entity_decode(Str::limit(strip_tags((string) $gallery->description), 160)),
                        'created_at' => $gallery->created_at,
                    ]);
                }

                // Videos (active)
                $videos = Video::where('status', 'active')
                    ->where(function ($q) use ($term) {
                        $q->where('title', 'like', $term)
                            ->orWhere('description', 'like', $term);
                    })
                    ->latest()
                    ->limit($limit)
                    ->get();

                foreach ($videos as $video) {
                    $imageUrl = $video->image
                        ? storage_image_url($video->image)
                        : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600';
                    $items->push((object) [
                        'type'       => 'video',
                        'url'        => route('videos.show', $video->slug),
                        'title'      => $video->title,
                        'image'      => $imageUrl,
                        'snippet'    => html_entity_decode(Str::limit(strip_tags((string) $video->description), 160)),
                        'created_at' => $video->created_at,
                    ]);
                }
            }

            if (!$slug || $isDivisionTopic) {
                $items = $items->sortByDesc(fn ($i) => $i->created_at->timestamp)->values();
            }
        }

        return view('frontend.search', compact('query', 'items', 'hasMore', 'nextPageUrl'));
    }

    private function mapPostsToSearchItems($posts)
    {
        return collect($posts)->map(function ($post) {
            $subTitle = $post->sub_title;
            if ($subTitle && is_string($subTitle)) {
                $decoded = json_decode($subTitle, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $subTitle = collect($decoded)
                        ->map(fn($v) => is_string($v) ? html_entity_decode(strip_tags($v)) : $v)
                        ->first(fn($v) => is_string($v) && trim($v) !== '');
                } else {
                    $subTitle = html_entity_decode(strip_tags($subTitle));
                }
            }

            return (object) [
                'type'       => 'post',
                'url'        => news_url($post),
                'title'      => $post->title,
                'image'      => $post->image ? storage_image_url($post->image) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600',
                'snippet'    => $subTitle ?: html_entity_decode(Str::limit(strip_tags((string) $post->description ?? ''), 160)),
                'created_at' => $post->created_at,
            ];
        })->values();
    }

    /**
     * Topic/tag page load more helper.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: bool, 2: ?string, 3: ?JsonResponse}
     */
    private function topicItemsWithLoadMore($baseQuery, int $initialCount, int $moreCount): array
    {
        $total = (clone $baseQuery)->count();
        $morePage = max(0, (int) request()->input('more_page', 0));

        if (request()->ajax() && $morePage >= 1) {
            $offset = $initialCount + ($morePage - 1) * $moreCount;
            $posts = (clone $baseQuery)->skip($offset)->take($moreCount)->get();
            $items = $this->mapPostsToSearchItems($posts);
            $hasMore = $total > $initialCount + $morePage * $moreCount;
            $nextUrl = $hasMore ? $this->loadMoreUrl($morePage + 1) : null;

            return [$items, $hasMore, $nextUrl, response()->json([
                'html'          => view('frontend.partials.search-items', compact('items'))->render(),
                'next_page_url' => $nextUrl,
                'has_more'      => $hasMore,
            ])];
        }

        $posts = (clone $baseQuery)->take($initialCount)->get();
        $items = $this->mapPostsToSearchItems($posts);
        $hasMore = $total > $initialCount;
        $nextPageUrl = $hasMore ? $this->loadMoreUrl(1) : null;

        return [$items, $hasMore, $nextPageUrl, null];
    }

    private function loadMoreUrl(int $morePage): string
    {
        $params = request()->query();
        $params['more_page'] = $morePage;

        return request()->url() . '?' . http_build_query($params);
    }
}
