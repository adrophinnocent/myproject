<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Twina Safaris')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #0a0703 0%, #1a1209 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            color: #D4AF37;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .email-header p {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }
        .email-content {
            padding: 30px 25px;
        }
        .email-content h2 {
            color: #0a0703;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .email-content p {
            color: #555;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .btn-primary {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #D4AF37 0%, #b8920d 100%);
            color: #0a0703;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-box {
            background-color: #fafafa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #666;
            font-size: 14px;
        }
        .detail-value {
            color: #0a0703;
            font-weight: 600;
            font-size: 14px;
        }
        .email-footer {
            background-color: #0a0703;
            padding: 25px 20px;
            text-align: center;
            color: rgba(255,255,255,0.7);
            font-size: 13px;
        }
        .email-footer a {
            color: #D4AF37;
            text-decoration: none;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 5px;
            line-height: 36px;
            text-align: center;
            color: #D4AF37;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>🦁 Twina Safaris</h1>
            <p>Explore Tanzania Beyond Expectations</p>
        </div>
        <div class="email-content">
            @yield('content')
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Twina Safaris. All rights reserved.</p>
            <p>
                <a href="{{ route('home') }}">Visit Website</a> | 
                <a href="{{ route('contact.index') }}">Contact Us</a>
            </p>
            <p>{{ \App\Models\Setting::get('site_phone', '+255 754 000 000') }} | {{ \App\Models\Setting::get('site_email', 'info@twinasafaris.com') }}</p>
        </div>
    </div>
</body>
</html>
