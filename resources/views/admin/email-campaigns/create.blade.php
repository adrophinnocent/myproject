@extends('admin.layouts.app')

@section('title', 'New Email Campaign')
@section('page-title', 'Create New Campaign')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.email-campaigns.index') }}" class="text-sm text-gray-500 hover:text-amber-600 transition-colors flex items-center gap-1">
            <span>←</span> Back to Subscribers
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-safari-dark p-6 text-white flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">Send Email Broadcast</h3>
                <p class="text-white/60 text-xs mt-1">This email will be sent to all <b>{{ $totalActive }}</b> active subscribers.</p>
            </div>
            <div class="text-[#F59E0B]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <form action="{{ route('admin.email-campaigns.send') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Email Subject</label>
                <input type="text" name="subject" required
                       class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20"
                       placeholder="e.g. 🦁 20% Early-Bird Discount for Serengeti 2026!">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest">Email Content (HTML Supported)</label>
                <textarea name="content" rows="12" required
                          class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-amber-500/20 font-mono text-sm"
                          placeholder="Dear Explorer, we have an amazing deal for you..."></textarea>
                <p class="text-[10px] text-gray-400 mt-2">Tip: Use an HTML template to make your email look professional.</p>
            </div>

            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        <b>Important:</b> Once you click "Launch Campaign", the emails will be processed. Ensure your SMTP settings are configured in the <code>.env</code> file.
                    </p>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-[#F59E0B] hover:bg-[#d98506] text-[#1a1209] font-bold px-10 py-4 rounded-xl shadow-lg transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                    Launch Campaign Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
