@extends('emails.layouts.app')

@section('content')
<h2>Great News, {{ $booking->first_name }}!</h2>
<p>Your safari booking has been approved! We are excited to welcome you to Tanzania for an unforgettable adventure.</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">Approved Booking Details</h3>
    <div class="detail-row">
        <span class="detail-label">Booking Reference</span>
        <span class="detail-value">{{ $booking->booking_reference }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Tour</span>
        <span class="detail-value">{{ $booking->tour->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Travel Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F j, Y') }}</span>
    </div>
</div>

<p>Our team will be in touch soon with detailed itinerary information and preparation tips for your trip!</p>

<a href="{{ route('home') }}" class="btn-primary">View Our Website</a>

<p>Warm regards,<br>The Twina Safaris Team</p>
@endsection
