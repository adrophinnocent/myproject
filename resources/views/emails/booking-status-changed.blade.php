@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Booking Status Update') }}</h2>
<p>{{ __('Dear :name, the status of your booking has been updated.', ['name' => $booking->first_name]) }}</p>

@php
    $statusLabels = [
        'pending' => __('Pending Review'),
        'approved' => __('Approved - Awaiting Deposit'),
        'deposit_paid' => __('Deposit Received'),
        'confirmed' => __('Confirmed'),
        'completed' => __('Completed'),
        'cancelled' => __('Cancelled'),
    ];
@endphp

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">{{ __('Booking Details') }}</h3>
    <div class="detail-row">
        <span class="detail-label">{{ __('Booking Reference') }}</span>
        <span class="detail-value">{{ $booking->booking_reference }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('New Status') }}</span>
        <span class="detail-value">{{ $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Total Price') }}</span>
        <span class="detail-value">{{ $booking->formatted_total_price }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Amount Paid') }}</span>
        <span class="detail-value">{{ $booking->currency_symbol.number_format((float) $booking->deposit_paid, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Balance Due') }}</span>
        <span class="detail-value">{{ $booking->formatted_balance_amount }}</span>
    </div>
</div>

@if($booking->status === 'approved')
<p>{{ __('Your booking is approved! Please pay the deposit to secure your safari dates.') }}</p>
<a href="{{ route('booking.payment', $booking->token) }}" class="btn-primary">{{ __('Pay Deposit') }}</a>
@elseif($booking->status === 'cancelled')
<p>{{ __('If you have any questions about this cancellation, please contact our team.') }}</p>
@else
<p>{{ __('You can check your booking details and payment status anytime using the link below.') }}</p>
@endif

<a href="{{ route('booking.status', $booking->token) }}" class="btn-primary">{{ __('View My Booking') }}</a>

<p>{{ __('Warm regards') }},<br>{{ __('The Twina Safaris Team') }}</p>
@endsection
