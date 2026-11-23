@extends('emails.layouts.app')

@section('content')
<h2>{{ __('Karibu, :name!', ['name' => $subscriber->name ?: 'traveler']) }}</h2>
<p>{{ __('You asked to receive safari news, travel tips and exclusive offers from Twina Safaris. Please confirm your email address to activate your subscription.') }}</p>

<a href="{{ route('newsletter.confirm', $subscriber->confirmation_token) }}" class="btn-primary">{{ __('Confirm My Subscription') }}</a>

<p style="font-size: 13px; color: #777;">{{ __('If the button does not work, copy and paste this link into your browser:') }}<br>
{{ route('newsletter.confirm', $subscriber->confirmation_token) }}</p>

<p style="font-size: 13px; color: #777;">{{ __('If you did not request this subscription, you can safely ignore this email and you will not be subscribed.') }}</p>

<p>{{ __('Warm regards') }},<br>{{ __('The Twina Safaris Team') }}</p>
@endsection
