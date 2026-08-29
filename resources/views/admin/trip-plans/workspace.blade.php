@extends('admin.layouts.app')

@section('title', 'Trip Plan Workspace')

@section('content')
<div x-data="{
    tab: 'workflow',
    status: '{{ $tripPlan->status }}',
    itinerary: {{ json_encode($tripPlan->itinerary_data ?: [['day' => 1, 'title' => '', 'desc' => '']]) }},
    inclusions: {{ json_encode($tripPlan->inclusions_data ?: ['Professional Guide']) }},
    exclusions: {{ json_encode($tripPlan->exclusions_data ?: ['Flights', 'Insurance']) }},

    addDay() {
        this.itinerary.push({ day: this.itinerary.length + 1, title: '', desc: '' });
    },
    removeDay(index) {
        this.itinerary.splice(index, 1);
        this.itinerary.forEach((d, i) => d.day = i + 1);
    },
    addItem(type) {
        this[type].push('');
    },
    removeItem(type, index) {
        this[type].splice(index, 1);
    }
}" class="max-w-7xl mx-auto">

    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- Left: Workspace Main Area --}}
        <div class="flex-1 w-full space-y-8">

            {{-- Header Card --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <div class="flex flex-wrap justify-between items-start gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-gold-100 text-gold-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-gold-200">
                                #{{ $tripPlan->id }}
                            </span>
                            <h1 class="text-3xl font-display font-black text-gray-900 tracking-tight">
                                {{ $tripPlan->trip_title ?: 'Planning Stage: ' . $tripPlan->name }}
                            </h1>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">
                            Requested on {{ $tripPlan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Current Status</div>
                            @php
                                $statusClasses = [
                                    'new' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'reviewing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'preparing_plan' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'sent' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'accepted' => 'bg-green-100 text-green-700 border-green-200',
                                    'booking' => 'bg-teal-100 text-teal-700 border-teal-200',
                                    'closed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                ];
                                $class = $statusClasses[$tripPlan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <span class="px-4 py-2 text-xs font-black uppercase tracking-widest rounded-xl border {{ $class }}">
                                {{ str_replace('_', ' ', $tripPlan->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quick Progress Tracker --}}
                <div class="mt-10 border-t border-gray-100 pt-8">
                    <div class="flex items-center justify-between">
                        @php
                            $steps = [
                                ['id' => 'new', 'label' => 'New'],
                                ['id' => 'reviewing', 'label' => 'Review'],
                                ['id' => 'preparing_plan', 'label' => 'Prepare'],
                                ['id' => 'sent', 'label' => 'Send'],
                                ['id' => 'accepted', 'label' => 'Accept'],
                                ['id' => 'booking', 'label' => 'Book'],
                            ];
                            $reached = false;
                        @endphp
                        @foreach($steps as $index => $step)
                            <div class="flex flex-col items-center relative flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all border-2
                                    {{ $tripPlan->status === $step['id'] ? 'bg-gold-500 border-gold-500 text-white shadow-lg shadow-gold-500/30' :
                                       ($reached ? 'bg-white border-gray-200 text-gray-300' : 'bg-green-500 border-green-500 text-white') }}">
                                    @if(!$reached && $tripPlan->status !== $step['id'])
                                        ✓
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest mt-2 {{ $tripPlan->status === $step['id'] ? 'text-gold-600' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
                                </span>
                                @if($tripPlan->status === $step['id']) @php $reached = true; @endphp @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="flex gap-4 border-b border-gray-100 overflow-x-auto no-scrollbar">
                <button @click="tab = 'workflow'" :class="tab === 'workflow' ? 'border-gold-500 text-gold-600' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 border-b-2 font-black uppercase text-[11px] tracking-widest transition-all">
                    Workspace Workflow
                </button>
                <button @click="tab = 'itinerary'" :class="tab === 'itinerary' ? 'border-gold-500 text-gold-600' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 border-b-2 font-black uppercase text-[11px] tracking-widest transition-all">
                    Build Itinerary
                </button>
                <button @click="tab = 'pricing'" :class="tab === 'pricing' ? 'border-gold-500 text-gold-600' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 border-b-2 font-black uppercase text-[11px] tracking-widest transition-all">
                    Quotation & Pricing
                </button>
                <button @click="tab = 'messages'" :class="tab === 'messages' ? 'border-gold-500 text-gold-600' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 border-b-2 font-black uppercase text-[11px] tracking-widest transition-all">
                    Messages ({{ $tripPlan->messages->count() }})
                </button>
            </div>

            {{-- TAB: WORKFLOW --}}
            <div x-show="tab === 'workflow'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Status Control --}}
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-gray-900 mb-6">Workflow Control</h3>
                        <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">Change Status To</label>
                                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold">
                                    <option value="reviewing" {{ $tripPlan->status === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                    <option value="preparing_plan" {{ $tripPlan->status === 'preparing_plan' ? 'selected' : '' }}>Preparing Plan</option>
                                    <option value="sent" {{ $tripPlan->status === 'sent' ? 'selected' : '' }}>Sent to Customer</option>
                                    <option value="closed" {{ $tripPlan->status === 'closed' ? 'selected' : '' }}>Closed / Completed</option>
                                    <option value="declined" {{ $tripPlan->status === 'declined' ? 'selected' : '' }}>Declined</option>
                                    <option value="cancelled" {{ $tripPlan->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-safari-dark text-white py-4 rounded-xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-black transition-all">
                                Update Workflow Status
                            </button>
                        </form>
                    </div>

                    {{-- Next Actions Suggestions --}}
                    <div class="bg-amber-50 rounded-3xl p-8 border border-amber-100">
                        <h3 class="font-display text-xl font-bold text-amber-900 mb-6">Recommended Next Step</h3>
                        <div class="space-y-4">
                            @if($tripPlan->status === 'new')
                                <div class="p-4 bg-white rounded-2xl border border-amber-200 shadow-sm">
                                    <p class="text-sm text-amber-800 font-medium mb-4">Start by acknowledging the request and moving to "Reviewing".</p>
                                    <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="reviewing">
                                        <button class="w-full bg-amber-500 text-white py-3 rounded-xl font-bold text-xs uppercase">Move to Review</button>
                                    </form>
                                </div>
                            @elseif($tripPlan->status === 'accepted')
                                <div class="p-4 bg-white rounded-2xl border border-green-200 shadow-sm">
                                    <p class="text-sm text-green-800 font-medium mb-4">The customer has accepted! Convert this to an official booking now.</p>
                                    <form action="{{ route('admin.trip-plans.convert-to-booking', $tripPlan) }}" method="POST">
                                        @csrf
                                        <button class="w-full bg-green-600 text-white py-3 rounded-xl font-bold text-xs uppercase">Create Booking</button>
                                    </form>
                                </div>
                            @else
                                <p class="text-sm text-amber-800 font-medium">Continue building the itinerary and setting the price under the tabs above.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Internal Notes --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-6">Internal Admin Notes</h3>
                    <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $tripPlan->status }}">
                        <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-medium outline-none focus:border-gold-500 mb-4" placeholder="Private notes for the team...">{{ $tripPlan->admin_notes }}</textarea>
                        <button type="submit" class="bg-gold-500 text-safari-dark px-8 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg">Save Notes</button>
                    </form>
                </div>
            </div>

            {{-- TAB: ITINERARY --}}
            <div x-show="tab === 'itinerary'" class="space-y-6">
                <form action="{{ route('admin.trip-plans.save-itinerary', $tripPlan) }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">Trip Title</label>
                                <input type="text" name="trip_title" value="{{ $tripPlan->trip_title ?: $tripPlan->name . '\'s Safari Adventure' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">Start Date</label>
                                    <input type="date" name="travel_date" value="{{ $tripPlan->travel_date ? $tripPlan->travel_date->format('Y-m-d') : '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">End Date</label>
                                    <input type="date" name="end_date" value="{{ $tripPlan->end_date ? $tripPlan->end_date->format('Y-m-d') : '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Day by Day Builder --}}
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-display text-2xl font-black text-gray-900 uppercase tracking-tight">Daily Itinerary</h3>
                            <button type="button" @click="addDay()" class="bg-gold-500 text-safari-dark px-4 py-2 rounded-lg font-black uppercase text-[10px] tracking-widest shadow-md">+ Add Day</button>
                        </div>

                        <template x-for="(day, index) in itinerary" :key="index">
                            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative group">
                                <button type="button" @click="removeDay(index)" class="absolute top-6 right-6 text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                <div class="flex gap-6">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 flex flex-col items-center justify-center shrink-0 border border-gray-200">
                                        <span class="text-[8px] font-black uppercase text-gray-400">Day</span>
                                        <span class="text-lg font-black text-gray-900 leading-none" x-text="day.day"></span>
                                    </div>
                                    <div class="flex-1 space-y-4">
                                        <input type="hidden" :name="'itinerary_data['+index+'][day]'" :value="day.day">
                                        <input type="text" :name="'itinerary_data['+index+'][title]'" x-model="day.title" placeholder="Title (e.g. Arrival in Arusha)" class="w-full bg-gray-50 border-0 border-b-2 border-gray-100 focus:border-gold-500 font-bold px-0 py-2 outline-none transition-all">
                                        <textarea :name="'itinerary_data['+index+'][desc]'" x-model="day.desc" rows="3" placeholder="Description of the day's activities..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium outline-none focus:border-gold-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Inclusions & Exclusions --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-display text-xl font-bold text-green-700">What's Included</h3>
                                <button type="button" @click="addItem('inclusions')" class="text-[10px] font-black uppercase text-green-600">+ Add</button>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(item, index) in inclusions" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" name="inclusions_data[]" x-model="inclusions[index]" class="flex-1 bg-white border border-gray-200 rounded-lg px-4 py-2 text-xs font-bold">
                                        <button type="button" @click="removeItem('inclusions', index)" class="text-red-400 px-2">✕</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-display text-xl font-bold text-red-700">Excluded</h3>
                                <button type="button" @click="addItem('exclusions')" class="text-[10px] font-black uppercase text-red-600">+ Add</button>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(item, index) in exclusions" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" name="exclusions_data[]" x-model="exclusions[index]" class="flex-1 bg-white border border-gray-200 rounded-lg px-4 py-2 text-xs font-bold">
                                        <button type="button" @click="removeItem('exclusions', index)" class="text-red-400 px-2">✕</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Terms & Conditions</h3>
                        <textarea name="terms_conditions" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-medium">{{ $tripPlan->terms_conditions }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-safari-dark text-white px-12 py-4 rounded-full font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">
                            Save Itinerary Content
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB: PRICING --}}
            <div x-show="tab === 'pricing'" class="space-y-6">
                <form action="{{ route('admin.trip-plans.save-pricing', $tripPlan) }}" method="POST" class="bg-white rounded-3xl p-10 border border-gray-100 shadow-sm max-w-2xl">
                    @csrf
                    <h3 class="font-display text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Price Calculation</h3>

                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Price Per Person</label>
                            <div class="flex items-center gap-3">
                                <span class="font-black text-lg text-gray-400">$</span>
                                <input type="number" name="price_per_person" value="{{ $tripPlan->price_per_person }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-black text-xl text-safari-dark">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Currency</label>
                            <select name="currency" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-black text-sm">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Discount Amount</label>
                            <input type="number" name="discount_amount" value="{{ $tripPlan->discount_amount }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold text-sm text-red-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Taxes / Fees</label>
                            <input type="number" name="tax_amount" value="{{ $tripPlan->tax_amount }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold text-sm">
                        </div>
                    </div>

                    <div class="space-y-6 bg-gray-50 p-6 rounded-2xl mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black uppercase text-gray-500 tracking-widest">Est. Total ({{ $tripPlan->adults + $tripPlan->children }} Pax)</span>
                            <span class="text-2xl font-black text-safari-dark">${{ number_format($tripPlan->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black uppercase text-gold-600 tracking-widest block">Deposit Required</span>
                                <input type="number" name="deposit_amount" value="{{ $tripPlan->deposit_amount }}" class="bg-white border border-gold-200 rounded-lg px-3 py-1 text-sm font-black w-32">
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest block">Balance Remaining</span>
                                <span class="text-lg font-black text-gray-700">${{ number_format($tripPlan->balance_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-safari-dark text-white py-5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-2xl hover:scale-[1.02] transition-all">
                        Update Pricing & Quotation
                    </button>
                </form>
            </div>

            {{-- TAB: MESSAGES --}}
            <div x-show="tab === 'messages'" class="space-y-6">
                {{-- Message History --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[600px]">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Conversation History</h3>
                    </div>

                    <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-gray-50/20">
                        @forelse($tripPlan->messages as $msg)
                            <div class="flex {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[80%] space-y-1">
                                    <div class="px-5 py-3 rounded-2xl shadow-sm text-sm font-medium {{ $msg->sender_type === 'admin' ? 'bg-safari-dark text-white rounded-tr-none' : 'bg-white text-gray-700 border border-gray-100 rounded-tl-none' }}">
                                        {{ $msg->message }}
                                    </div>
                                    <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest {{ $msg->sender_type === 'admin' ? 'text-right' : 'text-left' }}">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20">
                                <p class="text-gray-400 italic text-sm">No messages yet. Send your first response below.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-6 border-t border-gray-100 bg-white">
                        <form action="{{ route('admin.trip-plans.send-message', $tripPlan) }}" method="POST" class="flex gap-4">
                            @csrf
                            <input type="text" name="message" required placeholder="Type your message to the customer..." class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-6 py-4 text-sm font-medium outline-none focus:border-gold-500">
                            <button type="submit" class="bg-gold-500 text-safari-dark px-8 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Sidebar - Customer Details --}}
        <aside class="w-full lg:w-80 space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-6">Customer Profile</h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4 pb-6 border-b border-gray-50">
                        <div class="w-12 h-12 rounded-2xl bg-gold-500 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-gold-500/20">
                            {{ substr($tripPlan->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-gray-900 leading-tight">{{ $tripPlan->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $tripPlan->nationality ?: 'Nationality Unknown' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <a href="mailto:{{ $tripPlan->email }}" class="flex items-center gap-3 text-sm text-gray-600 hover:text-gold-600 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                            <span class="font-bold truncate">{{ $tripPlan->email }}</span>
                        </a>
                        @if($tripPlan->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/','',$tripPlan->phone) }}" target="_blank" class="flex items-center gap-3 text-sm text-green-600 hover:text-green-700 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></div>
                            <span class="font-bold truncate">{{ $tripPlan->phone }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-6">Original Request</h4>
                <div class="space-y-5 text-sm">
                    <div class="flex justify-between"><span class="text-gray-400">Duration</span><span class="font-bold text-gray-900">{{ $tripPlan->duration }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Pax</span><span class="font-bold text-gray-900">{{ $tripPlan->adults }} Adults, {{ $tripPlan->children }} Child</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Style</span><span class="font-bold text-gray-900 capitalize">{{ $tripPlan->travel_style }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Budget</span><span class="font-bold text-gold-600">{{ $tripPlan->budget_range }}</span></div>

                    <div class="pt-4 border-t border-gray-50">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Message</p>
                        <p class="text-xs text-gray-600 leading-relaxed italic">"{{ $tripPlan->message }}"</p>
                    </div>

                    @if($tripPlan->interests)
                    <div class="pt-4">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Interests</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tripPlan->interests as $interest)
                                <span class="px-2 py-1 bg-gray-50 rounded-lg text-[9px] font-black uppercase text-gray-500">{{ $interest }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </aside>

    </div>

    {{-- Bottom Floating Action Bar --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-full max-w-4xl px-4 z-40">
        <div class="bg-safari-dark/95 backdrop-blur-xl border border-white/10 rounded-[2rem] p-4 flex items-center justify-between shadow-2xl">
            <div class="flex items-center gap-4 ml-4">
                @if($tripPlan->sent_at)
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Last Sent</span>
                        <span class="text-white text-[10px] font-bold">{{ $tripPlan->sent_at->format('d M, H:i') }}</span>
                    </div>
                @endif
                @if($tripPlan->viewed_at)
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-green-400 uppercase tracking-widest">Viewed by Client</span>
                        <span class="text-white text-[10px] font-bold">{{ $tripPlan->viewed_at->format('d M, H:i') }}</span>
                    </div>
                @endif
            </div>

            <div class="flex gap-3">
                <a href="{{ route('trip-plan.show', $tripPlan->id) }}" target="_blank" class="bg-white/10 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all">Preview Plan</a>

                @if($tripPlan->status === 'preparing_plan' || $tripPlan->status === 'reviewing')
                <form action="{{ route('admin.trip-plans.update-status', $tripPlan) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="sent">
                    <button class="bg-gold-500 text-safari-dark px-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gold-500/20 hover:scale-105 transition-all">Send to Customer</button>
                </form>
                @endif

                @if($tripPlan->status === 'accepted')
                <form action="{{ route('admin.trip-plans.convert-to-booking', $tripPlan) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-600/20 hover:scale-105 transition-all">Create Final Booking</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
