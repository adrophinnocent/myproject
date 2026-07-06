@extends('emails.layouts.luxury')

@section('content')
    <h1>Jambo {{ $booking->first_name }},</h1>
    <p>Thank you for choosing <span class="highlight">Twina Safaris</span>. We are delighted to confirm that we have received your booking for an unforgettable African experience.</p>

    <div style="background-color: #fcfaf7; padding: 25px; border-radius: 15px; margin: 30px 0; border: 1px solid #eee;">
        <h3 style="margin-top: 0; color: #D4AF37;">Booking Summary</h3>
        <table width="100%" cellpadding="5">
            <tr>
                <td width="40%" style="font-weight: 600; color: #888;">Reference:</td>
                <td style="font-weight: 600;">{{ $booking->booking_reference }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600; color: #888;">Safari Package:</td>
                <td>{{ $booking->tour->title ?? $booking->safari->title ?? 'Safari Expedition' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600; color: #888;">Travel Date:</td>
                <td>{{ $booking->travel_date->format('d M, Y') }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600; color: #888;">Guests:</td>
                <td>{{ $booking->number_of_adults }} Adults, {{ $booking->number_of_children }} Children</td>
            </tr>
            <tr>
                <td style="font-weight: 600; color: #888;">Total Price:</td>
                <td style="color: #D4AF37; font-weight: bold; font-size: 18px;">${{ number_format($booking->total_price, 2) }}</td>
            </tr>
        </table>
    </div>

    <p>We have attached your official <span class="highlight">Safari Itinerary</span> to this email. Please review the details carefully.</p>

    <p>Our team is already preparing for your arrival. If you have any questions or need to customize your trip further, feel free to reply to this email or contact us on WhatsApp.</p>

    <div style="text-align: center;">
        <a href="{{ url('/') }}" class="button">View My Booking</a>
    </div>

    <p style="margin-top: 40px; font-size: 13px; color: #666;">Warm regards,<br>The Twina Safaris Team</p>
@endsection
