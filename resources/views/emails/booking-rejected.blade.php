@extends('emails.layouts.app')

@section('content')
<h2>Update on Your Booking, {{ $booking->first_name }}</h2>
<p>We regret to inform you that your recent booking request cannot be confirmed at this time.</p>

<div class="detail-box">
    <div class="detail-row">
        <span class="detail-label">Booking Reference</span>
        <span class="detail-value">{{ $booking->booking_reference }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Tour</span>
        <span class="detail-value">{{ $booking->tour->title }}</span>
    </div>
</div>

<p>If you have any questions or would like to discuss alternative dates or tours, please don't hesitate to contact us. We would love to help you plan your Tanzanian adventure!</p>

<a href="{{ route('contact.index') }}" class="btn-primary">Contact Us</a>

<p>Best regards,<br>The Twina Safaris Team</p>
@endsection
