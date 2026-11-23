<?php

use App\Helpers\SeoHelper;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\AiChatController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\CampaignController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CustomSafariController;
use App\Http\Controllers\Public\DestinationController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\InquiryController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\PaymentController;
use App\Http\Controllers\Public\ProposalController;
use App\Http\Controllers\Public\ReviewController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\TourController;
use App\Http\Controllers\Public\TripPlanController;
use Illuminate\Support\Facades\Route;

// Login Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest', 'throttle:10,1']);

// Password reset (admin account recovery)
require __DIR__.'/auth.php';

// Language Switcher
// The UI now links with ?lang=... (crawlable). This route is kept for old
// bookmarks and redirects them to the canonical URL for the chosen language.
Route::get('/lang/{locale}', function ($locale) {
    if (! SeoHelper::isSupported($locale)) {
        return redirect()->to('/');
    }

    session(['locale' => $locale]);
    session()->save();

    $target = $locale === SeoHelper::defaultLocale() ? '/' : '/?lang='.$locale;

    return redirect()->to($target);
})->name('lang.switch');

// Currency Switcher
Route::get('/currency/{code}', function ($code) {
    if (in_array($code, ['USD', 'EUR', 'GBP', 'TZS', 'KES', 'UGX'], true)) {
        session(['currency' => $code]);
        session()->save();
    }

    return back();
})->middleware('throttle:30,1')->name('currency.switch');

// Public Routes
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/itineraries', [TourController::class, 'index'])->name('itineraries.index');
Route::get('/itineraries/{slug}.html', [TourController::class, 'showItinerary'])->name('itineraries.show');
Route::get('/itineraries/{slug}', function ($slug) {
    if (str_ends_with($slug, '.html')) {
        return abort(404);
    }
    $queryString = request()->getQueryString();
    $url = "/itineraries/{$slug}.html".($queryString ? '?'.$queryString : '');

    return redirect()->to($url, 301);
});

// Public Booking System Routes
Route::get('/tours/{slug}/download-pdf', [TourController::class, 'downloadPdf'])->name('tours.download-pdf');
Route::get('/booking/policies', [BookingController::class, 'policies'])->name('booking.policies');
Route::get('/booking/{slug}', [BookingController::class, 'create']);
Route::get('/tours/{slug}/book', [BookingController::class, 'create'])->name('booking.create');
Route::post('/tours/{slug}/book', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('booking.store');

// Public booking pages are addressed by an unguessable token, never by the
// sequential/visible booking reference.
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/booking/confirmation/{token}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/booking/status/{token}', [BookingController::class, 'status'])->name('booking.status');
    Route::get('/booking/payment/{token}', [PaymentController::class, 'show'])->name('booking.payment');
    Route::post('/booking/payment/{token}/process', [PaymentController::class, 'processPayment'])
        ->middleware('throttle:5,1')
        ->name('booking.payment.process');
    Route::get('/booking/success/{token}', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/booking/download/{token}', [BookingController::class, 'downloadItinerary'])
        ->middleware('throttle:10,1')
        ->name('booking.download');
});

Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my-bookings');
Route::post('/my-bookings/search', [BookingController::class, 'myBookings'])
    ->middleware('throttle:10,1')
    ->name('booking.my-bookings.search');

// Dynamic Tour Show Route (Robust version with .html)
Route::get('/tours/{type}/{slug}.html', [TourController::class, 'show'])->name('tours.show');
Route::get('/tours/{type}/{slug}', function ($type, $slug) {
    if (str_ends_with($slug, '.html')) {
        return abort(404);
    }
    $queryString = request()->getQueryString();
    $url = "/tours/{$type}/{$slug}.html".($queryString ? '?'.$queryString : '');

    return redirect()->to($url, 301);
});

// Tour Inquiries
Route::post('/tours/{tour:id}/inquiry', [InquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('tours.inquiry');

// Public AI Assistant
Route::post('/ai-assistant/chat', [AiChatController::class, 'sendMessage'])
    ->middleware('throttle:20,1')
    ->name('ai-assistant.chat');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');

Route::get('/privacy-policy', function () {
    return view('public.privacy');
})->name('privacy.index');

Route::get('/terms-of-service', function () {
    return view('public.terms');
})->name('terms.index');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}.html', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/{slug}', function ($slug) {
    if (str_ends_with($slug, '.html')) {
        return abort(404);
    }
    $queryString = request()->getQueryString();
    $url = "/blog/{$slug}.html".($queryString ? '?'.$queryString : '');

    return redirect()->to($url, 301);
})->name('blog.redirect');

Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

// Request a Custom Safari Public Form
Route::get('/custom-safari', [CustomSafariController::class, 'create'])->name('custom-safari.create');
Route::post('/custom-safari', [CustomSafariController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('custom-safari.store');

Route::get('/trip-plan', [TripPlanController::class, 'index'])->name('trip-plan.index');
Route::post('/trip-plan', [TripPlanController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('trip-plan.store');

// Trip plans are private client workspaces: token only, never the sequential ID.
Route::middleware('throttle:30,1')->prefix('trip-plan')->group(function () {
    Route::get('/{token}', [TripPlanController::class, 'show'])->name('trip-plan.show');
    Route::get('/{token}/download', [TripPlanController::class, 'downloadPdf'])->name('trip-plan.download');
    Route::post('/{token}/accept', [TripPlanController::class, 'accept'])->name('trip-plan.accept');
    Route::post('/{token}/changes', [TripPlanController::class, 'requestChanges'])->name('trip-plan.changes');
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])
    ->middleware('throttle:30,1')
    ->name('newsletter.confirm');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->middleware('throttle:30,1')
    ->name('newsletter.unsubscribe');

// Advertisement Campaigns
Route::get('/offer/{slug}', [CampaignController::class, 'show'])->name('campaign.show');
Route::get('/offer/{campaign}/track', [CampaignController::class, 'trackAction'])->middleware('throttle:30,1')->name('campaign.track');
Route::post('/offer/{campaign}/lead', [CampaignController::class, 'submitLead'])
    ->middleware('throttle:5,1')
    ->name('campaign.lead');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// Reviews
Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('reviews.store');

// Client Safari Proposal Routes
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/proposal/{token}', [ProposalController::class, 'show'])->name('proposal.show');
    Route::post('/proposal/{token}/accept', [ProposalController::class, 'accept'])->name('proposal.accept');
    Route::post('/proposal/{token}/changes', [ProposalController::class, 'requestChanges'])->name('proposal.changes');
    Route::get('/proposal/{token}/pdf', [ProposalController::class, 'downloadPdf'])->name('proposal.pdf');
});

// Include Admin routes
require __DIR__.'/admin.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
