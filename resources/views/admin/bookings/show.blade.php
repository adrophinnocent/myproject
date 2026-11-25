@extends('admin.layouts.app')

@section('title', 'Booking Management - ' . $booking->booking_reference)
@section('page-title', 'Booking Control Center')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-12">

    {{-- Notifications --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-bold shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Action Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.bookings.index') }}" class="w-12 h-12 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gold-600 hover:border-gold-500 transition-all group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $booking->booking_reference }}</h1>
                    {!! $booking->status_badge !!}
                </div>
                <p class="text-xs text-stone-500 font-medium mt-1">
                    Created {{ $booking->created_at->format('M d, Y @ H:i') }} • Ref Token: <span class="font-mono text-[11px] text-stone-600">{{ $booking->token }}</span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Download Documents --}}
            <a href="{{ route('admin.bookings.download-itinerary', $booking->id) }}" class="px-4 py-2.5 bg-stone-900 text-white rounded-xl font-bold text-xs hover:bg-black transition-colors flex items-center gap-1.5 shadow-xs">
                <span>📥 {{ __('PDF Voucher') }}</span>
            </a>
            <a href="{{ route('admin.bookings.download-invoice', $booking->id) }}" class="px-4 py-2.5 bg-stone-100 text-stone-800 rounded-xl font-bold text-xs hover:bg-stone-200 transition-colors flex items-center gap-1.5 border border-stone-200">
                <span>📄 {{ __('PDF Invoice') }}</span>
            </a>
        </div>
    </div>

    {{-- Workflow Approval / Decision Banner --}}
    @if($booking->status === 'pending')
    <div class="bg-amber-50/80 border-2 border-amber-300 rounded-[2rem] p-8 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-black text-amber-800 uppercase tracking-widest block mb-1">STEP 1: REVIEW & AVAILABILITY VERIFICATION</span>
            <h3 class="font-display text-2xl font-bold text-stone-900">Approve Booking Request & Send Deposit Payment Link</h3>
            <p class="text-xs text-stone-600 mt-1 max-w-2xl leading-relaxed">
                Check vehicle, guide, and hotel lodge availability for {{ $booking->travel_date ? $booking->travel_date->format('d M Y') : 'the requested dates' }}.
                Once verified, approve this booking to send the customer an automated deposit payment email & link.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-amber-200/80">
            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="flex-1 flex flex-col sm:flex-row items-center gap-3 w-full">
                @csrf
                <div class="flex items-center gap-2 bg-white px-4 py-3 rounded-2xl border border-amber-300 shrink-0">
                    <label class="text-xs font-bold text-stone-700 uppercase">Deposit %:</label>
                    <input type="number" name="deposit_percentage" value="{{ old('deposit_percentage', $booking->deposit_percentage ?? 20) }}" min="5" max="100" class="w-16 text-xs font-bold border-0 focus:ring-0 outline-none">
                </div>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-black px-8 py-3.5 rounded-2xl text-xs uppercase tracking-widest shadow-md transition-all flex items-center gap-2 shrink-0">
                    ✓ Approve & Send Payment Link
                </button>
            </form>

            <button onclick="document.getElementById('reject-modal').classList.toggle('hidden')" class="px-6 py-3.5 bg-rose-100 text-rose-800 hover:bg-rose-200 rounded-2xl font-bold text-xs uppercase tracking-wider transition-colors shrink-0">
                × Reject Request
            </button>
        </div>
    </div>
    @endif

    {{-- Reject Modal / Form --}}
    <div id="reject-modal" class="hidden p-6 bg-rose-50 border border-rose-200 rounded-2xl space-y-4">
        <h4 class="font-bold text-rose-900 text-sm">Reject Booking Request</h4>
        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="cancellation_reason" rows="3" required placeholder="Reason for rejection (e.g. Lodges fully booked on these dates...)" class="w-full bg-white border border-rose-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-rose-400 outline-none"></textarea>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-rose-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-rose-800">
                    Confirm Rejection
                </button>
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2.5 border border-stone-300 text-stone-600 rounded-xl text-xs font-bold">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Main Details --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- Reserved Tour Info --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm">Tour Package Details</h3>
                    <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest bg-gold-50 px-3 py-1 rounded-full border border-gold-100">
                        {{ $booking->bookable_item->category->name ?? 'Safari' }}
                    </span>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="w-full md:w-48 h-36 rounded-2xl overflow-hidden bg-stone-900 shrink-0">
                            <img src="{{ $booking->bookable_item->featured_image_url ?? asset('images/banners/hero_fallback.webp') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 space-y-3">
                            <h4 class="text-xl font-black text-gray-900 leading-tight">{{ $booking->bookable_item->title ?? 'Custom Safari' }}</h4>
                            <p class="text-xs text-stone-500 font-medium">
                                {{ __('Duration') }}: <strong>{{ $booking->bookable_item->duration_text ?? 'Custom' }}</strong> •
                                {{ __('Pickup') }}: <strong>{{ $booking->accommodation_preference ?: 'Arusha / Moshi' }}</strong>
                            </p>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-stone-100 text-xs">
                                <div>
                                    <span class="block text-[10px] text-stone-400 uppercase font-bold">Travel Date</span>
                                    <span class="font-bold text-stone-900">{{ $booking->travel_date ? $booking->travel_date->format('d M, Y') : 'TBD' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-stone-400 uppercase font-bold">Guests</span>
                                    <span class="font-bold text-stone-900">{{ $booking->number_of_adults }} Adults, {{ $booking->number_of_children }} Children</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-stone-400 uppercase font-bold">Country</span>
                                    <span class="font-bold text-stone-900">{{ $booking->country }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Selected Accommodation Package & Room Type --}}
            @if($booking->package_name || $booking->room_type_name || is_array($booking->selected_hotels_summary))
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-6">
                <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm pb-4 border-b border-gray-100">Selected Accommodation Package & Hotel Assignments</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    @if($booking->package_name)
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Accommodation Package</span>
                        <span class="font-bold text-amber-900 text-sm bg-amber-50 px-3 py-1 rounded-lg border border-amber-200/80 inline-block">{{ $booking->package_name }}</span>
                    </div>
                    @endif

                    @if($booking->room_type_name)
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Room Preference</span>
                        <span class="font-bold text-stone-900 text-sm bg-stone-100 px-3 py-1 rounded-lg inline-block">
                            {{ $booking->room_type_name }}
                            @if($booking->room_supplement_amount > 0)
                                (+${{ number_format($booking->room_supplement_amount, 2) }} supplement)
                            @endif
                        </span>
                    </div>
                    @endif
                </div>

                @if(is_array($booking->selected_hotels_summary) && count($booking->selected_hotels_summary) > 0)
                <div class="pt-4 border-t border-stone-100 space-y-2">
                    <span class="block text-[10px] font-black text-stone-400 uppercase tracking-wider mb-2">Nightly Assigned Hotels:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        @foreach($booking->selected_hotels_summary as $hs)
                        <div class="p-3 bg-stone-50 rounded-xl border border-stone-200 flex items-start gap-2">
                            <span class="font-bold text-amber-800 shrink-0">Day {{ $hs['day'] ?? $loop->iteration }}:</span>
                            <div>
                                <span class="font-bold text-stone-900 block">{{ $hs['hotel'] ?? 'Lodge' }}</span>
                                <span class="text-[10px] text-stone-500 capitalize">{{ $hs['category'] ?? 'Standard' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Traveler Details --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-6">
                <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm pb-4 border-b border-gray-100">Customer Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Full Name</span>
                        <span class="font-bold text-stone-900 text-sm">{{ $booking->full_name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Email Address</span>
                        <a href="mailto:{{ $booking->email }}" class="font-bold text-amber-700 text-sm underline">{{ $booking->email }}</a>
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Phone / WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}" target="_blank" class="font-bold text-stone-900 text-sm underline">
                            {{ $booking->phone }}
                        </a>
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Nationality</span>
                        <span class="font-bold text-stone-900 text-sm">{{ $booking->nationality }}</span>
                    </div>
                </div>

                @if($booking->special_requests)
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 text-xs">
                    <span class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-1">Special Requests / Notes</span>
                    <p class="text-stone-700 italic leading-relaxed">"{{ $booking->special_requests }}"</p>
                </div>
                @endif
            </div>

            {{-- Payment History & Record Payment --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-sm">Payment History</h3>
                    <button onclick="document.getElementById('manual-payment-form').classList.toggle('hidden')" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-xs">
                        + Record Manual Payment
                    </button>
                </div>

                {{-- Record Payment Form Modal --}}
                <div id="manual-payment-form" class="hidden p-6 bg-amber-50/50 border border-amber-200 rounded-2xl space-y-4">
                    <h4 class="font-bold text-amber-900 text-xs uppercase tracking-wider">Record Received Payment (Bank Transfer / M-Pesa / Cash)</h4>
                    <form action="{{ route('admin.bookings.record-payment', $booking) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <label class="block font-bold text-stone-700 mb-1">Amount Paid</label>
                                <input type="number" step="0.01" name="amount" value="{{ old('amount', $booking->calculated_balance_amount) }}" required class="w-full bg-white border border-stone-300 rounded-xl p-2.5 outline-none font-bold">
                            </div>
                            <div>
                                <label class="block font-bold text-stone-700 mb-1">Payment Gateway / Method</label>
                                <select name="gateway" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 outline-none font-bold">
                                    <option value="bank_transfer">Direct Bank Transfer</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="tigopesa">Tigo Pesa</option>
                                    <option value="airtelmoney">Airtel Money</option>
                                    <option value="cash">Cash / Office</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-stone-700 mb-1">Payment Type</label>
                                <select name="payment_type" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 outline-none font-bold">
                                    <option value="deposit">Deposit Payment</option>
                                    <option value="balance">Balance Payment</option>
                                    <option value="full">Full Trip Payment</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Transaction Ref / Notes</label>
                            <input type="text" name="notes" placeholder="e.g. CRDB Bank Slip #982134" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 text-xs outline-none">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-6 py-2.5 bg-amber-600 text-stone-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-xs">
                                Confirm & Record Payment
                            </button>
                            <button type="button" onclick="document.getElementById('manual-payment-form').classList.add('hidden')" class="px-4 py-2.5 border border-stone-300 text-stone-600 rounded-xl text-xs font-bold">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Payments Table --}}
                @if($booking->payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-stone-700">
                            <thead>
                                <tr class="border-b border-stone-200 text-stone-400 uppercase font-black text-[10px]">
                                    <th class="py-2.5">Ref</th>
                                    <th class="py-2.5">Gateway</th>
                                    <th class="py-2.5">Type</th>
                                    <th class="py-2.5">Amount</th>
                                    <th class="py-2.5">Status</th>
                                    <th class="py-2.5">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach($booking->payments as $p)
                                <tr>
                                    <td class="py-3 font-mono font-bold">{{ $p->transaction_reference }}</td>
                                    <td class="py-3 uppercase font-semibold">{{ $p->gateway }}</td>
                                    <td class="py-3 uppercase">{{ $p->payment_type }}</td>
                                    <td class="py-3 font-bold text-stone-900">{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                                    <td class="py-3">
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase {{ $p->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-stone-500">{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-stone-400 italic text-center py-6 border border-dashed border-stone-200 rounded-2xl">
                        No transactions recorded for this booking yet.
                    </p>
                @endif
            </div>

        </div>

        {{-- Sidebar Billing Card --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-[#0a0703] rounded-[2.5rem] p-8 text-white space-y-6 shadow-2xl relative overflow-hidden">
                <div>
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest block mb-1">TOTAL ORDER VALUE</span>
                    <div class="text-4xl font-display font-black text-amber-400">{{ $booking->formatted_total_price }}</div>
                </div>

                <div class="space-y-3 pt-6 border-t border-white/10 text-xs text-stone-300">
                    <div class="flex justify-between">
                        <span class="text-stone-400">Deposit Required ({{ number_format($booking->deposit_percentage, 0) }}%):</span>
                        <span class="font-bold text-amber-400">{{ $booking->formatted_deposit_required }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Deposit Paid:</span>
                        <span class="font-bold text-emerald-400">{{ $booking->currency_symbol }}{{ number_format($booking->deposit_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Remaining Balance:</span>
                        <span class="font-bold text-white">{{ $booking->formatted_balance_amount }}</span>
                    </div>
                </div>

                <div class="pt-4 space-y-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}?text=Hello%20{{ urlencode($booking->first_name) }},%20regarding%20your%20Twina%20Safaris%20booking%20{{ $booking->booking_reference }}"
                       target="_blank"
                       class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-black py-3.5 rounded-2xl transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                        <span>💬 Chat Client on WhatsApp</span>
                    </a>
                </div>
            </div>

            {{-- Quick Status Update Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 space-y-4">
                <h4 class="font-black text-stone-900 uppercase tracking-wider text-xs">Update Record Data</h4>
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Trip Total Price ($)</label>
                        <input type="number" step="0.01" name="total_price" value="{{ old('total_price', $booking->total_price) }}" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-bold outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Booking Status</label>
                        <select name="status" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-bold outline-none">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved (Ready for Deposit)</option>
                            <option value="deposit_paid" {{ $booking->status == 'deposit_paid' ? 'selected' : '' }}>Deposit Paid</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs font-bold outline-none">
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="deposit_paid" {{ $booking->payment_status == 'deposit_paid' ? 'selected' : '' }}>Deposit Paid</option>
                            <option value="fully_paid" {{ $booking->payment_status == 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                            <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Admin Internal Notes</label>
                        <textarea name="admin_notes" rows="3" class="w-full bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs outline-none">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-stone-900 hover:bg-black text-white font-bold py-3.5 rounded-xl text-xs uppercase tracking-wider transition-colors">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
