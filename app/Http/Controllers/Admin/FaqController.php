<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with(['tour', 'destination', 'category'])->orderBy('order')->get();
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

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'tour_id' => $validated['tour_id'] ?? null,
            'destination_id' => $validated['destination_id'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'primary_keyword' => $validated['primary_keyword'] ?? null,
            'is_verified_traveler' => $request->has('is_verified_traveler'),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

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

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'tour_id' => $validated['tour_id'] ?? null,
            'destination_id' => $validated['destination_id'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'primary_keyword' => $validated['primary_keyword'] ?? null,
            'is_verified_traveler' => $request->has('is_verified_traveler'),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'Q&A updated successfully');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'Q&A deleted successfully');
    }
}
