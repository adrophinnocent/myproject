<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #1a1209; color: #fff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; width: 40%; }
        .btn { display: inline-block; padding: 10px 20px; background: #D4AF37; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Trip Plan Request</h1>
        </div>
        <div class="content">
            <p>You have received a new trip planning request from <strong>{{ $plan->name }}</strong>.</p>

            <table>
                <tr><th>Name</th><td>{{ $plan->name }}</td></tr>
                <tr><th>Email</th><td>{{ $plan->email }}</td></tr>
                <tr><th>Phone</th><td>{{ $plan->phone ?? 'N/A' }}</td></tr>
                <tr><th>Nationality</th><td>{{ $plan->nationality ?? 'N/A' }}</td></tr>
                <tr><th>Travel Style</th><td>{{ $plan->travel_style ?? 'N/A' }}</td></tr>
                <tr><th>Budget Range</th><td>{{ $plan->budget_range ?? 'N/A' }}</td></tr>
                <tr><th>Duration</th><td>{{ $plan->duration ?? 'N/A' }}</td></tr>
                <tr><th>Travel Date</th><td>{{ $plan->travel_date ? $plan->travel_date->format('d M Y') : 'Flexible' }}</td></tr>
                <tr><th>Group Size</th><td>{{ $plan->adults }} Adults, {{ $plan->children ?? 0 }} Children</td></tr>
            </table>

            @if($plan->message)
            <div style="margin-top: 20px;">
                <strong>Message / Requirements:</strong>
                <p style="background: #fdfbf0; padding: 15px; border-radius: 10px; border-left: 4px solid #D4AF37;">
                    {{ $plan->message }}
                </p>
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('admin.trip-plans.show', $plan) }}" class="btn">View on Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated notification from Twinasafaris.com</p>
        </div>
    </div>
</body>
</html>
