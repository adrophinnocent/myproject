@extends('admin.layouts.app')

@section('title', 'AI Knowledge Base')
@section('page-title', 'AI Knowledge Base (Nondo)')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">AI Knowledge Base</h1>
            <p class="text-sm text-gray-500">Manage the facts and information your AI assistant uses to answer guest questions.</p>
        </div>
        <a href="{{ route('admin.ai-knowledge.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add New Fact
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content Summary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($knowledge as $fact)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-full uppercase">
                            {{ $fact->category ?: 'General' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $fact->topic }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="max-w-xs truncate">{{ $fact->content }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($fact->is_active)
                            <span class="flex items-center text-green-600 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span> Active
                            </span>
                        @else
                            <span class="flex items-center text-gray-400 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.ai-knowledge.edit', $fact) }}" class="text-amber-600 hover:text-amber-900">Edit</a>
                            <form action="{{ route('admin.ai-knowledge.destroy', $fact) }}" method="POST" onsubmit="return confirm('Delete this fact?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                        No facts added yet. Add your first "Nondo" to help the AI learn about Twina Safaris!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $knowledge->links() }}
    </div>
</div>
@endsection
