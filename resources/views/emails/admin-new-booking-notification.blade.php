@extends('emails.layouts.luxury')

@section('content')
    <h1 style="color: #d9534f;">New Booking Received!</h1>
    <p>A new reservation has been made on the website. Please review the details and confirm availability.</p>

    <div style="background-color: #f8f8f8; padding: 20px; border-radius: 10px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Customer Information</h3>
        <p><strong>Name:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
        <p><strong>Email:</strong> {{ $booking->email }}</p>
        <p><strong>Phone:</strong> {{ $booking->phone }}</p>
        <p><strong>Nationality:</strong> {{ $booking->nationality }}</p>
    </div>

    <div style="background-color: #fcfaf7; padding: 20px; border-radius: 10px; margin: 20px 0; border: 1px solid #D4AF37;">
        <h3 style="margin-top: 0; color: #D4AF37;">Booking Details</h3>
        <p><strong>Tour:</strong> {{ $booking->tour->title }}</p>
        <p><strong>Date:</strong> {{ $booking->travel_date->format('M d, Y') }}</p>
        <p><strong>Guests:</strong> {{ $booking->number_of_adults + $booking->number_of_children }}</p>
        <p><strong>Total Value:</strong> ${{ number_format($booking->total_price, 2) }}</p>
    </div>

    @if($booking->special_requests)
        <div style="background-color: #fff4f4; padding: 15px; border-radius: 10px; border: 1px solid #ffcccc;">
            <p><strong>Special Requests:</strong><br>{{ $booking->special_requests }}</p>
        </div>
    @endif

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('admin.bookings.show', $booking) }}" class="button">Open in Admin Panel</a>
    </div>
@endsection
