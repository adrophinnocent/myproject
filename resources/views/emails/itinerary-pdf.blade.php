<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trip Itinerary - {{ (string)($booking->bookable_item->title ?? 'Safari') }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a1209;
            margin: 0;
            padding: 0;
            background-color: #fdfbf7;
            line-height: 1.4;
        }

        .container { padding: 40px; position: relative; min-height: 100vh; }

        .header { margin-bottom: 30px; }
        .logo-text { font-size: 24px; font-weight: 900; color: #1a1209; text-transform: uppercase; }
        .logo-sub { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 2px; }

        /* Title Section */
        .itinerary-title-block { margin-top: 40px; margin-bottom: 30px; }
        .main-title { font-size: 50px; font-weight: 900; color: #D4AF37; margin: 0; text-transform: uppercase; letter-spacing: -2px; line-height: 0.9; }
        .sub-title { font-size: 18px; font-weight: bold; color: #0a0703; margin-top: 10px; text-transform: uppercase; }

        /* Overview Grid */
        .overview-grid { width: 100%; border-collapse: collapse; margin-bottom: 40px; background: #ffffff; border-radius: 15px; border: 1px solid #e5e5e5; }
        .overview-grid td { padding: 15px; border-right: 1px solid #f0f0f0; width: 25%; }
        .overview-grid td:last-child { border-right: none; }
        .ov-label { font-size: 9px; font-weight: 900; color: #D4AF37; text-transform: uppercase; margin-bottom: 5px; }
        .ov-value { font-size: 12px; font-weight: bold; color: #1a1209; }

        /* Daily Timeline - Restored */
        .day-block { margin-bottom: 40px; position: relative; padding-left: 60px; page-break-inside: avoid; }
        .day-block:before { content: ""; position: absolute; left: 19px; top: 35px; bottom: -35px; width: 2px; background: #D4AF37; opacity: 0.3; }
        .day-block:last-child:before { display: none; }

        .day-badge {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            background: #0a0703;
            color: #D4AF37;
            border-radius: 10px;
            text-align: center;
            line-height: 40px;
            font-weight: 900;
            font-size: 14px;
        }

        .day-card { background: #ffffff; padding: 25px; border-radius: 20px; border: 1px solid #e5e5e5; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
        .day-title { font-size: 20px; font-weight: 900; color: #0a0703; margin: 0 0 10px 0; }
        .day-description { font-size: 12px; color: #444; line-height: 1.6; margin-bottom: 20px; }

        .logistics-grid { display: table; width: 100%; border-top: 1px solid #f0f0f0; padding-top: 15px; }
        .log-col { display: table-cell; width: 33.33%; font-size: 11px; }
        .log-label { font-weight: 900; color: #D4AF37; text-transform: uppercase; font-size: 8px; margin-bottom: 2px; }
        .log-value { font-weight: bold; color: #1a1209; }

        /* Footer - Redesigned like Invoice */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #0a0703;
            padding: 40px 0;
            text-align: center;
            box-sizing: border-box;
        }
        .footer-brand { font-size: 16px; font-weight: 900; color: #D4AF37; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
        .footer-contact { font-size: 10px; color: #94a3b8; font-weight: bold; }
        .bullet { color: #D4AF37; padding: 0 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php
                $logo = \App\Models\Setting::get('logo');
                // Use storage_path directly to avoid public_path/symlink issues during PDF generation
                $logoPath = $logo ? storage_path('app/public/' . $logo) : null;
            @endphp
            @if($logoPath && file_exists($logoPath))
                <img src="{{ 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) }}" style="height: 40px;">
            @else
                <div class="logo-text">TWINA SAFARIS</div>
                <div class="logo-sub">EXPLORE THE WILD</div>
            @endif
        </div>

        <div class="itinerary-title-block">
            <h1 class="main-title">TRIP<br>ITINERARY</h1>
            <div class="sub-title">{{ $booking->bookable_item->title }}</div>
        </div>

        <table class="overview-grid">
            <tr>
                <td>
                    <div class="ov-label">Traveler</div>
                    <div class="ov-value">{{ $booking->first_name }} {{ $booking->last_name }}</div>
                </td>
                <td>
                    <div class="ov-label">Travel Date</div>
                    <div class="ov-value">{{ \Carbon\Carbon::parse($booking->travel_date)->format('d M, Y') }}</div>
                </td>
                <td>
                    <div class="ov-label">Duration</div>
                    <div class="ov-value">{{ $booking->bookable_item->duration_text }}</div>
                </td>
                <td>
                    <div class="ov-label">Reference</div>
                    <div class="ov-value">#{{ $booking->booking_reference }}</div>
                </td>
            </tr>
        </table>

        @php
            $itinerary = $booking->bookable_item->itinerary ?? [];
            if (is_string($itinerary)) {
                $itinerary = json_decode($itinerary, true);
            }
        @endphp

        @if(!empty($itinerary) && is_array($itinerary))
            @foreach($itinerary as $index => $item)
                @php $item = (array)$item; @endphp
                <div class="day-block">
                    <div class="day-badge">{{ $loop->iteration }}</div>
                    <div class="day-card">
                        <h3 class="day-title">{{ $item['title'] ?? 'Day ' . ($index+1) }}</h3>
                        <div class="day-description">{{ $item['description'] ?? 'Prepare for an incredible day exploring the beauty of Tanzania.' }}</div>

                        <div class="logistics-grid">
                            <div class="log-col">
                                <div class="log-label">Stay</div>
                                <div class="log-value">{{ $item['accommodation'] ?? 'Safari Camp' }}</div>
                            </div>
                            <div class="log-col">
                                <div class="log-label">Meals</div>
                                <div class="log-value">{{ $item['meals'] ?? 'B, L, D' }}</div>
                            </div>
                            <div class="log-col">
                                <div class="log-label">Main Activity</div>
                                <div class="log-value">{{ is_array($item['activities'] ?? null) ? $item['activities'][0] : ($item['activities'] ?? 'Game Drive') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="footer">
            <div class="footer-brand">TWINA SAFARIS TANZANIA</div>
            <div class="footer-contact">
                {{ \App\Models\Setting::get('address', 'Moshi, Kilimanjaro') }}
                <span class="bullet">•</span>
                www.twinasafaris.com
                <span class="bullet">•</span>
                {{ \App\Models\Setting::get('site_phone', '+255 795 482 197') }}
            </div>
        </div>
    </div>
</body>
</html>
