@extends('admin.layouts.app')

@section('title', 'Trip Plan Workspace')

@section('content')
<div x-data="{
    tab: 'details',
    itinerary: {{ json_encode($tripPlan->itinerary_data ?: [['day' => 1, 'title' => '', 'desc' => '']]) }},
    inclusions: {{ json_encode($tripPlan->inclusions_data ?: ['Professional Guide', 'Park Fees', 'Transfers']) }},
    exclusions: {{ json_encode($tripPlan->exclusions_data ?: ['International Flights', 'Travel Insurance', 'Tips']) }},

    addDay() {
        this.itinerary.push({ day: this.itinerary.length + 1, title: '', desc: '' });
    },
    removeDay(index) {
        if(confirm('Delete this day?')) {
            this.itinerary.splice(index, 1);
            this.itinerary.forEach((d, i) => d.day = i + 1);
        }
    },
    addItem(type) {
        this[type].push('');
    },
    removeItem(type, index) {
        this[type].splice(index, 1);
    }
}" class="max-w-7xl mx-auto pb-20">

    {{-- Main Workspace Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">

        {{-- Workspace Header --}}
        <div class="bg-safari-dark p-8 md:p-12 text-white relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-3xl bg-gold-500 flex items-center justify-center text-safari-dark font-black text-3xl shadow-2xl transform -rotate-3">
                        {{ substr($tripPlan->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-3xl md:text-4xl font-display font-black tracking-tight">{{ $tripPlan->name }}</h1>
                            <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10">ID #{{ $tripPlan->id }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-white/50 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><span class="text-gold-500">📍</span> {{ $tripPlan->nationality ?: 'Global' }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="flex items-center gap-1.5"><span class="text-gold-500">📅</span> {{ $tripPlan->created_at->format('d M Y') }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <div class="flex items-center gap-2">
                                @php
                                    $statusColors = [
                                        'new' => 'bg-amber-500', 'reviewing' => 'bg-blue-500', 'preparing_plan' => 'bg-indigo-500',
                                        'sent' => 'bg-purple-500', 'viewed' => 'bg-pink-500', 'accepted' => 'bg-green-500',
                                        'booking' => 'bg-teal-500', 'declined' => 'bg-red-500',
                                    ];
                                @endphp
                                <span class="w-2.5 h-2.5 rounded-full {{ $statusColors[$tripPlan->status] ?? 'bg-gray-500' }} animate-pulse"></span>
                                <span class="text-white">{{ str_replace('_', ' ', $tripPlan->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Critical Action Buttons --}}
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <a href="{{ route('trip-plan.show', $tripPlan->id) }}" target="_blank" class="flex-1 md:flex-none bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest border border-white/10 transition-all text-center">
                        Preview Plan
                    </a>

                    @if($tripPlan->status === 'new')
                        <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST" class="flex-1 md:flex-none">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="reviewing">
                            <button class="w-full bg-gold-500 hover:bg-gold-600 text-safari-dark px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-2xl transition-all">Start Working</button>
                        </form>
                    @elseif($tripPlan->status === 'accepted')
                        <form action="{{ route('admin.trip-plans.convert-to-booking', $tripPlan) }}" method="POST" class="flex-1 md:flex-none">
                            @csrf
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-2xl transition-all">Create Booking</button>
                        </form>
                    @elseif(in_array($tripPlan->status, ['reviewing', 'preparing_plan', 'changes_requested']))
                        <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST" class="flex-1 md:flex-none">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="sent">
                            <button class="w-full bg-gold-500 hover:bg-gold-600 text-safari-dark px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-2xl transition-all">Send to Client</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-gold-500/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Workspace Tabs --}}
        <div class="flex border-b border-gray-100 bg-gray-50/50 overflow-x-auto no-scrollbar">
            @php $tabs = [['id' => 'details', 'label' => '1. Requirement'], ['id' => 'itinerary', 'label' => '2. Build Ratiba'], ['id' => 'pricing', 'label' => '3. Price Quote'], ['id' => 'messages', 'label' => '4. Messenger']]; @endphp
            @foreach($tabs as $t)
                <button @click="tab = '{{ $t['id'] }}'"
                        :class="tab === '{{ $t['id'] }}' ? 'bg-white text-gold-600 border-b-4 border-gold-500 shadow-sm' : 'text-gray-400 hover:bg-gray-100'"
                        class="flex-1 min-w-[150px] py-6 font-black uppercase text-[11px] tracking-widest transition-all border-r border-gray-100 last:border-0">
                    {{ $t['label'] }}
                </button>
            @endforeach
        </div>

        <div class="p-8 md:p-12">

            {{-- TAB: REQUIREMENT --}}
            <div x-show="tab === 'details'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 space-y-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Travel Date</p>
                            <p class="font-bold text-gray-900">{{ $tripPlan->travel_date ? $tripPlan->travel_date->format('d M Y') : 'Flexible' }}</p>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Stay Duration</p>
                            <p class="font-bold text-gray-900">{{ $tripPlan->duration ?: 'Not specified' }}</p>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Pax Count</p>
                            <p class="font-bold text-gray-900">{{ $tripPlan->adults }} Adults, {{ $tripPlan->children }} Child</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Client Message & Preferences</h4>
                        <div class="p-8 bg-amber-50/50 rounded-[2rem] border border-amber-100 text-gray-700 italic leading-relaxed">
                            "{{ $tripPlan->message }}"
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6">Internal Notes (Team Only)</h4>
                        <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $tripPlan->status }}">
                            <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-medium outline-none focus:border-gold-500 mb-4" placeholder="Mfano: Mteja anapenda luxury zaidi...">{{ $tripPlan->admin_notes }}</textarea>
                            <button type="submit" class="bg-safari-dark text-white px-8 py-3 rounded-xl font-black uppercase text-[9px] tracking-widest">Update Notes</button>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Client Contact</h4>
                        <div class="space-y-4">
                            <a href="mailto:{{ $tripPlan->email }}" class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-gray-200 hover:border-gold-500 transition-all">
                                <div class="w-10 h-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600">📧</div>
                                <span class="text-xs font-bold truncate">{{ $tripPlan->email }}</span>
                            </a>
                            @if($tripPlan->phone)
                            <a href="https://wa.me/{{ preg_replace('/\D/','',$tripPlan->phone) }}" target="_blank" class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-gray-200 hover:border-green-500 transition-all">
                                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-lg">💬</div>
                                <span class="text-xs font-bold">{{ $tripPlan->phone }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: ITINERARY BUILDER --}}
            <div x-show="tab === 'itinerary'" x-transition>
                <form action="{{ route('admin.trip-plans.save-itinerary', $tripPlan) }}" method="POST" class="space-y-12">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Trip Title</label>
                            <input type="text" name="trip_title" value="{{ $tripPlan->trip_title ?: $tripPlan->name . '\'s Safari Adventure' }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 text-lg font-black text-gray-900 outline-none focus:border-gold-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Start Date</label>
                                <input type="date" name="travel_date" value="{{ $tripPlan->travel_date ? $tripPlan->travel_date->format('Y-m-d') : '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 font-bold outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ $tripPlan->end_date ? $tripPlan->end_date->format('Y-m-d') : '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 font-bold outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-display text-3xl font-black text-gray-900 uppercase tracking-tight">Daily Program</h3>
                            <button type="button" @click="addDay()" class="bg-gold-500 text-safari-dark px-8 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl">+ Add New Day</button>
                        </div>

                        <div class="space-y-6">
                            <template x-for="(day, index) in itinerary" :key="index">
                                <div class="bg-gray-50 rounded-[2.5rem] p-10 border border-gray-100 relative group hover:bg-white hover:shadow-2xl hover:shadow-gold-500/5 transition-all">
                                    <button type="button" @click="removeDay(index)" class="absolute top-8 right-8 text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <div class="flex gap-10">
                                        <div class="w-20 h-20 rounded-3xl bg-white flex flex-col items-center justify-center shrink-0 border-2 border-gray-100 shadow-sm">
                                            <span class="text-[10px] font-black uppercase text-gray-400 mb-1">Day</span>
                                            <span class="text-3xl font-black text-gold-600 leading-none" x-text="day.day"></span>
                                        </div>
                                        <div class="flex-1 space-y-6">
                                            <input type="hidden" :name="'itinerary_data['+index+'][day]'" :value="day.day">
                                            <div>
                                                <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2 block ml-1">Activity Headline</label>
                                                <input type="text" :name="'itinerary_data['+index+'][title]'" x-model="day.title" placeholder="E.g. Full Day in Ngorongoro Crater" class="w-full bg-white border-0 border-b-2 border-gray-200 focus:border-gold-500 font-black text-xl px-0 py-2 outline-none transition-all">
                                            </div>
                                            <div>
                                                <label class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2 block ml-1">The Experience</label>
                                                <textarea :name="'itinerary_data['+index+'][desc]'" x-model="day.desc" rows="4" placeholder="What will the client experience? Mention accommodation and meals..." class="w-full bg-white border border-gray-200 rounded-3xl px-8 py-6 text-sm font-medium outline-none focus:border-gold-500 transition-all"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-12 border-t border-gray-100">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center"><h3 class="font-display text-2xl font-black text-green-700 uppercase tracking-tight">Included</h3><button type="button" @click="addItem('inclusions')" class="text-[10px] font-black uppercase text-green-600 hover:underline">+ Add Row</button></div>
                            <div class="space-y-3">
                                <template x-for="(item, index) in inclusions" :key="index">
                                    <div class="flex gap-3">
                                        <input type="text" name="inclusions_data[]" x-model="inclusions[index]" class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-bold outline-none focus:bg-white focus:border-green-500 transition-all">
                                        <button type="button" @click="removeItem('inclusions', index)" class="text-red-300 hover:text-red-500 px-2">✕</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center"><h3 class="font-display text-2xl font-black text-red-700 uppercase tracking-tight">Excluded</h3><button type="button" @click="addItem('exclusions')" class="text-[10px] font-black uppercase text-red-600 hover:underline">+ Add Row</button></div>
                            <div class="space-y-3">
                                <template x-for="(item, index) in exclusions" :key="index">
                                    <div class="flex gap-3">
                                        <input type="text" name="exclusions_data[]" x-model="exclusions[index]" class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-bold outline-none focus:bg-white focus:border-red-500 transition-all">
                                        <button type="button" @click="removeItem('exclusions', index)" class="text-red-300 hover:text-red-500 px-2">✕</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center pt-8">
                        <button type="submit" class="bg-safari-dark text-white px-20 py-6 rounded-full font-black uppercase tracking-[0.3em] text-xs shadow-2xl hover:scale-105 active:scale-95 transition-all">
                            Save Itinerary & Program
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB: PRICING --}}
            <div x-show="tab === 'pricing'" x-transition class="max-w-3xl mx-auto py-10">
                <form action="{{ route('admin.trip-plans.save-pricing', $tripPlan) }}" method="POST" class="space-y-10">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-1">Cost Per Person</label>
                            <div class="relative">
                                <span class="absolute left-8 top-1/2 -translate-y-1/2 font-black text-2xl text-gold-500">$</span>
                                <input type="number" name="price_per_person" value="{{ $tripPlan->price_per_person }}" class="w-full bg-white border-2 border-gray-100 rounded-[2rem] pl-16 pr-8 py-8 font-black text-4xl text-safari-dark outline-none focus:border-gold-500 transition-all shadow-sm">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-1">Currency</label>
                            <select name="currency" class="w-full bg-white border-2 border-gray-100 rounded-[2rem] px-8 py-8 font-black text-xl outline-none cursor-pointer">
                                <option value="USD" {{ $tripPlan->currency === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ $tripPlan->currency === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="GBP" {{ $tripPlan->currency === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 p-10 bg-gray-50 rounded-[3rem] border border-gray-100">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Special Discount</label>
                            <input type="number" name="discount_amount" value="{{ $tripPlan->discount_amount }}" class="w-full bg-white border border-gray-200 rounded-2xl px-6 py-4 font-black text-red-600 outline-none">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Taxes & Fees</label>
                            <input type="number" name="tax_amount" value="{{ $tripPlan->tax_amount }}" class="w-full bg-white border border-gray-200 rounded-2xl px-6 py-4 font-black text-gray-900 outline-none">
                        </div>
                    </div>

                    <div class="bg-safari-dark p-12 rounded-[3.5rem] text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gold-500/10 rounded-full -mr-20 -mt-20"></div>
                        <div class="relative z-10 space-y-12">
                            <div class="flex justify-between items-end border-b border-white/10 pb-10">
                                <div>
                                    <h4 class="text-[11px] font-black uppercase text-gold-500 tracking-[0.4em] mb-4">Total Quote Value</h4>
                                    <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest">Calculated for {{ $tripPlan->group_size ?: ($tripPlan->adults + $tripPlan->children) }} travelers</p>
                                </div>
                                <span class="text-6xl font-display font-black tracking-tighter text-gold-400">${{ number_format($tripPlan->total_price, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black uppercase text-white/40 tracking-widest">Deposit Required</label>
                                    <input type="number" name="deposit_amount" value="{{ $tripPlan->deposit_amount }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-2xl font-black text-gold-500 outline-none focus:bg-white/10 transition-all">
                                </div>
                                <div class="flex flex-col justify-end text-right">
                                    <span class="text-[10px] font-black uppercase text-white/40 tracking-widest mb-2">Balance after Deposit</span>
                                    <span class="text-3xl font-black text-white">${{ number_format($tripPlan->balance_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="bg-gold-500 text-safari-dark px-20 py-6 rounded-full font-black uppercase tracking-[0.3em] text-xs shadow-2xl hover:scale-105 active:scale-95 transition-all">
                            Update Quotation
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB: MESSENGER --}}
            <div x-show="tab === 'messages'" x-transition>
                <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[750px]">
                    <div class="p-10 border-b border-gray-50 bg-[#fcfaf7] flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-3 h-3 rounded-full bg-green-500 animate-ping"></div>
                            <h3 class="font-black text-gray-900 uppercase tracking-tighter text-xl">Direct Message Thread</h3>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-12 space-y-10 bg-gray-50/30 no-scrollbar" id="message-container">
                        @forelse($tripPlan->messages as $msg)
                            <div class="flex {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] space-y-3">
                                    <div class="px-8 py-6 rounded-[2.5rem] shadow-xl text-base font-medium leading-relaxed {{ $msg->sender_type === 'admin' ? 'bg-safari-dark text-white rounded-tr-none' : 'bg-white text-gray-700 border border-gray-100 rounded-tl-none' }}">
                                        {{ $msg->message }}
                                    </div>
                                    <div class="flex items-center gap-3 px-4 {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $msg->created_at->format('d M, H:i') }}</span>
                                        @if($msg->sender_type === 'admin') <span class="text-gold-500 text-[10px]">✔</span> @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-40">
                                <p class="text-gray-400 italic font-medium">Ujumbe utatokea hapa ukianza kuchat na mteja.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-10 border-t border-gray-50 bg-white">
                        <form action="{{ route('admin.trip-plans.send-message', $tripPlan) }}" method="POST" class="flex gap-6">
                            @csrf
                            <input type="text" name="message" required placeholder="Andika ujumbe wako hapa..." class="flex-1 bg-gray-50 border-2 border-gray-100 rounded-3xl px-10 py-6 text-sm font-medium outline-none focus:border-gold-500 transition-all">
                            <button type="submit" class="bg-safari-dark text-white px-12 rounded-3xl font-black uppercase text-[10px] tracking-widest shadow-2xl hover:bg-black transition-all">Send Now</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>
@endsection
