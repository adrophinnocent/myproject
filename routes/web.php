<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Login Routes
Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Currency & Language Switchers
Route::get('/currency/{code}', function ($code) {
    session(['currency' => $code]);
    return back();
})->name('currency.switch');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'de', 'fr', 'es', 'it', 'zh', 'nl'])) {
        session(['locale' => $locale]);
        session()->save(); // Force immediate persistence
    }
    return back();
})->name('lang.switch');

// Public Routes
Route::get('/tours', [App\Http\Controllers\Public\TourController::class, 'index'])->name('tours.index');

// Public Booking Routes
Route::get('/tours/{tour:slug}/book', [App\Http\Controllers\Public\BookingController::class, 'create'])->name('booking.create');
Route::post('/tours/{tour:slug}/book', [App\Http\Controllers\Public\BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{reference}', [App\Http\Controllers\Public\BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/download/{reference}', [App\Http\Controllers\Public\BookingController::class, 'downloadItinerary'])->name('booking.download');

// Dynamic Tour Show Route (Robust version with .html)
Route::get('/tours/{type}/{slug}.html', [App\Http\Controllers\Public\TourController::class, 'show'])->name('tours.show');

// Blog Route with .html
Route::get('/blog/{slug}.html', [App\Http\Controllers\Public\BlogController::class, 'show'])->name('blog.show');

// Redirects
Route::get('/tours/{type}/{slug}', function($type, $slug) {
    return redirect()->to("/tours/{$type}/{$slug}.html", 301);
});
Route::get('/blog/{slug}', function($slug) {
    if (str_ends_with($slug, '.html')) return abort(404);
    return redirect()->to("/blog/{$slug}.html", 301);
});

Route::get('/faqs', [App\Http\Controllers\Public\FaqController::class, 'index'])->name('faqs.index');

Route::get('/trip-plan', [App\Http\Controllers\Public\TripPlanController::class, 'index'])->name('trip-plan.index');
Route::post('/trip-plan', [App\Http\Controllers\Public\TripPlanController::class, 'store'])->name('trip-plan.store');
Route::get('/trip-plan/{id}', [App\Http\Controllers\Public\TripPlanController::class, 'show'])->name('trip-plan.show');
Route::get('/trip-plan/{id}/download', [App\Http\Controllers\Public\TripPlanController::class, 'downloadPdf'])->name('trip-plan.download');
Route::post('/trip-plan/{id}/accept', [App\Http\Controllers\Public\TripPlanController::class, 'accept'])->name('trip-plan.accept');
Route::post('/trip-plan/{id}/changes', [App\Http\Controllers\Public\TripPlanController::class, 'requestChanges'])->name('trip-plan.changes');

Route::post('/newsletter/subscribe', [App\Http\Controllers\Public\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Advertisement Campaigns
Route::get('/offer/{slug}', [App\Http\Controllers\Public\CampaignController::class, 'show'])->name('campaign.show');
Route::get('/offer/{campaign}/track', [App\Http\Controllers\Public\CampaignController::class, 'trackAction'])->name('campaign.track');
Route::post('/offer/{campaign}/lead', [App\Http\Controllers\Public\CampaignController::class, 'submitLead'])->name('campaign.lead');

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\Public\SitemapController::class, 'index']);

// Reviews
Route::post('/reviews', [App\Http\Controllers\Public\ReviewController::class, 'store'])->name('reviews.store');

// Include Admin routes
require __DIR__.'/admin.php';

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
