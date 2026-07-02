<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledge;
use Illuminate\Http\Request;

class AiKnowledgeController extends Controller
{
    public function index()
    {
        $knowledge = AiKnowledge::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.ai-knowledge.index', compact('knowledge'));
    }

    public function create()
    {
        return view('admin.ai-knowledge.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'topic' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $validated['is_active'] = $request->has('is_active');

        AiKnowledge::create($validated);

        return redirect()->route('admin.ai-knowledge.index')->with('success', 'Fact added to AI Knowledge base!');
    }

    public function edit(AiKnowledge $aiKnowledge)
    {
        return view('admin.ai-knowledge.edit', ['fact' => $aiKnowledge]);
    }

    public function update(Request $request, AiKnowledge $aiKnowledge)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'topic' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $aiKnowledge->update($validated);

        return redirect()->route('admin.ai-knowledge.index')->with('success', 'AI Knowledge updated!');
    }

    public function destroy(AiKnowledge $aiKnowledge)
    {
        $aiKnowledge->delete();
        return redirect()->route('admin.ai-knowledge.index')->with('success', 'Fact removed from AI knowledge.');
    }
}
