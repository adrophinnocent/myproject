<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Safari Proposal - {{ $proposal->title }} - {{ $proposal->full_reference }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 10.5pt;
            line-height: 1.5;
        }
        .header {
            background-color: #022c22;
            color: #ffffff;
            padding: 25px 35px;
            border-bottom: 4px solid #d4af37;
        }
        .header table {
            width: 100%;
        }
        .logo {
            font-size: 20pt;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 9pt;
            color: #a7f3d0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .body-container {
            padding: 25px 35px;
        }
        .title-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #d4af37;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .proposal-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .client-name {
            font-size: 11pt;
            color: #b45309;
            font-weight: bold;
            margin: 0;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grid-cell {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            width: 25%;
            vertical-align: top;
        }
        .grid-label {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .grid-value {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .route-box {
            background-color: #064e3b;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 9.5pt;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #022c22;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        .day-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 6px;
            page-break-inside: avoid;
        }
        .day-header {
            font-size: 10.5pt;
            font-weight: bold;
            color: #b45309;
            margin-bottom: 6px;
        }
        .day-desc {
            font-size: 9.5pt;
            color: #334155;
            margin-bottom: 8px;
        }
        .day-meta {
            font-size: 8.5pt;
            color: #475569;
            background-color: #f8fafc;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .acc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .acc-table th, .acc-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 9pt;
            text-align: left;
        }
        .acc-table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .inc-exc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .inc-cell {
            width: 50%;
            vertical-align: top;
            padding-right: 12px;
        }
        .exc-cell {
            width: 50%;
            vertical-align: top;
            padding-left: 12px;
        }
        ul.list-items {
            margin: 0;
            padding-left: 16px;
        }
        ul.list-items li {
            margin-bottom: 4px;
            font-size: 9pt;
            color: #334155;
        }
        .price-box {
            background-color: #022c22;
            color: #ffffff;
            padding: 18px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .price-label {
            font-size: 8.5pt;
            text-transform: uppercase;
            color: #d4af37;
            font-weight: bold;
        }
        .price-value {
            font-size: 20pt;
            font-weight: bold;
            color: #ffffff;
            margin-top: 2px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8.5pt;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo">TWINA SAFARIS</div>
                    <div class="subtitle">Customized Client Safari Proposal &bull; Ref: {{ $proposal->full_reference }}</div>
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 9.5pt; color: #a7f3d0; font-weight: bold;">www.twinasafaris.com</div>
                    <div style="font-size: 8.5pt; color: #cbd5e1;">info@twinasafaris.com</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="body-container">
        {{-- Proposal Overview Title --}}
        <div class="title-box">
            <div class="proposal-title">{{ $proposal->title }}</div>
            <div class="client-name">Prepared exclusively for: {{ $proposal->client_name }}</div>
            @if($proposal->subtitle)<div style="font-size: 9pt; color: #64748b; margin-top: 2px;">{{ $proposal->subtitle }}</div>@endif
        </div>

        {{-- Route Chain --}}
        <div class="route-box">
            Safari Route: {{ $proposal->route_chain }}
        </div>

        {{-- Grid Info --}}
        <table class="grid-table">
            <tr>
                <td class="grid-cell">
                    <div class="grid-label">Duration</div>
                    <div class="grid-value">{{ $proposal->duration_days }} Days / {{ $proposal->duration_nights }} Nights</div>
                </td>
                <td class="grid-cell">
                    <div class="grid-label">Travelers</div>
                    <div class="grid-value">{{ $proposal->adults }} Adults @if($proposal->children > 0), {{ $proposal->children }} Children @endif</div>
                </td>
                <td class="grid-cell">
                    <div class="grid-label">Travel Dates</div>
                    <div class="grid-value">
                        @if($proposal->start_date)
                            {{ $proposal->start_date->format('d M Y') }}
                        @else
                            Flexible Dates
                        @endif
                    </div>
                </td>
                <td class="grid-cell">
                    <div class="grid-label">Total Selling Price</div>
                    <div class="grid-value" style="color: #d4af37;">{{ $proposal->formatted_total_price }}</div>
                </td>
            </tr>
        </table>

        @if($proposal->welcome_message)
        <div style="background-color: #fffbeb; border: 1px solid #fde68a; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px; font-style: italic; font-size: 9.5pt; color: #92400e;">
            "{{ $proposal->welcome_message }}"
        </div>
        @endif

        {{-- Day-by-Day Itinerary --}}
        <div class="section-title">Day-by-Day Itinerary</div>

        @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
            @foreach($proposal->itinerary as $day)
            <div class="day-box">
                <div class="day-header">Day {{ $day['day'] ?? $loop->iteration }}: {{ $day['title'] ?? '' }}</div>
                @if(!empty($day['description']))
                    <div class="day-desc">{{ $day['description'] }}</div>
                @endif
                <div class="day-meta">
                    @if(!empty($day['accommodation_property']))
                        <strong>Lodge:</strong> {{ $day['accommodation_property'] }} @if(!empty($day['room_type'])) ({{ $day['room_type'] }}) @endif &nbsp;&bull;&nbsp;
                    @endif
                    @if(!empty($day['meals']))
                        <strong>Meals:</strong> {{ $day['meals'] }} &nbsp;&bull;&nbsp;
                    @endif
                    @if(!empty($day['driving_time']))
                        <strong>Drive:</strong> {{ $day['driving_time'] }}
                    @endif
                </div>
            </div>
            @endforeach
        @endif

        {{-- Accommodation Summary --}}
        @if(is_array($proposal->accommodations) && count($proposal->accommodations) > 0)
        <div class="section-title">Accommodation Summary</div>
        <table class="acc-table">
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Room Type</th>
                    <th>Meal Plan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->accommodations as $acc)
                <tr>
                    <td><strong>{{ $acc['property_name'] ?? 'Safari Lodge' }}</strong></td>
                    <td>{{ $acc['location'] ?? 'National Park' }}</td>
                    <td>{{ $acc['category'] ?? 'Comfort' }}</td>
                    <td>{{ $acc['room_type'] ?? 'Standard Room' }}</td>
                    <td>{{ $acc['meal_plan'] ?? 'Full Board' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Inclusions & Exclusions --}}
        <div class="section-title">Inclusions & Exclusions</div>
        <table class="inc-exc-table">
            <tr>
                <td class="inc-cell">
                    <div style="font-weight: bold; color: #166534; font-size: 9.5pt; margin-bottom: 6px;">Included Services</div>
                    <ul class="list-items">
                        @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                            @foreach($proposal->inclusions as $inc)
                                <li>{{ $inc }}</li>
                            @endforeach
                        @else
                            <li>All park fees, Land Cruiser safari vehicle & professional guide</li>
                        @endif
                    </ul>
                </td>
                <td class="exc-cell">
                    <div style="font-weight: bold; color: #991b1b; font-size: 9.5pt; margin-bottom: 6px;">Excluded Services</div>
                    <ul class="list-items">
                        @if(is_array($proposal->exclusions) && count($proposal->exclusions) > 0)
                            @foreach($proposal->exclusions as $exc)
                                <li>{{ $exc }}</li>
                            @endforeach
                        @else
                            <li>International flights, visas, tips, and personal expenses</li>
                        @endif
                    </ul>
                </td>
            </tr>
        </table>

        {{-- Price Box & Payment Terms --}}
        <div class="price-box">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td>
                        <div class="price-label">TOTAL SAFARI INVESTMENT</div>
                        <div class="price-value">{{ $proposal->formatted_total_price }}</div>
                        <div style="font-size: 8.5pt; color: #a7f3d0; margin-top: 4px;">
                            Deposit Required ({{ $proposal->deposit_percentage ?? 30 }}%): {{ $proposal->formatted_deposit_required }}
                        </div>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        <div style="font-size: 8.5pt; color: #a7f3d0; font-weight: bold;">
                            Valid Until: {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
                        </div>
                        <div style="font-size: 8pt; color: #cbd5e1; margin-top: 2px;">Subject to lodge availability</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Terms --}}
        @if($proposal->payment_terms || $proposal->cancellation_policy)
        <div class="section-title">Payment Terms & Conditions</div>
        <div style="font-size: 9pt; color: #475569;">
            @if($proposal->payment_terms)
                <p><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</p>
            @endif
            @if($proposal->cancellation_policy)
                <p><strong>Cancellation Policy:</strong> {{ $proposal->cancellation_policy }}</p>
            @endif
        </div>
        @endif

        <div class="footer">
            Thank you for choosing Twina Safaris! Review or accept your proposal online at: {{ $proposal->public_url }}
        </div>
    </div>

</body>
</html>
