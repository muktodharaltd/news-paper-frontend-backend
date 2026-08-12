<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\MetaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReporterController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserSettingsController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\AdvertisementClickController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\SearchController as FrontendSearchController;
use App\Http\Controllers\Frontend\VideoController as FrontendVideoController;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Route;

// Language switch (cookie + redirect — Google Translate reads googtrans on reload)
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->whereIn('locale', ['bn', 'en'])
    ->name('lang.switch');

// Local: 503 maintenance page preview (no artisan down needed)
if (app()->environment('local')) {
    Route::get('/preview-503', fn () => response(view('errors.503'), 503));
}

// Public frontend routes (all non-admin, non-API pages)
Route::get('/', [FrontendHomeController::class, 'index'])->name('home');

// Search (post, gallery, video – one list, category-style)
Route::get('/search', [FrontendSearchController::class, 'index'])->name('search');
Route::get('/topic/{slug}', [FrontendSearchController::class, 'index'])->name('topic.show');

// সর্বশেষ – সব নতুন পোস্ট, ক্যাটাগরি পেজের মতো UI
Route::get('/latest', [FrontendCategoryController::class, 'latest'])->name('latest');

// আর্কাইভ – সর্বশেষ পেজের মতো UI
Route::get('/archive', [FrontendCategoryController::class, 'archive'])->name('archive');
Route::get('/archive/calendar', [FrontendCategoryController::class, 'archiveCalendar'])->name('archive.calendar');

// Category listing routes
Route::get('/category/{slug}', [FrontendCategoryController::class, 'show'])->name('category.show');
Route::get('/category/{parentSlug}/{childSlug}', [FrontendCategoryController::class, 'showChild'])
    ->name('category.show.child');
Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [FrontendGalleryController::class, 'show'])->name('gallery.show');

Route::get('/videos', [FrontendVideoController::class, 'index'])->name('videos.index');
Route::get('/video/{slug}', [FrontendVideoController::class, 'show'])->name('videos.show');

// Simple public newsletter subscribe endpoint (footer form)
Route::post('/subscribe', function (\Illuminate\Http\Request $request) {
    $data = $request->validate(['email' => 'required|email']);

    Subscriber::firstOrCreate(['email' => $data['email']]);

    return back()->with('subscribe_success', 'সাবস্ক্রাইব করার জন্য ধন্যবাদ।');
})->name('frontend.subscribe');

