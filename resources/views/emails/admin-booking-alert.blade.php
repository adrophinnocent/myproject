@extends('emails.layout')

@section('content')
<h1 style="color: #1a1209; font-size: 24px; font-weight: bold; margin-bottom: 20px;">New Booking Alert</h1>
<p style="font-size: 16px; color: #555555; line-height: 1.6;">A new safari booking has just been submitted via the website.</p>

<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin: 30px 0;">
    <h3 style="color: #334155; margin-top: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Customer Details</h3>
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Name:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->first_name }} {{ $booking->last_name }}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Email:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->email }}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Phone:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->phone }}</td>
        </tr>
    </table>
</div>

<div style="background-color: #fdfbf0; border: 1px solid #faf3cc; padding: 20px; border-radius: 8px; margin: 30px 0;">
    <h3 style="color: #D4AF37; margin-top: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Trip Details</h3>
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Tour:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->tour->title }}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Travel Date:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->travel_date->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Guests:</td>
            <td style="padding: 5px 0; color: #333333; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->number_of_adults }} Adults, {{ $booking->number_of_children }} Children</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; color: #888888; font-size: 13px;">Reference:</td>
            <td style="padding: 5px 0; color: #D4AF37; font-weight: bold; font-size: 14px; text-align: right;">{{ $booking->booking_reference }}</td>
        </tr>
    </table>
</div>

@if($booking->special_requests)
<div style="padding: 20px; border: 1px dashed #e2e8f0; border-radius: 8px; margin-bottom: 30px;">
    <p style="font-size: 12px; color: #888888; text-transform: uppercase; margin-top: 0;">Special Requests</p>
    <p style="font-size: 14px; color: #555555; line-height: 1.6; margin-bottom: 0;">{{ $booking->special_requests }}</p>
</div>
@endif

<a href="{{ route('admin.bookings.show', $booking) }}" style="background-color: #1a1209; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 30px; display: block; text-align: center; font-weight: bold;">View in Dashboard</a>
@endsection
