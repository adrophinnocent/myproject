@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Great News, :name!', ['name' => $booking->first_name]) }}</h2>
<p>{{ __('Your safari booking has been approved! We are excited to welcome you to Tanzania for an unforgettable adventure.') }}</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">{{ __('Approved Booking Details') }}</h3>
    <div class="detail-row">
        <span class="detail-label">{{ __('Booking Reference') }}</span>
        <span class="detail-value">{{ $booking->booking_reference }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Tour') }}</span>
        <span class="detail-value">{{ $booking->tour->translate('title') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Travel Date') }}</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F j, Y') }}</span>
    </div>
</div>

<p>{{ __('Our team will be in touch soon with detailed itinerary information and preparation tips for your trip!') }}</p>

<a href="{{ route('home') }}" class="btn-primary">{{ __('View Our Website') }}</a>

<p>{{ __('Warm regards') }},<br>{{ __('The Twina Safaris Team') }}</p>
@endsection
