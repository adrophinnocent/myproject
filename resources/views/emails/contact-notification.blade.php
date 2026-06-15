@extends('emails.layouts.app')

@section('content')
<h2>New Contact Form Message</h2>
<p>You have received a new message from your website contact form:</p>

<div class="detail-box">
    <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value">{{ $data['name'] }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $data['email'] }}</span>
    </div>
    @if(isset($data['phone']))
    <div class="detail-row">
        <span class="detail-label">Phone</span>
        <span class="detail-value">{{ $data['phone'] }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Subject</span>
        <span class="detail-value">{{ $data['subject'] }}</span>
    </div>
</div>

<div style="background-color: #fafafa; border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; margin-top: 20px;">
    <h4 style="color: #0a0703; margin-bottom: 10px;">Message:</h4>
    <p style="color: #555; white-space: pre-wrap;">{{ $data['message'] }}</p>
</div>
@endsection
