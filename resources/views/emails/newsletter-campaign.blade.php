@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Hello :name!', ['name' => $subscriberName ?: 'traveler']) }}</h2>

{{-- Content is composed by an admin in the campaign editor. --}}
{!! $campaignContent !!}

<p>{{ __('Warm regards') }},<br>{{ __('The Twina Safaris Team') }}</p>

<p style="font-size: 12px; color: #999;">
    {{ __('You are receiving this email because you subscribed to Twina Safaris updates.') }}
    @if($unsubscribeUrl)
        <a href="{{ $unsubscribeUrl }}" style="color: #999; text-decoration: underline;">{{ __('Unsubscribe') }}</a>
    @else
        {{ __('To stop receiving these emails, contact us at') }}
        {{ \App\Models\Setting::publicEmail() }}.
    @endif
</p>
@endsection
