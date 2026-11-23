@extends('emails.layouts.app')

@section('content')
<h2>New Inquiry{{ $tourTitle ? ': '.$tourTitle : '' }}</h2>
<p>A customer has submitted an inquiry through the website.</p>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">Customer Details</h3>
    <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value">{{ $name ?? 'Not provided' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $email ?? 'Not provided' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Phone</span>
        <span class="detail-value">{{ $phone ?: 'Not provided' }}</span>
    </div>
    @if($tourTitle)
    <div class="detail-row">
        <span class="detail-label">Tour</span>
        <span class="detail-value">{{ $tourTitle }}</span>
    </div>
    @endif
</div>

<div class="detail-box">
    <h3 style="color: #D4AF37; margin-bottom: 15px; font-size: 18px;">Message</h3>
    <p style="white-space: pre-line;">{{ $inquiryMessage ?: 'No message provided.' }}</p>
</div>

@if($adminUrl)
<a href="{{ $adminUrl }}" class="btn-primary">View Inquiry in Admin</a>
@endif

<p>Please respond to this customer as soon as possible.</p>
@endsection
