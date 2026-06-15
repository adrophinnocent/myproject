@extends('emails.layout')

@section('content')
<h1 style="color: #1a1209; font-size: 24px; font-weight: bold; margin-bottom: 20px;">Booking Confirmation</h1>
<p style="font-size: 16px; color: #555555; line-height: 1.6;">Jambo {{ $booking->first_name }},</p>
<p style="font-size: 16px; color: #555555; line-height: 1.6;">Thank you for choosing Twina Safaris! We are delighted to confirm that your booking request has been received. Our team of safari experts is already preparing for your adventure.</p>

<div style="background-color: #fdfbf0; border: 1px solid #faf3cc; padding: 20px; border-radius: 8px; margin: 30px 0;">
    <h3 style="color: #D4AF37; margin-top: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Booking Reference: {{ $booking->booking_reference }}</h3>
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
    </table>
</div>

<p style="font-size: 16px; color: #555555; line-height: 1.6;">What happens next?</p>
<ol style="color: #555555; font-size: 15px;">
    <li>One of our consultants will review your itinerary details.</li>
    <li>We will reach out via WhatsApp/Email to discuss any custom requests.</li>
    <li>You will receive final confirmation and payment instructions for your deposit.</li>
</ol>

<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box; margin-top: 30px;">
    <tbody>
        <tr>
            <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                    <tbody>
                        <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center; background-color: #D4AF37;">
                                <a href="{{ url('/') }}" target="_blank" style="border: solid 1px #D4AF37; border-radius: 30px; box-sizing: border-box; color: #ffffff; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize; background-color: #D4AF37;">View Website</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<p style="font-size: 14px; color: #999999; line-height: 1.6; margin-top: 30px;">If you have any urgent questions, please contact us on WhatsApp at {{ \App\Models\Setting::get('site_phone') }}.</p>
@endsection
