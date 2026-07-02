<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->booking_reference }}</title>
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
        .logo-sub { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 2px; margin-top: -5px; }

        /* Invoice Section */
        .invoice-title-block { margin-top: 40px; margin-bottom: 30px; }
        .main-title { font-size: 60px; font-weight: 900; color: #D4AF37; margin: 0; text-transform: uppercase; letter-spacing: -2px; }
        .meta-info { font-size: 12px; font-weight: bold; color: #0a0703; margin-top: 10px; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table thead th { background-color: #0a0703; color: #D4AF37; text-align: left; padding: 12px 15px; font-size: 11px; text-transform: uppercase; font-weight: 900; }
        .items-table tbody td { padding: 15px; border-bottom: 1px solid #e5e5e5; font-size: 12px; }

        .item-name { font-weight: bold; color: #1a1209; }

        /* Summary Grid */
        .summary-wrapper { width: 100%; display: table; margin-top: 30px; }
        .summary-left { display: table-cell; width: 55%; vertical-align: top; }
        .summary-right { display: table-cell; width: 45%; vertical-align: top; }

        .invoice-to-title { font-size: 22px; font-weight: 900; color: #0a0703; margin-bottom: 15px; border-bottom: 2px solid #D4AF37; display: inline-block; }
        .client-info { font-size: 12px; line-height: 1.6; color: #444; }

        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 8px 15px; font-size: 13px; }
        .total-label { color: #666; font-weight: bold; text-align: left; }
        .total-value { text-align: right; font-weight: bold; color: #1a1209; }
        .grand-total-row td { padding-top: 15px; }
        .grand-total-label { font-size: 18px; font-weight: 900; color: #0a0703; text-transform: uppercase; }
        .grand-total-value { font-size: 22px; font-weight: 900; color: #D4AF37; text-align: right; }

        /* New Premium Black Footer */
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
                $logoPath = $logo ? public_path('storage/' . $logo) : null;
            @endphp
            @if($logoPath && file_exists($logoPath))
                <img src="{{ 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) }}" style="height: 40px;">
            @else
                <div class="logo-text">TWINA SAFARIS</div>
                <div class="logo-sub">EXPLORE THE WILD</div>
            @endif
        </div>

        <div class="invoice-title-block">
            <h1 class="main-title">INVOICE</h1>
            <div class="meta-info">
                Invoice No: TS-{{ $booking->booking_reference }}<br>
                Invoice Date: {{ date('d M Y') }}
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 20%;">Price</th>
                    <th style="width: 10%; text-align: center;">Qty.</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="item-name">{{ $booking->bookable_item->title }}</span><br>
                        <small style="color: #666;">Standard Safari Package</small>
                    </td>
                    <td>{{ $booking->formatted_price }}</td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right; font-weight: bold;">{{ $booking->formatted_price }}</td>
                </tr>
                <tr>
                    <td>
                        <span class="item-name">Park Fees & Logistics</span><br>
                        <small style="color: #666;">Conservation and entries</small>
                    </td>
                    <td>Included</td>
                    <td style="text-align: center;">{{ $booking->number_of_adults + $booking->number_of_children }}</td>
                    <td style="text-align: right; font-weight: bold;">$0.00</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-wrapper">
            <div class="summary-left">
                <div class="invoice-to-title">Invoice to:</div>
                <div class="client-info">
                    NAME: {{ $booking->first_name }} {{ $booking->last_name }}<br>
                    EMAIL: {{ $booking->email }}<br>
                    PHONE: {{ $booking->phone }}<br>
                    COUNTRY: {{ $booking->nationality }}
                </div>
            </div>
            <div class="summary-right">
                <table class="totals-table">
                    <tr>
                        <td class="total-label">Subtotal</td>
                        <td class="total-value">{{ $booking->formatted_price }}</td>
                    </tr>
                    <tr>
                        <td class="total-label">Tax Rate (0%)</td>
                        <td class="total-value">$0.00</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td class="grand-total-label">TOTAL</td>
                        <td class="grand-total-value">{{ $booking->formatted_price }}</td>
                    </tr>
                </table>
            </div>
        </div>

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
