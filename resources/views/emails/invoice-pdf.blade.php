<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $booking->booking_reference }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1a1209; margin: 0; padding: 40px; line-height: 1.5; background: #fff; }
        .header { border-bottom: 3px solid #D4AF37; padding-bottom: 20px; margin-bottom: 30px; }
        .header-table { width: 100%; }
        .logo { font-size: 24px; font-weight: bold; color: #0a0703; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-title { font-size: 32px; font-weight: 900; color: #D4AF37; text-align: right; text-transform: uppercase; }

        .info-table { width: 100%; margin-bottom: 40px; }
        .info-td { width: 50%; vertical-align: top; }
        .label { color: #888; font-size: 10px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .value { font-weight: bold; font-size: 14px; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { background: #0a0703; color: #fff; text-align: left; padding: 12px 15px; font-size: 11px; text-transform: uppercase; }
        .items-table td { padding: 15px; border-bottom: 1px solid #eee; font-size: 13px; }

        .totals-table { width: 40%; margin-left: 60%; }
        .totals-row td { padding: 10px 0; }
        .total-label { text-align: right; color: #888; padding-right: 20px; font-size: 12px; }
        .total-value { text-align: right; font-weight: bold; font-size: 14px; }
        .grand-total { background: #fdfbf0; border-top: 2px solid #D4AF37; }
        .grand-total .total-label { color: #0a0703; font-weight: 900; }
        .grand-total .total-value { color: #D4AF37; font-size: 20px; }

        .payment-info { margin-top: 60px; padding: 20px; background: #f9f7f4; border-radius: 15px; border: 1px solid #e1ede8; }
        .payment-title { font-weight: bold; font-size: 12px; margin-bottom: 10px; color: #8f6e0a; }
        .payment-text { font-size: 11px; color: #666; }

        .footer { margin-top: 60px; text-align: center; color: #ccc; font-size: 10px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo">Twina Safaris</td>
                <td class="invoice-title">Invoice</td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-td">
                <div class="label">Billed To:</div>
                <div class="value">{{ $booking->first_name }} {{ $booking->last_name }}</div>
                <div class="payment-text">{{ $booking->email }}</div>
                <div class="payment-text">{{ $booking->phone }}</div>
                <div class="payment-text">{{ $booking->nationality }}</div>
            </td>
            <td class="info-td" style="text-align: right;">
                <div class="label">Invoice Details:</div>
                <div class="value">REF: {{ $booking->booking_reference }}</div>
                <div class="payment-text">Date: {{ date('F j, Y') }}</div>
                <div class="payment-text">Travel Date: {{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</div>
                <div class="payment-text">Status: <span style="color: {{ $booking->status == 'confirmed' ? '#22c55e' : '#f59e0b' }}">{{ strtoupper($booking->status) }}</span></div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center; width: 100px;">Guests</th>
                <th style="text-align: right; width: 120px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold;">{{ $booking->tour->title }}</div>
                    <div style="font-size: 11px; color: #888; margin-top: 5px;">
                        Duration: {{ $booking->tour->duration_text }}<br>
                        Location: {{ $booking->tour->destination->name ?? 'Tanzania' }}
                    </div>
                </td>
                <td style="text-align: center;">
                    {{ $booking->number_of_adults }} Adults<br>
                    @if($booking->number_of_children > 0)
                        {{ $booking->number_of_children }} Children
                    @endif
                </td>
                <td style="text-align: right; font-weight: bold;">{{ $booking->formatted_price }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr class="totals-row">
            <td class="total-label">Subtotal</td>
            <td class="total-value">{{ $booking->formatted_price }}</td>
        </tr>
        <tr class="totals-row">
            <td class="total-label">Tax (VAT 0%)</td>
            <td class="total-value">$0.00</td>
        </tr>
        <tr class="totals-row grand-total">
            <td class="total-label">Total Amount</td>
            <td class="total-value">{{ $booking->formatted_price }}</td>
        </tr>
    </table>

    <div class="payment-info">
        <div class="payment-title">Payment Instructions</div>
        <div class="payment-text">
            Please complete your payment to secure your booking. You can pay via:<br>
            - <strong>Bank Transfer:</strong> Bank Name: [YOUR BANK], Account Name: Twina Safaris, SWIFT: [CODE]<br>
            - <strong>PayPal:</strong> info@twinasafaris.com<br>
            - <strong>WhatsApp:</strong> Contact us at +255 795 482 197 for more options.
        </div>
    </div>

    <div class="footer">
        © {{ date('Y') }} Twina Safaris Tanzania. Moshi, Tanzania. | +255 795 482 197 | info@twinasafaris.com
    </div>
</body>
</html>
