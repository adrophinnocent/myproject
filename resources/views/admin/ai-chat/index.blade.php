@extends('admin.layouts.app')

@section('title', 'AI Safari Assistant')
@section('page-title', 'Your AI Business Partner')

@section('content')
<div class="max-w-5xl mx-auto h-[75vh] flex flex-col bg-white rounded-[2rem] shadow-2xl border border-gold-500/10 overflow-hidden" x-data="aiChat()">

    {{-- Header --}}
    <div class="bg-safari-dark p-6 flex items-center justify-between border-b border-gold-500/20">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-2xl flex items-center justify-center shadow-lg shadow-gold-500/20">
                <svg class="w-7 h-7 text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="text-white font-black uppercase tracking-widest text-sm">Twina AI Assistant</h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-gold-400 text-[10px] font-bold uppercase">Online & Ready to Assist</span>
                </div>
            </div>
        </div>
        <button @click="messages = []" class="text-white/40 hover:text-white transition-colors text-xs font-bold uppercase tracking-tighter">Clear History</button>
    </div>

    {{-- Chat Body --}}
    <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#fcfaf7]" id="chat-container">
        <template x-for="(msg, index) in messages" :key="index">
            <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                <div :class="msg.role === 'user'
                    ? 'bg-safari-dark text-white rounded-2xl rounded-tr-none px-6 py-4 max-w-[80%] shadow-xl'
                    : 'bg-white text-gray-800 rounded-2xl rounded-tl-none px-6 py-4 max-w-[80%] shadow-md border border-gray-100'"
                >
                    <p class="text-sm leading-relaxed" x-text="msg.content"></p>
                </div>
            </div>
        </template>

        <div x-show="loading" class="flex justify-start">
            <div class="bg-white rounded-2xl rounded-tl-none px-6 py-4 shadow-md border border-gray-100">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gold-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gold-500 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                    <div class="w-2 h-2 bg-gold-600 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="p-6 bg-white border-t border-gray-100">
        <form @submit.prevent="sendMessage()" class="flex gap-4">
            <input type="text" x-model="userInput" placeholder="Ask me to write a blog, reply to a guest, or translate a tour..."
                   class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all shadow-inner"
                   :disabled="loading">
            <button type="submit"
                    class="bg-gold-500 hover:bg-gold-600 text-safari-dark px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-gold-500/20 transition-all active:scale-95 flex items-center gap-2"
                    :disabled="loading || !userInput.trim()">
                Send
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
        <div class="mt-4 flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
            <template x-for="hint in suggestions">
                <button @click="userInput = hint" class="whitespace-nowrap px-4 py-2 bg-gold-50 text-gold-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-gold-200 hover:bg-gold-500 hover:text-white transition-all shadow-sm">
                    <span x-text="hint"></span>
                </button>
            </template>
        </div>
    </div>
</div>

<script>
function aiChat() {
    return {
        userInput: '',
        messages: [
            { role: 'assistant', content: "Jambo! I'm your Twina Safaris partner. How can I help you today?" }
        ],
        loading: false,
        suggestions: [
            "Write a blog post about Serengeti Migration",
            "Draft a professional reply to a honeymoon inquiry",
            "Translate 'Luxury 7-day Safari' to German",
            "Explain current TANAPA fees"
        ],
        async sendMessage() {
            if (!this.userInput.trim()) return;

            const userMsg = this.userInput;
            this.messages.push({ role: 'user', content: userMsg });
            this.userInput = '';
            this.loading = true;

            this.scrollToBottom();

            try {
                const history = this.messages.slice(0, -1).map(m => ({
                    role: m.role,
                    content: m.content
                }));

                const response = await fetch('{{ route('admin.ai-assistant.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: userMsg,
                        history: history
                    })
                });

                const data = await response.json();
                this.messages.push({ role: 'assistant', content: data.response });
            } catch (error) {
                this.messages.push({ role: 'assistant', content: "Pole! I lost connection to the bush. Please try again." });
            } finally {
                this.loading = false;
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            setTimeout(() => {
                const container = document.getElementById('chat-container');
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
}
</script>
@endsection
