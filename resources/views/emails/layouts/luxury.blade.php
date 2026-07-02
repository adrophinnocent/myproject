<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f9f7f4;
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1a1209;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
        }
        .header {
            padding: 40px 20px;
            text-align: center;
            background-color: #0a0703;
        }
        .content {
            padding: 40px;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 20px;
            color: #0a0703;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #D4AF37, #b8920d);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #f1ede8;
            font-size: 12px;
            color: #8b6e50;
        }
        .social-links a {
            margin: 0 10px;
            color: #D4AF37;
            text-decoration: none;
            font-weight: bold;
        }
        .highlight {
            color: #D4AF37;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php $logo = \App\Models\Setting::get('logo'); @endphp
            @if($logo)
                <img src="{{ $message->embed(storage_path('app/public/' . $logo)) }}" alt="Twina Safaris" style="max-width: 150px;">
            @else
                <h2 style="color: #D4AF37; margin: 0;">TWINA SAFARIS</h2>
            @endif
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Twina Safaris Tanzania. All rights reserved.</p>
            <p>Moshi, Kilimanjaro | +255 754 000 000 | info@twinasafaris.com</p>
            <div class="social-links">
                <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}">Facebook</a>
                <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}">Instagram</a>
                <a href="{{ \App\Models\Setting::get('youtube_url', '#') }}">YouTube</a>
            </div>
        </div>
    </div>
</body>
</html>