Route::get('/push/config', [\App\Http\Controllers\PushSubscriptionController::class, 'config'])->name('push.config');
Route::post('/push/subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
Route::delete('/push/unsubscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

Route::get('/ad/click/{advertisement}', AdvertisementClickController::class)
    ->whereNumber('advertisement')
    ->middleware('throttle:120,1')
    ->name('advertisement.click');

Route::get('/ad/queue-click/{queueItem}', \App\Http\Controllers\AdvertisementQueueClickController::class)
    ->whereNumber('queueItem')
    ->middleware('throttle:120,1')
    ->name('advertisement.queue-click');

// Static pages copied from the news-paper frontend project
Route::view('/national', 'national');
Route::get('/special-news', [FrontendHomeController::class, 'specialNews'])->name('special-news');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/bangla-converter', 'frontend.bangla-converter')->name('bangla-converter');

// Fallback login route name that Laravel's auth middleware expects
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::middleware('guest')->group(function (): void {
            Route::get('login', [AdminAuthController::class, 'showLoginForm'])
                ->name('login');

            Route::post('login', [AdminAuthController::class, 'login'])
                ->middleware('throttle:10,1')
                ->name('login.attempt');
        });

        Route::post('logout', [AdminAuthController::class, 'logout'])
            ->middleware('auth')
            ->name('logout');

        Route::middleware(['auth', 'role:admin,senior editor,sub editor'])->group(function (): void {
            Route::get('/', DashboardController::class)->name('dashboard');
            Route::get('/user-settings', [UserSettingsController::class, 'edit'])
                ->name('user-settings.edit');
            Route::put('/user-settings', [UserSettingsController::class, 'update'])
                ->name('user-settings.update');

            Route::middleware('feature:categories.manage')->group(function (): void {
                Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
                Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
                Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
                Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
                Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');

                Route::get('/topics', [App\Http\Controllers\Admin\TopicController::class, 'index'])->name('topics.index');
                Route::post('/topics', [App\Http\Controllers\Admin\TopicController::class, 'store'])->name('topics.store');
                Route::put('/topics/{id}', [App\Http\Controllers\Admin\TopicController::class, 'update'])->name('topics.update');
                Route::delete('/topics/{id}', [App\Http\Controllers\Admin\TopicController::class, 'destroy'])->name('topics.destroy');
            });

            Route::middleware('feature:posts.view')->group(function (): void {
                Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
            });
            Route::middleware('feature:posts.manage')->group(function (): void {
                Route::get('/posts/pick-image', [PostController::class, 'pickImage'])->name('posts.pick-image');
                Route::post('/posts/pick-image', [PostController::class, 'applyPickedImage'])->name('posts.pick-image.apply');
                Route::post('/posts/reporters/quick-store', [ReporterController::class, 'quickStore'])->name('posts.reporters.quick-store');
                Route::post('/topics/quick-store', [App\Http\Controllers\Admin\TopicController::class, 'quickStore'])->name('topics.quick-store');
                Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
                Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
                Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
                Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
                Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
            });

            Route::middleware('feature:pages.manage')->group(function (): void {
                Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
                Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
                Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
                Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
                Route::put('/pages/{id}', [PageController::class, 'update'])->name('pages.update');
                Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
            });

            Route::middleware('feature:galleries.manage')->group(function (): void {
                Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
                Route::get('/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
                Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
                Route::get('/galleries/{id}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
                Route::put('/galleries/{id}', [GalleryController::class, 'update'])->name('galleries.update');
                Route::delete('/galleries/{id}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
                Route::delete('/gallery-images/{imageId}', [GalleryController::class, 'destroyImage'])->name('galleries.images.destroy');
            });

            Route::middleware('feature:videos.manage')->group(function (): void {
                Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
                Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
                Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
                Route::get('/videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
                Route::put('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
                Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
            });

            Route::middleware('feature:reporters.manage')->group(function (): void {
                Route::get('/reporters', [ReporterController::class, 'index'])->name('reporters.index');
                Route::get('/reporters/create', [ReporterController::class, 'create'])->name('reporters.create');
                Route::post('/reporters', [ReporterController::class, 'store'])->name('reporters.store');
                Route::get('/reporters/{id}/edit', [ReporterController::class, 'edit'])->name('reporters.edit');
                Route::put('/reporters/{id}', [ReporterController::class, 'update'])->name('reporters.update');
                Route::delete('/reporters/{id}', [ReporterController::class, 'destroy'])->name('reporters.destroy');
            });

            Route::middleware('feature:advertisements.manage')->group(function (): void {
                Route::put('/advertisements/google-slots', [App\Http\Controllers\Admin\AdvertisementController::class, 'updateBulkGoogleSlots'])->name('advertisements.google-slots.update');
                Route::get('/advertisements', [App\Http\Controllers\Admin\AdvertisementController::class, 'index'])->name('advertisements.index');
                Route::get('/advertisements/photocard-ads', [App\Http\Controllers\Admin\PhotocardAdController::class, 'index'])->name('advertisements.photocard-ads.index');
                Route::post('/advertisements/photocard-ads/{categoryId}', [App\Http\Controllers\Admin\PhotocardAdController::class, 'update'])->name('advertisements.photocard-ads.update');
                Route::delete('/advertisements/photocard-ads/{categoryId}', [App\Http\Controllers\Admin\PhotocardAdController::class, 'destroy'])->name('advertisements.photocard-ads.destroy');
                Route::get('/advertisements/{id}/edit', [App\Http\Controllers\Admin\AdvertisementController::class, 'edit'])->name('advertisements.edit');
                Route::put('/advertisements/{id}', [App\Http\Controllers\Admin\AdvertisementController::class, 'update'])->name('advertisements.update');
                Route::put('/advertisements/{id}/google-settings', [App\Http\Controllers\Admin\AdvertisementController::class, 'updateGoogleSettings'])->name('advertisements.google-settings.update');
                Route::post('/advertisements/{id}/pause-local', [App\Http\Controllers\Admin\AdvertisementController::class, 'pauseLocalAd'])->name('advertisements.local-ad.pause');
                Route::post('/advertisements/{id}/resume-local', [App\Http\Controllers\Admin\AdvertisementController::class, 'resumeLocalAd'])->name('advertisements.local-ad.resume');
                Route::post('/advertisements/{id}/delete-local', [App\Http\Controllers\Admin\AdvertisementController::class, 'deleteLocalAd'])->name('advertisements.local-ad.delete');
                Route::post('/advertisements/{id}/toggle-ad-source', [App\Http\Controllers\Admin\AdvertisementController::class, 'toggleAdSource'])->name('advertisements.toggle-source');
                Route::post('/advertisements/{id}/queue-items', [App\Http\Controllers\Admin\AdvertisementController::class, 'storeQueueItem'])->name('advertisements.queue-items.store');
                Route::put('/advertisements/{id}/queue-items/{itemId}', [App\Http\Controllers\Admin\AdvertisementController::class, 'updateQueueItem'])->name('advertisements.queue-items.update');
                Route::delete('/advertisements/{id}/queue-items/{itemId}', [App\Http\Controllers\Admin\AdvertisementController::class, 'destroyQueueItem'])->name('advertisements.queue-items.destroy');
                Route::post('/advertisements/{id}/queue-items/reorder', [App\Http\Controllers\Admin\AdvertisementController::class, 'reorderQueueItems'])->name('advertisements.queue-items.reorder');
            });

            Route::middleware('feature:statistics.view')->group(function (): void {
                Route::get('/statistics/visitors', [App\Http\Controllers\Admin\StatisticsController::class, 'visitors'])->name('statistics.visitors');
            });

            Route::middleware('feature:subscribes.view')->group(function (): void {
                Route::get('/subscribes', [SubscribeController::class, 'index'])->name('subscribes.index');
                Route::post('/subscribes', [SubscribeController::class, 'store'])->name('subscribes.store');
                Route::post('/subscribes/push-test', [SubscribeController::class, 'sendTestPush'])->name('subscribes.push-test');
            });

            Route::middleware('feature:users.manage')->group(function (): void {
                Route::get('/users', [UserController::class, 'index'])->name('users.index');
                Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
                Route::post('/users', [UserController::class, 'store'])->name('users.store');
                Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
                Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
                Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            });

            Route::middleware('feature:role_permissions.manage')->group(function (): void {
                Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
                Route::put('/role-permissions', [RolePermissionController::class, 'update'])->name('role-permissions.update');
            });

            Route::middleware('feature:settings.meta')->group(function (): void {
                Route::get('/meta', [MetaController::class, 'index'])->name('meta.index');
                Route::post('/meta', [MetaController::class, 'update'])->name('meta.update');
            });

            Route::middleware('feature:settings.layout')->group(function (): void {
                Route::get('/layout/frontend', [LayoutController::class, 'frontend'])->name('layout.frontend');
                Route::post('/layout/frontend', [LayoutController::class, 'saveFrontend'])->name('layout.frontend.save');
                Route::get('/layout/home', [LayoutController::class, 'home'])->name('layout.home');
                Route::post('/layout/home', [LayoutController::class, 'saveHome'])->name('layout.home.save');
            });

            Route::get('/heartbeat', function () {
                return response()->json(['status' => 'ok']);
            })->name('heartbeat');
        });
    });

// Legacy URLs (Google-এ পুরনো index) → canonical slug URL
Route::get('/news-view/{id}', [FrontendPostController::class, 'redirectLegacyNewsView'])
    ->whereNumber('id');
Route::get('/news/{slug}', [FrontendPostController::class, 'redirectLegacyNews'])
    ->where('slug', '.+');

// Post featured image — full page viewer
Route::get('/{slug}/photo', [FrontendPostController::class, 'showPhoto'])
    ->where('slug', '^(?!admin$|category$|page$|gallery$|video$|login$|search$|latest$|archive$|subscribe$|special-news$|videos$|terms$|privacy-policy$|bangla-converter$|heartbeat$|national$|api$|lang$).+')
    ->name('news.photo');

// News detail route (fully simplified)
Route::get('/{slug}', [FrontendPostController::class, 'show'])
    ->where('slug', '^(?!admin$|category$|page$|gallery$|video$|login$|search$|latest$|archive$|subscribe$|special-news$|videos$|terms$|privacy-policy$|bangla-converter$|heartbeat$|national$|api$|lang$).+')
    ->name('news.show');
