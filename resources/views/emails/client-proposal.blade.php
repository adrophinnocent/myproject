@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Dear :name,', ['name' => $proposal->client_name]) }}</h2>
<p>{{ __('We are delighted to present your personalized safari proposal and itinerary for :title.', ['title' => $proposal->title]) }}</p>

@if($proposal->welcome_message)
<div class="detail-box" style="margin-top: 15px; margin-bottom: 20px; font-style: italic;">
    <p style="margin: 0;">"{{ $proposal->welcome_message }}"</p>
</div>
@endif

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">{{ __('Proposal Summary') }}</h3>
    <div class="detail-row">
        <span class="detail-label">{{ __('Safari Title') }}</span>
        <span class="detail-value">{{ $proposal->title }}</span>
    </div>
    @if($proposal->start_date)
    <div class="detail-row">
        <span class="detail-label">{{ __('Travel Dates') }}</span>
        <span class="detail-value">{{ $proposal->start_date->format('M d, Y') }} @if($proposal->end_date) - {{ $proposal->end_date->format('M d, Y') }} @endif</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">{{ __('Duration') }}</span>
        <span class="detail-value">{{ $proposal->duration_days }} Days</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Travelers') }}</span>
        <span class="detail-value">{{ $proposal->adults }} Adults @if($proposal->children > 0), {{ $proposal->children }} Children @endif</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">{{ __('Total Price') }}</span>
        <span class="detail-value" style="font-weight: bold; color: #D4AF37;">{{ $proposal->formatted_total_price }}</span>
    </div>
</div>

<p style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
    <a href="{{ $proposal->public_url }}" class="btn-primary" style="display: inline-block; background-color: #D4AF37; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: bold;">
        {{ __('View Interactive Proposal') }}
    </a>
</p>

<p>{{ __('You can review the full itinerary, request changes, accept the proposal directly online, or download the attached PDF.') }}</p>

<p>{{ __('Warm regards,') }}<br>{{ __('Twina Safaris Team') }}</p>
@endsection
