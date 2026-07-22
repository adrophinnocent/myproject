<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;

// Add 'logout' route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Admin Login Routes (Path alias for convenience)
Route::get('/admin/login', function() {
    return redirect()->route('login');
});

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Priority Routes (Moved Bookings here)
    Route::resource('/bookings', App\Http\Controllers\Admin\BookingController::class)->names([
        'index' => 'admin.bookings.index',
        'create' => 'admin.bookings.create',
        'store' => 'admin.bookings.store',
        'show' => 'admin.bookings.show',
        'edit' => 'admin.bookings.edit',
        'update' => 'admin.bookings.update',
        'destroy' => 'admin.bookings.destroy',
    ]);
    Route::patch('/bookings/{booking}/update-status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
    Route::post('/bookings/{booking}/update-status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus']);
    Route::get('/bookings/{booking}/download-itinerary', [App\Http\Controllers\Admin\BookingController::class, 'downloadItinerary'])->name('admin.bookings.download-itinerary');
    Route::get('/bookings/{booking}/download-invoice', [App\Http\Controllers\Admin\BookingController::class, 'downloadInvoice'])->name('admin.bookings.download-invoice');

    Route::get('/dashboard', [App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('admin.dashboard');

    // Chart Routes
    Route::get('/dashboard/revenue-chart', function () {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $revenues[] = (float) Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');
        }

        return response()->json([
            'labels' => $months,
            'revenues' => $revenues,
        ]);
    })->name('admin.dashboard.revenue-chart');

    Route::get('/dashboard/bookings-chart', function () {
        $months = [];
        $bookings = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $bookings[] = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return response()->json([
            'labels' => $months,
            'bookings' => $bookings,
        ]);
    })->name('admin.dashboard.bookings-chart');

    Route::get('/dashboard/tour-performance-chart', function () {
        $tours = Tour::withCount('bookings')
            ->withSum('bookings', 'total_price')
            ->take(10)
            ->get();

        return response()->json([
            'labels' => $tours->pluck('title'),
            'revenues' => $tours->pluck('bookings_sum_total_price'),
            'bookings' => $tours->pluck('bookings_count'),
        ]);
    })->name('admin.dashboard.tour-performance-chart');

    // Admin Resource Routes
    Route::resource('/sliders', App\Http\Controllers\Admin\SliderController::class)->names([
        'index' => 'admin.sliders.index',
        'create' => 'admin.sliders.create',
        'store' => 'admin.sliders.store',
        'show' => 'admin.sliders.show',
        'edit' => 'admin.sliders.edit',
        'update' => 'admin.sliders.update',
        'destroy' => 'admin.sliders.destroy',
    ]);

    // Media Manager
    Route::get('/media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/media/upload', [App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('admin.media.upload');
    Route::delete('/media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('admin.media.destroy');
    Route::post('/media/bulk-delete', [App\Http\Controllers\Admin\MediaController::class, 'bulkDelete'])->name('admin.media.bulk-delete');
    Route::patch('/media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'update'])->name('admin.media.update');

    Route::resource('/tours', App\Http\Controllers\Admin\TourController::class)->names([
        'index' => 'admin.tours.index',
        'create' => 'admin.tours.create',
        'store' => 'admin.tours.store',
        'show' => 'admin.tours.show',
        'edit' => 'admin.tours.edit',
        'update' => 'admin.tours.update',
        'destroy' => 'admin.tours.destroy',
    ]);

    Route::resource('/safaris', App\Http\Controllers\Admin\SafariController::class)->names([
        'index' => 'admin.safaris.index',
        'create' => 'admin.safaris.create',
        'store' => 'admin.safaris.store',
        'show' => 'admin.safaris.show',
        'edit' => 'admin.safaris.edit',
        'update' => 'admin.safaris.update',
        'destroy' => 'admin.safaris.destroy',
    ]);
    Route::post('/tours/{tour}/toggle-publish', [App\Http\Controllers\Admin\TourController::class, 'togglePublish'])->name('admin.tours.toggle-publish');
    Route::match(['get', 'delete'], '/tours/images/{image}', [App\Http\Controllers\Admin\TourController::class, 'deleteImage'])->name('admin.tours.delete-image');

    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    Route::resource('/destinations', App\Http\Controllers\Admin\DestinationController::class)->names([
        'index' => 'admin.destinations.index',
        'create' => 'admin.destinations.create',
        'store' => 'admin.destinations.store',
        'show' => 'admin.destinations.show',
        'edit' => 'admin.destinations.edit',
        'update' => 'admin.destinations.update',
        'destroy' => 'admin.destinations.destroy',
    ]);

    Route::resource('/gallery', App\Http\Controllers\Admin\GalleryController::class)->parameters(['gallery' => 'album'])->names([
        'index' => 'admin.gallery.index',
        'create' => 'admin.gallery.create',
        'store' => 'admin.gallery.store',
        'show' => 'admin.gallery.show',
        'edit' => 'admin.gallery.edit',
        'update' => 'admin.gallery.update',
        'destroy' => 'admin.gallery.destroy',
    ]);
    Route::post('/gallery/{album}/add-image', [App\Http\Controllers\Admin\GalleryController::class, 'addImage'])->name('admin.gallery.add-image');
    Route::delete('/gallery/images/{image}', [App\Http\Controllers\Admin\GalleryController::class, 'removeImage'])->name('admin.gallery.remove-image');

    Route::resource('/testimonials', App\Http\Controllers\Admin\TestimonialController::class)->names([
        'index' => 'admin.testimonials.index',
        'create' => 'admin.testimonials.create',
        'store' => 'admin.testimonials.store',
        'show' => 'admin.testimonials.show',
        'edit' => 'admin.testimonials.edit',
        'update' => 'admin.testimonials.update',
        'destroy' => 'admin.testimonials.destroy',
    ]);

    Route::resource('/trip-plans', App\Http\Controllers\Admin\TripPlanAdminController::class)->names([
        'index' => 'admin.trip-plans.index',
        'create' => 'admin.trip-plans.create',
        'store' => 'admin.trip-plans.store',
        'show' => 'admin.trip-plans.show',
        'edit' => 'admin.trip-plans.edit',
        'update' => 'admin.trip-plans.update',
        'destroy' => 'admin.trip-plans.destroy',
    ]);

    Route::patch('/trip-plans/{tripPlan}/update-status', [App\Http\Controllers\Admin\TripPlanAdminController::class, 'updateStatus'])->name('admin.trip-plans.update-status');

    Route::match(['get', 'patch'], '/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewAdminController::class, 'approve'])->name('admin.reviews.approve');
    Route::resource('/reviews', App\Http\Controllers\Admin\ReviewAdminController::class)->names([
        'index' => 'admin.reviews.index',
        'create' => 'admin.reviews.create',
        'store' => 'admin.reviews.store',
        'show' => 'admin.reviews.show',
        'edit' => 'admin.reviews.edit',
        'update' => 'admin.reviews.update',
        'destroy' => 'admin.reviews.destroy',
    ]);

    Route::resource('/faqs', App\Http\Controllers\Admin\FaqController::class)->names([
        'index' => 'admin.faqs.index',
        'create' => 'admin.faqs.create',
        'store' => 'admin.faqs.store',
        'show' => 'admin.faqs.show',
        'edit' => 'admin.faqs.edit',
        'update' => 'admin.faqs.update',
        'destroy' => 'admin.faqs.destroy',
    ]);

    Route::resource('/blog-categories', App\Http\Controllers\Admin\BlogCategoryController::class)->parameters([
        'blog-categories' => 'blogCategory'
    ])->names([
        'index' => 'admin.blog-categories.index',
        'create' => 'admin.blog-categories.create',
        'store' => 'admin.blog-categories.store',
        'show' => 'admin.blog-categories.show',
        'edit' => 'admin.blog-categories.edit',
        'update' => 'admin.blog-categories.update',
        'destroy' => 'admin.blog-categories.destroy',
    ]);

    Route::resource('/blog', App\Http\Controllers\Admin\BlogController::class)->names([
        'index' => 'admin.blog.index',
        'create' => 'admin.blog.create',
        'store' => 'admin.blog.store',
        'show' => 'admin.blog.show',
        'edit' => 'admin.blog.edit',
        'update' => 'admin.blog.update',
        'destroy' => 'admin.blog.destroy',
    ]);

    // Admin Notification Routes
    Route::get('/notifications', [App\Http\Controllers\Admin\AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\Admin\AdminNotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');

    // Tour Inquiry Routes
    Route::get('/inquiries', [App\Http\Controllers\Admin\TourInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [App\Http\Controllers\Admin\TourInquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::delete('/inquiries/{inquiry}', [App\Http\Controllers\Admin\TourInquiryController::class, 'destroy'])->name('admin.inquiries.destroy');

    // Settings Routes (custom, no model parameter needed)
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // Marketing & SEO Routes
    Route::get('/marketing', [App\Http\Controllers\Admin\MarketingController::class, 'index'])->name('admin.marketing.index');
    Route::put('/marketing', [App\Http\Controllers\Admin\MarketingController::class, 'update'])->name('admin.marketing.update');

    Route::resource('/email-campaigns', App\Http\Controllers\Admin\EmailCampaignController::class)->names([
        'index' => 'admin.email-campaigns.index',
        'create' => 'admin.email-campaigns.create',
        'store' => 'admin.email-campaigns.store',
        'show' => 'admin.email-campaigns.show',
        'edit' => 'admin.email-campaigns.edit',
        'update' => 'admin.email-campaigns.update',
        'destroy' => 'admin.email-campaigns.destroy',
    ]);
    Route::post('/email-campaigns/send', [App\Http\Controllers\Admin\EmailCampaignController::class, 'send'])->name('admin.email-campaigns.send');
    Route::patch('/email-campaigns/{subscriber}/toggle', [App\Http\Controllers\Admin\EmailCampaignController::class, 'toggleStatus'])->name('admin.email-campaigns.toggle');

    // AI Image Upload System
    Route::resource('/ai-images', App\Http\Controllers\Admin\AiImageController::class)->names([
        'index' => 'admin.ai-images.index',
        'create' => 'admin.ai-images.create',
        'store' => 'admin.ai-images.store',
        'show' => 'admin.ai-images.show',
        'edit' => 'admin.ai-images.edit',
        'update' => 'admin.ai-images.update',
        'destroy' => 'admin.ai-images.destroy',
    ]);
    Route::get('/ai-images/preview', [App\Http\Controllers\Admin\AiImageController::class, 'preview'])->name('admin.ai-images.preview');
    Route::post('/ai-images/confirm', [App\Http\Controllers\Admin\AiImageController::class, 'confirm'])->name('admin.ai-images.confirm');

    // AI Chat Assistant
    Route::get('/ai-assistant', [App\Http\Controllers\Admin\AiChatController::class, 'index'])->name('admin.ai-assistant.index');
    Route::post('/ai-assistant/send', [App\Http\Controllers\Admin\AiChatController::class, 'sendMessage'])->name('admin.ai-assistant.send');

    // AI Knowledge Base (Nondo)
    Route::resource('/ai-knowledge', App\Http\Controllers\Admin\AiKnowledgeController::class)->names([
        'index' => 'admin.ai-knowledge.index',
        'create' => 'admin.ai-knowledge.create',
        'store' => 'admin.ai-knowledge.store',
        'show' => 'admin.ai-knowledge.show',
        'edit' => 'admin.ai-knowledge.edit',
        'update' => 'admin.ai-knowledge.update',
        'destroy' => 'admin.ai-knowledge.destroy',
    ]);

    // Advertisement Campaigns
    Route::get('/campaigns/tour-data/{tour}', [App\Http\Controllers\Admin\CampaignController::class, 'getTourData'])->name('admin.campaigns.tour-data');
    Route::get('/campaigns/{campaign}/analytics', [App\Http\Controllers\Admin\CampaignController::class, 'analytics'])->name('admin.campaigns.analytics');
    Route::resource('/campaigns', App\Http\Controllers\Admin\CampaignController::class)->names([
        'index' => 'admin.campaigns.index',
        'create' => 'admin.campaigns.create',
        'store' => 'admin.campaigns.store',
        'show' => 'admin.campaigns.show',
        'edit' => 'admin.campaigns.edit',
        'update' => 'admin.campaigns.update',
        'destroy' => 'admin.campaigns.destroy',
    ]);
});
