<?php

use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AiChatController;
use App\Http\Controllers\Admin\AiImageController;
use App\Http\Controllers\Admin\AiKnowledgeController;
use App\Http\Controllers\Admin\AiSearchIntelligenceController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomSafariInquiryController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\EmailCampaignController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\ProposalTemplateController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\SafariController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourInquiryController;
use App\Http\Controllers\Admin\TripPlanAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\IsAdmin;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Support\Facades\Route;

// Add 'logout' route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Admin Login & Route Aliases
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/admin/login', function () {
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('admin.login');

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    // Priority Booking System Routes
    Route::get('/bookings/export', [BookingController::class, 'export'])->name('admin.bookings.export');
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    Route::post('/bookings/{booking}/record-payment', [BookingController::class, 'recordPayment'])->name('admin.bookings.record-payment');

    Route::resource('/bookings', BookingController::class)->only(['index', 'show', 'destroy'])->names([
        'index' => 'admin.bookings.index',
        'show' => 'admin.bookings.show',
        'destroy' => 'admin.bookings.destroy',
    ]);
    Route::patch('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
    Route::get('/bookings/{booking}/download-itinerary', [BookingController::class, 'downloadItinerary'])->name('admin.bookings.download-itinerary');
    Route::get('/bookings/{booking}/download-invoice', [BookingController::class, 'downloadInvoice'])->name('admin.bookings.download-invoice');

    Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('admin.dashboard');

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
    Route::resource('/sliders', SliderController::class)->except(['show'])->names([
        'index' => 'admin.sliders.index',
        'create' => 'admin.sliders.create',
        'store' => 'admin.sliders.store',
        'edit' => 'admin.sliders.edit',
        'update' => 'admin.sliders.update',
        'destroy' => 'admin.sliders.destroy',
    ]);

    // Media Manager
    Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('admin.media.destroy');
    Route::post('/media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('admin.media.bulk-delete');
    Route::patch('/media/{media}', [MediaController::class, 'update'])->name('admin.media.update');

    Route::resource('/tours', TourController::class)->names([
        'index' => 'admin.tours.index',
        'create' => 'admin.tours.create',
        'store' => 'admin.tours.store',
        'show' => 'admin.tours.show',
        'edit' => 'admin.tours.edit',
        'update' => 'admin.tours.update',
        'destroy' => 'admin.tours.destroy',
    ]);

    Route::resource('/safaris', SafariController::class)->except(['show'])->names([
        'index' => 'admin.safaris.index',
        'create' => 'admin.safaris.create',
        'store' => 'admin.safaris.store',
        'edit' => 'admin.safaris.edit',
        'update' => 'admin.safaris.update',
        'destroy' => 'admin.safaris.destroy',
    ]);
    Route::post('/tours/{tour}/toggle-publish', [TourController::class, 'togglePublish'])->name('admin.tours.toggle-publish');
    Route::delete('/tours/images/{image}', [TourController::class, 'deleteImage'])->name('admin.tours.delete-image');

    Route::resource('/categories', CategoryController::class)->except(['show'])->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    Route::resource('/destinations', DestinationController::class)->except(['show'])->names([
        'index' => 'admin.destinations.index',
        'create' => 'admin.destinations.create',
        'store' => 'admin.destinations.store',
        'edit' => 'admin.destinations.edit',
        'update' => 'admin.destinations.update',
        'destroy' => 'admin.destinations.destroy',
    ]);

    Route::resource('/gallery', GalleryController::class)->parameters(['gallery' => 'album'])->except(['show'])->names([
        'index' => 'admin.gallery.index',
        'create' => 'admin.gallery.create',
        'store' => 'admin.gallery.store',
        'edit' => 'admin.gallery.edit',
        'update' => 'admin.gallery.update',
        'destroy' => 'admin.gallery.destroy',
    ]);
    Route::post('/gallery/{album}/add-image', [GalleryController::class, 'addImage'])->name('admin.gallery.add-image');
    Route::delete('/gallery/images/{image}', [GalleryController::class, 'removeImage'])->name('admin.gallery.remove-image');

    Route::resource('/testimonials', TestimonialController::class)->except(['show'])->names([
        'index' => 'admin.testimonials.index',
        'create' => 'admin.testimonials.create',
        'store' => 'admin.testimonials.store',
        'edit' => 'admin.testimonials.edit',
        'update' => 'admin.testimonials.update',
        'destroy' => 'admin.testimonials.destroy',
    ]);

    Route::resource('/trip-plans', TripPlanAdminController::class)->only(['index', 'show', 'destroy'])->names([
        'index' => 'admin.trip-plans.index',
        'show' => 'admin.trip-plans.show',
        'destroy' => 'admin.trip-plans.destroy',
    ]);

    Route::resource('/custom-inquiries', CustomSafariInquiryController::class)->only(['index', 'show', 'destroy'])->names([
        'index' => 'admin.custom-inquiries.index',
        'show' => 'admin.custom-inquiries.show',
        'destroy' => 'admin.custom-inquiries.destroy',
    ]);
    Route::post('/custom-inquiries/{inquiry}/convert', [CustomSafariInquiryController::class, 'convertToProposal'])->name('admin.custom-inquiries.convert');

    Route::post('/proposals/upload-day-image', [ProposalController::class, 'uploadDayImage'])->name('admin.proposals.upload-day-image');

    Route::resource('/proposals', ProposalController::class)->names([
        'index' => 'admin.proposals.index',
        'create' => 'admin.proposals.create',
        'store' => 'admin.proposals.store',
        'show' => 'admin.proposals.show',
        'edit' => 'admin.proposals.edit',
        'update' => 'admin.proposals.update',
        'destroy' => 'admin.proposals.destroy',
    ]);
    Route::post('/proposals/{proposal}/send', [ProposalController::class, 'send'])->name('admin.proposals.send');
    Route::get('/proposals/{proposal}/download-pdf', [ProposalController::class, 'downloadPdf'])->name('admin.proposals.download-pdf');
    Route::post('/proposals/{proposal}/duplicate-version', [ProposalController::class, 'duplicateVersion'])->name('admin.proposals.duplicate-version');
    Route::post('/proposals/{proposal}/save-as-template', [ProposalTemplateController::class, 'saveProposalAsTemplate'])->name('admin.proposals.save-as-template');

    // Day Templates & Complete Proposal Templates
    Route::get('/proposal-day-templates', [ProposalTemplateController::class, 'getDayTemplates'])->name('admin.day-templates.index');
    Route::post('/proposal-day-templates', [ProposalTemplateController::class, 'storeDayTemplate'])->name('admin.day-templates.store');
    Route::delete('/proposal-day-templates/{dayTemplate}', [ProposalTemplateController::class, 'destroyDayTemplate'])->name('admin.day-templates.destroy');

    Route::resource('/proposal-templates', ProposalTemplateController::class)->names([
        'index' => 'admin.proposal-templates.index',
        'create' => 'admin.proposal-templates.create',
        'store' => 'admin.proposal-templates.store',
        'show' => 'admin.proposal-templates.show',
        'edit' => 'admin.proposal-templates.edit',
        'update' => 'admin.proposal-templates.update',
        'destroy' => 'admin.proposal-templates.destroy',
    ]);
    Route::post('/proposal-templates/{template}/duplicate', [ProposalTemplateController::class, 'duplicate'])->name('admin.proposal-templates.duplicate');
    Route::post('/proposal-templates/{template}/archive', [ProposalTemplateController::class, 'archive'])->name('admin.proposal-templates.archive');
    Route::get('/proposal-templates/{template}/use', [ProposalTemplateController::class, 'useTemplate'])->name('admin.proposal-templates.use');

    Route::prefix('trip-plans/{tripPlan}')->group(function () {
        Route::patch('/update-status', [TripPlanAdminController::class, 'updateStatus'])->name('admin.trip-plans.update-status');
        Route::post('/save-itinerary', [TripPlanAdminController::class, 'saveItinerary'])->name('admin.trip-plans.save-itinerary');
        Route::post('/save-pricing', [TripPlanAdminController::class, 'savePricing'])->name('admin.trip-plans.save-pricing');
        Route::post('/send-message', [TripPlanAdminController::class, 'sendMessage'])->name('admin.trip-plans.send-message');
        Route::post('/convert-to-booking', [TripPlanAdminController::class, 'convertToBooking'])->name('admin.trip-plans.convert-to-booking');
    });

    Route::patch('/reviews/{review}/approve', [ReviewAdminController::class, 'approve'])->name('admin.reviews.approve');
    Route::resource('/reviews', ReviewAdminController::class)->only(['index', 'destroy'])->names([
        'index' => 'admin.reviews.index',
        'destroy' => 'admin.reviews.destroy',
    ]);

    Route::resource('/faqs', FaqController::class)->except(['show'])->names([
        'index' => 'admin.faqs.index',
        'create' => 'admin.faqs.create',
        'store' => 'admin.faqs.store',
        'edit' => 'admin.faqs.edit',
        'update' => 'admin.faqs.update',
        'destroy' => 'admin.faqs.destroy',
    ]);

    Route::resource('/blog-categories', BlogCategoryController::class)->parameters([
        'blog-categories' => 'blogCategory',
    ])->except(['show'])->names([
        'index' => 'admin.blog-categories.index',
        'create' => 'admin.blog-categories.create',
        'store' => 'admin.blog-categories.store',
        'edit' => 'admin.blog-categories.edit',
        'update' => 'admin.blog-categories.update',
        'destroy' => 'admin.blog-categories.destroy',
    ]);

    Route::resource('/blog', BlogController::class)->except(['show'])->names([
        'index' => 'admin.blog.index',
        'create' => 'admin.blog.create',
        'store' => 'admin.blog.store',
        'edit' => 'admin.blog.edit',
        'update' => 'admin.blog.update',
        'destroy' => 'admin.blog.destroy',
    ]);

    // Admin Notification Routes
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');

    // Tour Inquiry Routes
    Route::get('/inquiries', [TourInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [TourInquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::delete('/inquiries/{inquiry}', [TourInquiryController::class, 'destroy'])->name('admin.inquiries.destroy');

    // Settings Routes (custom, no model parameter needed)
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Marketing & SEO Routes
    Route::get('/marketing', [MarketingController::class, 'index'])->name('admin.marketing.index');
    Route::put('/marketing', [MarketingController::class, 'update'])->name('admin.marketing.update');

    Route::resource('/email-campaigns', EmailCampaignController::class)->only(['index', 'create', 'destroy'])->names([
        'index' => 'admin.email-campaigns.index',
        'create' => 'admin.email-campaigns.create',
        'destroy' => 'admin.email-campaigns.destroy',
    ]);
    Route::post('/email-campaigns/send', [EmailCampaignController::class, 'send'])->name('admin.email-campaigns.send');
    Route::patch('/email-campaigns/{subscriber}/toggle', [EmailCampaignController::class, 'toggleStatus'])->name('admin.email-campaigns.toggle');

    // AI Image Upload System
    Route::resource('/ai-images', AiImageController::class)->except(['show'])->names([
        'index' => 'admin.ai-images.index',
        'create' => 'admin.ai-images.create',
        'store' => 'admin.ai-images.store',
        'edit' => 'admin.ai-images.edit',
        'update' => 'admin.ai-images.update',
        'destroy' => 'admin.ai-images.destroy',
    ]);
    Route::get('/ai-images/preview', [AiImageController::class, 'preview'])->name('admin.ai-images.preview');
    Route::post('/ai-images/confirm', [AiImageController::class, 'confirm'])->name('admin.ai-images.confirm');

    // AI Chat Assistant
    Route::get('/ai-assistant', [AiChatController::class, 'index'])->name('admin.ai-assistant.index');
    Route::post('/ai-assistant/send', [AiChatController::class, 'sendMessage'])->name('admin.ai-assistant.send');

    // AI Knowledge Base (Nondo)
    Route::resource('/ai-knowledge', AiKnowledgeController::class)->except(['show'])->names([
        'index' => 'admin.ai-knowledge.index',
        'create' => 'admin.ai-knowledge.create',
        'store' => 'admin.ai-knowledge.store',
        'edit' => 'admin.ai-knowledge.edit',
        'update' => 'admin.ai-knowledge.update',
        'destroy' => 'admin.ai-knowledge.destroy',
    ]);

    // Advertisement Campaigns
    Route::get('/campaigns/tour-data/{tour}', [CampaignController::class, 'getTourData'])->name('admin.campaigns.tour-data');
    Route::get('/campaigns/{campaign}/analytics', [CampaignController::class, 'analytics'])->name('admin.campaigns.analytics');
    Route::resource('/campaigns', CampaignController::class)->except(['show'])->names([
        'index' => 'admin.campaigns.index',
        'create' => 'admin.campaigns.create',
        'store' => 'admin.campaigns.store',
        'edit' => 'admin.campaigns.edit',
        'update' => 'admin.campaigns.update',
        'destroy' => 'admin.campaigns.destroy',
    ]);

    // AI Search Intelligence System
    Route::prefix('ai-search-intelligence')->group(function () {
        Route::get('/', [AiSearchIntelligenceController::class, 'dashboard'])->name('admin.ai-search.dashboard');

        // Questions Management
        Route::get('/questions', [AiSearchIntelligenceController::class, 'questionsIndex'])->name('admin.ai-search.questions.index');
        Route::get('/questions/create', [AiSearchIntelligenceController::class, 'createQuestion'])->name('admin.ai-search.questions.create');
        Route::post('/questions', [AiSearchIntelligenceController::class, 'storeQuestion'])->name('admin.ai-search.questions.store');
        Route::get('/questions/{question}/edit', [AiSearchIntelligenceController::class, 'editQuestion'])->name('admin.ai-search.questions.edit');
        Route::put('/questions/{question}', [AiSearchIntelligenceController::class, 'updateQuestion'])->name('admin.ai-search.questions.update');
        Route::delete('/questions/{question}', [AiSearchIntelligenceController::class, 'destroyQuestion'])->name('admin.ai-search.questions.destroy');
        Route::post('/generate-ai-answer', [AiSearchIntelligenceController::class, 'generateAiAnswer'])->name('admin.ai-search.generate-answer');

        // AI Visibility Tracker
        Route::get('/visibility', [AiSearchIntelligenceController::class, 'visibilityIndex'])->name('admin.ai-search.visibility.index');
        Route::post('/visibility', [AiSearchIntelligenceController::class, 'storeVisibilityCheck'])->name('admin.ai-search.visibility.store');
        Route::delete('/visibility/{check}', [AiSearchIntelligenceController::class, 'destroyVisibilityCheck'])->name('admin.ai-search.visibility.destroy');
    });
});
