@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Thank you, :name!', ['name' => $contact['name'] ?? 'traveler']) }}</h2>
<p>{{ __('We have received your message and our team will get back to you within 24 hours.') }}</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">{{ __('Your Message Summary') }}</h3>
    <div class="detail-row">
        <span class="detail-label">{{ __('Subject') }}</span>
        <span class="detail-value">{{ $contact['subject'] ?? 'General Inquiry' }}</span>
    </div>
    @if(!empty($contact['message']))
    <div class="detail-row">
        <span class="detail-label">{{ __('Message') }}</span>
        <span class="detail-value">{{ \Illuminate\Support\Str::limit($contact['message'], 200) }}</span>
    </div>
    @endif
</div>

<p>{{ __('In the meantime, feel free to explore our tours and start planning your adventure.') }}</p>

<a href="{{ route('tours.index') }}" class="btn-primary">{{ __('Explore Our Tours') }}</a>

<p>{{ __('Warm regards') }},<br>{{ __('The Twina Safaris Team') }}</p>
@endsection
