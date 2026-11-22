<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    public function index()
    {
        $relations = [];
        try {
            if (Schema::hasColumn('faqs', 'tour_id')) {
                $relations = ['tour', 'destination', 'category'];
            }
        } catch (\Throwable $e) {}

        $faqs = Faq::with($relations)->orderBy('order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $tours = Tour::orderBy('title')->get();
        $destinations = Destination::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.faqs.create', compact('tours', 'destinations', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'tour_id' => 'nullable|exists:tours,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'category_id' => 'nullable|exists:categories,id',
            'primary_keyword' => 'nullable|string|max:255',
            'is_verified_traveler' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        // Auto-run migration if column is missing on live database
        if (!Schema::hasColumn('faqs', 'tour_id')) {
            try {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/2026_10_09_000000_enhance_faqs_table.php',
                    '--force' => true
                ]);
            } catch (\Throwable $e) {
                Log::error('Auto-migration enhance_faqs_table failed: ' . $e->getMessage());
            }
        }

        $payload = [
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if (Schema::hasColumn('faqs', 'tour_id')) {
            $payload['tour_id'] = $validated['tour_id'] ?? null;
            $payload['destination_id'] = $validated['destination_id'] ?? null;
            $payload['category_id'] = $validated['category_id'] ?? null;
            $payload['primary_keyword'] = $validated['primary_keyword'] ?? null;
            $payload['is_verified_traveler'] = $request->has('is_verified_traveler');
        }

        Faq::create($payload);

        return redirect()->route('admin.faqs.index')->with('success', 'Published Q&A created successfully');
    }

    public function edit(Faq $faq)
    {
        $tours = Tour::orderBy('title')->get();
        $destinations = Destination::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.faqs.edit', compact('faq', 'tours', 'destinations', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'tour_id' => 'nullable|exists:tours,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'category_id' => 'nullable|exists:categories,id',
            'primary_keyword' => 'nullable|string|max:255',
            'is_verified_traveler' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if (!Schema::hasColumn('faqs', 'tour_id')) {
            try {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/2026_10_09_000000_enhance_faqs_table.php',
                    '--force' => true
                ]);
            } catch (\Throwable $e) {
                Log::error('Auto-migration enhance_faqs_table failed: ' . $e->getMessage());
            }
        }

        $payload = [
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if (Schema::hasColumn('faqs', 'tour_id')) {
            $payload['tour_id'] = $validated['tour_id'] ?? null;
            $payload['destination_id'] = $validated['destination_id'] ?? null;
            $payload['category_id'] = $validated['category_id'] ?? null;
            $payload['primary_keyword'] = $validated['primary_keyword'] ?? null;
            $payload['is_verified_traveler'] = $request->has('is_verified_traveler');
        }

        $faq->update($payload);

        return redirect()->route('admin.faqs.index')->with('success', 'Q&A updated successfully');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'Q&A deleted successfully');
    }
}
