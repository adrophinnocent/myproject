@extends('emails.layouts.app')

@section('content')
<h2>Thank You for Your Payment, {{ $booking->first_name }}!</h2>
<p>We have successfully received your payment for your safari booking. Your booking is now confirmed!</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">Payment & Booking Details</h3>
    <div class="detail-row">
        <span class="detail-label">Booking Reference</span>
        <span class="detail-value">{{ $booking->booking_reference }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Tour</span>
        <span class="detail-value">{{ $booking->tour->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Amount Paid</span>
        <span class="detail-value">${{ number_format($booking->total_price, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Payment Status</span>
        <span class="detail-value" style="color: #22c55e;">{{ ucfirst($booking->payment_status) }}</span>
    </div>
</div>

<p>We will send your detailed itinerary and travel preparation guide soon!</p>
<p>Warm regards,<br>The Twina Safaris Team</p>
@endsection
