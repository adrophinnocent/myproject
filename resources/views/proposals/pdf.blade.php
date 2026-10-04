<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Twina Safaris - Proposal {{ $proposal->full_reference }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #052010;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 10pt;
            line-height: 1.5;
        }
        .header {
            background-color: #052010;
            color: #ffffff;
            padding: 24px 32px;
            border-bottom: 4px solid #D4AF37;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo {
            font-size: 22pt;
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-family: Georgia, 'Times New Roman', serif;
        }
        .tagline {
            font-size: 8.5pt;
            color: #fef08a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }
        .body-container {
            padding: 24px 32px;
        }
        .title-box {
            background-color: #f4fdf7;
            border: 1px solid #d1fae5;
            border-left: 5px solid #D4AF37;
            padding: 16px 20px;
            margin-bottom: 18px;
            border-radius: 6px;
        }
        .proposal-title {
            font-size: 16pt;
            font-weight: bold;
            color: #052010;
            margin: 0 0 4px 0;
            font-family: Georgia, serif;
        }
        .client-name {
            font-size: 10.5pt;
            color: #D4AF37;
            font-weight: bold;
            margin: 0;
        }
        .cover-image-container {
            width: 100%;
            margin-bottom: 18px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #D4AF37;
            text-align: center;
        }
        .cover-image-container img {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            display: block;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .grid-cell {
            background-color: #f4fdf7;
            border: 1px solid #a7f3d0;
            padding: 8px 12px;
            width: 25%;
            vertical-align: top;
        }
        .grid-label {
            font-size: 7.5pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid-value {
            font-size: 9.5pt;
            font-weight: bold;
            color: #052010;
            margin-top: 2px;
        }
        .route-box {
            background-color: #052010;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 9.5pt;
            font-weight: bold;
            margin-bottom: 18px;
            border-left: 4px solid #D4AF37;
        }
        .section-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #052010;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 4px;
            margin-top: 22px;
            margin-bottom: 12px;
            font-family: Georgia, serif;
        }
        .day-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 14px 16px;
            margin-bottom: 16px;
            border-radius: 6px;
            page-break-inside: avoid;
        }
        .day-num {
            font-size: 8pt;
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .day-header {
            font-size: 11pt;
            font-weight: bold;
            color: #052010;
            margin-bottom: 6px;
            font-family: Georgia, serif;
        }
        .day-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 6px;
            margin-top: 8px;
            margin-bottom: 10px;
            border: 1px solid #a7f3d0;
            display: block;
        }
        .day-desc {
            font-size: 9.5pt;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .day-meta {
            font-size: 8.5pt;
            color: #065f46;
            background-color: #f4fdf7;
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #d1fae5;
        }
        .acc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .acc-table th, .acc-table td {
            border: 1px solid #d1fae5;
            padding: 8px 10px;
            font-size: 8.5pt;
            text-align: left;
        }
        .acc-table th {
            background-color: #052010;
            color: #D4AF37;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            letter-spacing: 0.5px;
        }
        .inc-exc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .inc-cell {
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .exc-cell {
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }
        ul.list-items {
            margin: 0;
            padding-left: 16px;
        }
        ul.list-items li {
            margin-bottom: 4px;
            font-size: 8.5pt;
            color: #1f2937;
        }
        .price-box {
            background-color: #052010;
            color: #ffffff;
            padding: 18px 20px;
            border-radius: 8px;
            margin-top: 22px;
            border: 1px solid #D4AF37;
        }
        .price-label {
            font-size: 8.5pt;
            text-transform: uppercase;
            color: #D4AF37;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .price-value {
            font-size: 22pt;
            font-weight: bold;
            color: #ffffff;
            margin-top: 2px;
            font-family: Georgia, serif;
        }
        .footer {
            margin-top: 28px;
            text-align: center;
            font-size: 8pt;
            color: #047857;
            border-top: 1px solid #d1fae5;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo">TWINA SAFARIS</div>
                    <div class="tagline">Your Personalized Tanzania Safari &bull; Ref: {{ $proposal->full_reference }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="font-size: 9.5pt; color: #D4AF37; font-weight: bold;">www.twinasafaris.com</div>
                    <div style="font-size: 8.5pt; color: #a7f3d0;">twinasafaris@gmail.com</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="body-container">
        {{-- PROPOSAL TITLE & CLIENT NAME --}}
        <div class="title-box">
            <div class="proposal-title">{{ $proposal->title }}</div>
            <div class="client-name">Prepared exclusively for: {{ $proposal->client_name }}</div>
            @if($proposal->subtitle)
                <div style="font-size: 8.5pt; color: #047857; margin-top: 2px;">{{ $proposal->subtitle }}</div>
            @endif
        </div>

        {{-- HERO DESTINATION COVER IMAGE --}}
        @php
            $heroCover = null;
            if (is_array($proposal->itinerary) && count($proposal->itinerary) > 0 && !empty($proposal->itinerary[0]['cover_image'])) {
                $heroCover = $proposal->itinerary[0]['cover_image'];
            }
        @endphp
        @if($heroCover)
        <div class="cover-image-container">
            <img src="{{ $heroCover }}" alt="Tanzania Safari">
        </div>
        @endif

        {{-- ROUTE --}}
        <div class="route-box">
            Safari Route: {{ $proposal->route_chain }}
        </div>

        {{-- SUMMARY GRID --}}
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
                    <div class="grid-label">Total Investment</div>
                    <div class="grid-value" style="color: #052010;">{{ $proposal->formatted_total_price }}</div>
                </td>
            </tr>
        </table>

        @if($proposal->welcome_message)
        <div style="background-color: #f4fdf7; border: 1px solid #a7f3d0; border-left: 4px solid #052010; padding: 10px 14px; border-radius: 6px; margin-bottom: 18px; font-style: italic; font-size: 9pt; color: #052010;">
            "{{ $proposal->welcome_message }}"
        </div>
        @endif

        {{-- DAY BY DAY ITINERARY WITH IMAGES --}}
        <div class="section-title">Day-by-Day Itinerary</div>

        @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
            @foreach($proposal->itinerary as $day)
            <div class="day-box">
                <div class="day-num">DAY {{ sprintf('%02d', $day['day'] ?? $loop->iteration) }}</div>
                <div class="day-header">{{ $day['title'] ?? '' }}</div>

                {{-- Day Cover Image in PDF --}}
                @if(!empty($day['cover_image']))
                    <img src="{{ $day['cover_image'] }}" class="day-image" alt="{{ $day['title'] ?? '' }}">
                @endif

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
                    @if(!empty($day['activities']))
                        <strong>Activities:</strong> {{ $day['activities'] }}
                    @endif
                </div>
            </div>
            @endforeach
        @endif

        {{-- ACCOMMODATIONS --}}
        @if(is_array($proposal->accommodations) && count($proposal->accommodations) > 0)
        <div class="section-title">Accommodations Summary</div>
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

        {{-- INCLUSIONS & EXCLUSIONS --}}
        <div class="section-title">Inclusions & Exclusions</div>
        <table class="inc-exc-table">
            <tr>
                <td class="inc-cell">
                    <div style="font-weight: bold; color: #047857; font-size: 9pt; margin-bottom: 6px; font-family: Georgia, serif;">Included Services</div>
                    <ul class="list-items">
                        @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                            @foreach($proposal->inclusions as $inc)
                                <li>{{ $inc }}</li>
                            @endforeach
                        @else
                            <li>All park fees, safari 4x4 Land Cruiser & professional guide</li>
                        @endif
                    </ul>
                </td>
                <td class="exc-cell">
                    <div style="font-weight: bold; color: #991b1b; font-size: 9pt; margin-bottom: 6px; font-family: Georgia, serif;">Excluded Services</div>
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

        {{-- PRICE BOX --}}
        <div class="price-box">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td>
                        <div class="price-label">YOUR SAFARI INVESTMENT</div>
                        <div class="price-value">{{ $proposal->formatted_total_price }}</div>
                        <div style="font-size: 8.5pt; color: #fef08a; margin-top: 4px;">
                            Deposit Required ({{ $proposal->deposit_percentage ?? 30 }}%): {{ $proposal->formatted_deposit_required }}
                        </div>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        <div style="font-size: 8.5pt; color: #D4AF37; font-weight: bold;">
                            Quotation Valid Until: {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
                        </div>
                        <div style="font-size: 8pt; color: #a7f3d0; margin-top: 2px;">Subject to availability</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- PAYMENT TERMS --}}
        @if($proposal->payment_terms || $proposal->cancellation_policy)
        <div class="section-title">Payment Terms & Cancellation Policy</div>
        <div style="font-size: 8.5pt; color: #374151;">
            @if($proposal->payment_terms)
                <p><strong>Payment Terms:</strong> {{ $proposal->payment_terms }}</p>
            @endif
            @if($proposal->cancellation_policy)
                <p><strong>Cancellation Policy:</strong> {{ $proposal->cancellation_policy }}</p>
            @endif
        </div>
        @endif

        <div class="footer">
            Twina Safaris &bull; View or accept your proposal online at: {{ $proposal->public_url }}
        </div>
    </div>

</body>
</html>
