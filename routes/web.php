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
Route::get('/tours/{slug}/book', [App\Http\Controllers\Public\BookingController::class, 'create'])->name('booking.create');
Route::post('/tours/{slug}/book', [App\Http\Controllers\Public\BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{reference}', [App\Http\Controllers\Public\BookingController::class, 'success'])->name('booking.success');

// Dynamic Tour Show Route (Must be after specific routes like /book)
Route::get('/tours/{type}/{slug}.html', [App\Http\Controllers\Public\TourController::class, 'show'])->name('tours.show');

// Tour Inquiries
Route::post('/tours/{tour:id}/inquiry', [App\Http\Controllers\Public\InquiryController::class, 'store'])->name('tours.inquiry');

// Public AI Assistant
Route::post('/ai-assistant/chat', [App\Http\Controllers\Public\AiChatController::class, 'sendMessage'])->name('ai-assistant.chat');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/contact', [App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\Public\ContactController::class, 'send'])->name('contact.send');

Route::get('/gallery', [App\Http\Controllers\Public\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [App\Http\Controllers\Public\GalleryController::class, 'show'])->name('gallery.show');

Route::get('/blog', [App\Http\Controllers\Public\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [App\Http\Controllers\Public\BlogController::class, 'show'])->name('blog.show');

Route::get('/faqs', [App\Http\Controllers\Public\FaqController::class, 'index'])->name('faqs.index');

Route::get('/trip-plan', [App\Http\Controllers\Public\TripPlanController::class, 'index'])->name('trip-plan.index');
Route::post('/trip-plan', [App\Http\Controllers\Public\TripPlanController::class, 'store'])->name('trip-plan.store');

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
