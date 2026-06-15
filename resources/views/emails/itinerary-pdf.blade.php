<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Itinerary - {{ (string)($booking->tour->title ?? 'Safari') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #D4AF37;
            margin: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            color: #0a0703;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .detail-row {
            margin-bottom: 5px;
        }
        .detail-label {
            color: #666;
            font-weight: 500;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #0a0703;
            font-weight: bold;
        }
        .itinerary-item {
            margin: 15px 0;
            padding-left: 15px;
            border-left: 3px solid #D4AF37;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Twina Safaris</h1>
        <p>Your Adventure Awaits!</p>
    </div>

    <div class="section">
        <h3>Booking Information</h3>
        <div class="detail-row">
            <span class="detail-label">Reference:</span>
            <span class="detail-value">{{ (string)$booking->booking_reference }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Guest Name:</span>
            <span class="detail-value">{{ (string)$booking->first_name }} {{ (string)$booking->last_name }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Travel Date:</span>
            <span class="detail-value">{{ $booking->travel_date ? \Carbon\Carbon::parse($booking->travel_date)->format('F j, Y') : 'TBD' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tour:</span>
            <span class="detail-value">{{ (string)($booking->tour->title ?? 'Safari') }}</span>
        </div>
    </div>

    @php
        $itinerary = $booking->tour->itinerary ?? [];
        if (is_string($itinerary)) {
            $itinerary = json_decode($itinerary, true);
        }
    @endphp

    @if(!empty($itinerary) && (is_array($itinerary) || is_object($itinerary)))
    <div class="section">
        <h3>Trip Itinerary</h3>
        @foreach($itinerary as $day => $item)
            @php $item = (array)$item; @endphp
            <div class="itinerary-item">
                <h4>Day {{ $day }}: {{ is_array($item['title'] ?? null) ? 'Day Details' : ($item['title'] ?? '') }}</h4>
                <p>{{ is_array($item['description'] ?? null) ? '' : ($item['description'] ?? '') }}</p>
                @if(!empty($item['accommodation']) && !is_array($item['accommodation']))
                    <p><strong>Accommodation:</strong> {{ $item['accommodation'] }}</p>
                @endif
                @if(!empty($item['meals']) && !is_array($item['meals']))
                    <p><strong>Meals:</strong> {{ $item['meals'] }}</p>
                @endif
                @if(!empty($item['activities']) && !is_array($item['activities']))
                    <p><strong>Activities:</strong> {{ $item['activities'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    <div class="section">
        <h3>Contact Information</h3>
        <p>Email: {{ (string)\App\Models\Setting::get('site_email', 'info@twinasafaris.com') }}</p>
        <p>Phone: {{ (string)\App\Models\Setting::get('site_phone', '+255 754 000 000') }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Twina Safaris. All rights reserved.</p>
    </div>
</body>
</html>
