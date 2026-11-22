<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiVisibilityCheck;
use App\Models\SearchAnswer;
use App\Models\SearchContentAssignment;
use App\Models\SearchQuestion;
use App\Models\SearchQuestionCategory;
use App\Models\Tour;
use App\Services\AIGenerator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AiSearchIntelligenceController extends Controller
{
    protected function ensureTablesExist()
    {
        if (Schema::hasTable('search_questions')) {
            return;
        }

        try {
            if (!Schema::hasTable('search_question_categories')) {
                Schema::create('search_question_categories', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->text('description')->nullable();
                    $table->integer('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('search_questions')) {
                Schema::create('search_questions', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('category_id')->constrained('search_question_categories')->onDelete('cascade');
                    $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('set null');
                    $table->text('question');
                    $table->string('slug')->unique();
                    $table->string('primary_keyword')->nullable();
                    $table->json('secondary_keywords')->nullable();
                    $table->string('search_intent')->default('informational');
                    $table->string('target_country')->nullable();
                    $table->string('suggested_content_type')->default('tour');
                    $table->string('status')->default('draft');
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamps();
                    $table->softDeletes();
                });
            }

            if (!Schema::hasTable('search_answers')) {
                Schema::create('search_answers', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
                    $table->string('title')->nullable();
                    $table->longText('content');
                    $table->text('meta_description')->nullable();
                    $table->json('suggested_links')->nullable();
                    $table->json('related_questions')->nullable();
                    $table->boolean('is_approved')->default(false);
                    $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamp('approved_at')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }

            if (!Schema::hasTable('search_content_assignments')) {
                Schema::create('search_content_assignments', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
                    $table->string('target_type')->default('faq');
                    $table->unsignedBigInteger('target_id')->nullable();
                    $table->boolean('is_published')->default(false);
                    $table->timestamp('published_at')->nullable();
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('ai_visibility_checks')) {
                Schema::create('ai_visibility_checks', function (Blueprint $table) {
                    $table->id();
                    $table->text('query_text');
                    $table->string('ai_platform');
                    $table->boolean('is_brand_mentioned')->default(false);
                    $table->boolean('is_source_cited')->default(false);
                    $table->json('competitors_mentioned')->nullable();
                    $table->text('source_link')->nullable();
                    $table->date('check_date');
                    $table->text('notes')->nullable();
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamps();
                });
            }

            if (DB::table('search_question_categories')->count() === 0) {
                $categories = [
                    'Tanzania Safari', 'Serengeti', 'Ngorongoro', 'Tarangire', 'Lake Manyara',
                    'Kilimanjaro Climbing', 'Machame Route', 'Lemosho Route', 'Honeymoon Safaris',
                    'Zanzibar Holidays', 'Family Safaris', 'Private Safaris', 'Safari Pricing',
                    'Booking and Travel Information', 'Company Information',
                ];

                foreach ($categories as $index => $catName) {
                    DB::table('search_question_categories')->insert([
                        'name' => $catName,
                        'slug' => Str::slug($catName),
                        'description' => 'Tourist search questions regarding ' . $catName,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Auto table creation error: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $this->ensureTablesExist();

        $totalQuestions = SearchQuestion::count();
        $draftCount = SearchQuestion::where('status', 'draft')->count();
        $approvedCount = SearchQuestion::where('status', 'approved')->count();
        $publishedCount = SearchQuestion::where('status', 'published')->count();
        $archivedCount = SearchQuestion::where('status', 'archived')->count();

        $categories = SearchQuestionCategory::withCount('questions')->orderBy('sort_order')->get();

        $visibilityStats = AiVisibilityCheck::selectRaw('
            COUNT(*) as total_checks,
            SUM(CASE WHEN is_brand_mentioned = 1 THEN 1 ELSE 0 END) as brand_mentions,
            SUM(CASE WHEN is_source_cited = 1 THEN 1 ELSE 0 END) as source_cites
        ')->first();

        $mentionRate = ($visibilityStats && $visibilityStats->total_checks > 0)
            ? round(($visibilityStats->brand_mentions / $visibilityStats->total_checks) * 100, 1)
            : 0;

        $recentQuestions = SearchQuestion::with(['category', 'tour', 'answer'])
            ->latest()
            ->take(8)
            ->get();

        $recentVisibilityChecks = AiVisibilityCheck::latest('check_date')
            ->take(5)
            ->get();

        return view('admin.ai-search.dashboard', compact(
            'totalQuestions',
            'draftCount',
            'approvedCount',
            'publishedCount',
            'archivedCount',
            'categories',
            'visibilityStats',
            'mentionRate',
            'recentQuestions',
            'recentVisibilityChecks'
        ));
    }

    public function questionsIndex(Request $request)
    {
        $this->ensureTablesExist();

        $query = SearchQuestion::with(['category', 'tour', 'answer', 'assignments']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('primary_keyword', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search_intent')) {
            $query->where('search_intent', $request->search_intent);
        }

        if ($request->filled('target_country')) {
            $query->where('target_country', $request->target_country);
        }

        $questions = $query->latest()->paginate(15)->withQueryString();
        $categories = SearchQuestionCategory::where('is_active', true)->orderBy('name')->get();
        $tours = Tour::published()->orderBy('title')->get(['id', 'title']);

        return view('admin.ai-search.questions.index', compact('questions', 'categories', 'tours'));
    }

    public function createQuestion()
    {
        $this->ensureTablesExist();

        $categories = SearchQuestionCategory::where('is_active', true)->orderBy('name')->get();
        $tours = Tour::published()->orderBy('title')->get(['id', 'title']);

        return view('admin.ai-search.questions.create', compact('categories', 'tours'));
    }

    public function storeQuestion(Request $request)
    {
        $this->ensureTablesExist();

        $validated = $request->validate([
            'category_id' => 'required|exists:search_question_categories,id',
            'tour_id' => 'nullable|exists:tours,id',
            'question' => 'required|string|max:1000',
            'primary_keyword' => 'nullable|string|max:255',
            'secondary_keywords' => 'nullable|array',
            'search_intent' => 'required|string|in:informational,commercial,transactional,navigational',
            'target_country' => 'nullable|string|max:100',
            'suggested_content_type' => 'required|string|in:tour,destination,blog,faq,company',
            'status' => 'required|string|in:draft,approved,published,archived',
            'answer_title' => 'nullable|string|max:255',
            'answer_content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'related_questions' => 'nullable|array',
        ]);

        $validated['created_by'] = auth()->id();
        $question = SearchQuestion::create($validated);

        if (!empty($validated['answer_content'])) {
            $answer = SearchAnswer::create([
                'search_question_id' => $question->id,
                'title' => $validated['answer_title'] ?? $question->question,
                'content' => $validated['answer_content'],
                'meta_description' => $validated['meta_description'] ?? null,
                'related_questions' => $validated['related_questions'] ?? [],
                'is_approved' => in_array($validated['status'], ['approved', 'published']),
                'approved_by' => in_array($validated['status'], ['approved', 'published']) ? auth()->id() : null,
                'approved_at' => in_array($validated['status'], ['approved', 'published']) ? now() : null,
            ]);

            if ($validated['status'] === 'published') {
                SearchContentAssignment::create([
                    'search_question_id' => $question->id,
                    'target_type' => $validated['suggested_content_type'],
                    'target_id' => $question->tour_id,
                    'is_published' => true,
                    'published_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.ai-search.questions.index')->with('success', 'Search Question & Answer created successfully!');
    }

    public function editQuestion(SearchQuestion $question)
    {
        $this->ensureTablesExist();

        $question->load(['category', 'tour', 'answer', 'assignments']);
        $categories = SearchQuestionCategory::where('is_active', true)->orderBy('name')->get();
        $tours = Tour::published()->orderBy('title')->get(['id', 'title']);

        return view('admin.ai-search.questions.edit', compact('question', 'categories', 'tours'));
    }

    public function updateQuestion(Request $request, SearchQuestion $question)
    {
        $this->ensureTablesExist();

        $validated = $request->validate([
            'category_id' => 'required|exists:search_question_categories,id',
            'tour_id' => 'nullable|exists:tours,id',
            'question' => 'required|string|max:1000',
            'primary_keyword' => 'nullable|string|max:255',
            'secondary_keywords' => 'nullable|array',
            'search_intent' => 'required|string|in:informational,commercial,transactional,navigational',
            'target_country' => 'nullable|string|max:100',
            'suggested_content_type' => 'required|string|in:tour,destination,blog,faq,company',
            'status' => 'required|string|in:draft,approved,published,archived',
            'answer_title' => 'nullable|string|max:255',
            'answer_content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'related_questions' => 'nullable|array',
            'target_id' => 'nullable|integer',
        ]);

        $question->update($validated);

        if (!empty($validated['answer_content'])) {
            $isApproved = in_array($validated['status'], ['approved', 'published']);

            $question->answer()->updateOrCreate(
                ['search_question_id' => $question->id],
                [
                    'title' => $validated['answer_title'] ?? $question->question,
                    'content' => $validated['answer_content'],
                    'meta_description' => $validated['meta_description'] ?? null,
                    'related_questions' => $validated['related_questions'] ?? [],
                    'is_approved' => $isApproved,
                    'approved_by' => $isApproved ? auth()->id() : null,
                    'approved_at' => $isApproved ? now() : null,
                ]
            );

            if ($validated['status'] === 'published') {
                SearchContentAssignment::updateOrCreate(
                    ['search_question_id' => $question->id],
                    [
                        'target_type' => $validated['suggested_content_type'],
                        'target_id' => $validated['target_id'] ?? $question->tour_id,
                        'is_published' => true,
                        'published_at' => now(),
                    ]
                );
            }
        }

        return redirect()->route('admin.ai-search.questions.index')->with('success', 'Search Question & Answer updated successfully!');
    }

    public function destroyQuestion(SearchQuestion $question)
    {
        $question->delete();

        return redirect()->route('admin.ai-search.questions.index')->with('success', 'Search Question deleted.');
    }

    public function generateAiAnswer(Request $request, AIGenerator $aiGenerator)
    {
        $request->validate([
            'question' => 'required|string',
            'category_id' => 'nullable|exists:search_question_categories,id',
            'tour_id' => 'nullable|exists:tours,id',
        ]);

        $categoryName = 'Tanzania Safari';
        if ($request->filled('category_id')) {
            $cat = SearchQuestionCategory::find($request->category_id);
            if ($cat) $categoryName = $cat->name;
        }

        $tourTitle = null;
        if ($request->filled('tour_id')) {
            $tour = Tour::find($request->tour_id);
            if ($tour) $tourTitle = $tour->title;
        }

        $aiResult = $aiGenerator->generateSearchAnswerAndSeo($request->question, $categoryName, $tourTitle);

        return response()->json([
            'success' => true,
            'data' => $aiResult,
        ]);
    }

    public function visibilityIndex(Request $request)
    {
        $this->ensureTablesExist();

        $query = AiVisibilityCheck::query();

        if ($request->filled('platform')) {
            $query->where('ai_platform', $request->platform);
        }

        if ($request->filled('search')) {
            $query->where('query_text', 'like', "%{$request->search}%");
        }

        $checks = $query->latest('check_date')->paginate(15)->withQueryString();

        $stats = [
            'total' => AiVisibilityCheck::count(),
            'chatgpt' => AiVisibilityCheck::where('ai_platform', 'ChatGPT')->count(),
            'gemini' => AiVisibilityCheck::where('ai_platform', 'Gemini')->count(),
            'perplexity' => AiVisibilityCheck::where('ai_platform', 'Perplexity')->count(),
            'mentioned_count' => AiVisibilityCheck::where('is_brand_mentioned', true)->count(),
            'cited_count' => AiVisibilityCheck::where('is_source_cited', true)->count(),
        ];

        return view('admin.ai-search.visibility.index', compact('checks', 'stats'));
    }

    public function storeVisibilityCheck(Request $request)
    {
        $this->ensureTablesExist();

        $validated = $request->validate([
            'query_text' => 'required|string|max:1000',
            'ai_platform' => 'required|string|in:ChatGPT,Gemini,Perplexity,Copilot,Claude,Other',
            'is_brand_mentioned' => 'nullable|boolean',
            'is_source_cited' => 'nullable|boolean',
            'competitors_mentioned' => 'nullable|array',
            'source_link' => 'nullable|url',
            'check_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['is_brand_mentioned'] = $request->has('is_brand_mentioned');
        $validated['is_source_cited'] = $request->has('is_source_cited');
        $validated['created_by'] = auth()->id();

        AiVisibilityCheck::create($validated);

        return redirect()->route('admin.ai-search.visibility.index')->with('success', 'AI Visibility Test recorded successfully!');
    }

    public function destroyVisibilityCheck(AiVisibilityCheck $check)
    {
        $check->delete();

        return redirect()->route('admin.ai-search.visibility.index')->with('success', 'Visibility record removed.');
    }
}
