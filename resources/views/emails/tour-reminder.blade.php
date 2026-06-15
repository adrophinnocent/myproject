@extends('emails.layouts.app')

@section('content')
<h2>Your Safari is Coming Up Soon, {{ $booking->first_name }}!</h2>
<p>We can't wait to welcome you to Tanzania! Here's a quick reminder about your upcoming adventure:</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">Trip Reminder</h3>
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

<p>Make sure you have all your travel documents ready, including passport, visas, and travel insurance. If you have any last-minute questions, feel free to reach out!</p>

<p>See you soon in Tanzania!<br>The Twina Safaris Team</p>
@endsection
