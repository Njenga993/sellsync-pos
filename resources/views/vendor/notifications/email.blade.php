<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SellSync POS' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Outfit', sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 480px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .email-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e4e7ef;
            box-shadow: 0 1px 3px rgba(2, 24, 47, 0.04);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(160deg, #03A737 0%, #028a2e 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .email-header img {
            width: 56px;
            height: 56px;
            margin-bottom: 12px;
        }
        .email-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.01em;
        }
        .email-header p {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            margin: 4px 0 0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .email-body {
            padding: 32px 24px;
        }
        .email-body h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #02182F;
            margin: 0 0 8px;
        }
        .email-body p {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin: 0 0 24px;
        }
        .email-button {
            display: inline-block;
            padding: 12px 28px;
            background: #03A737;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.01em;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s ease;
        }
        .email-button:hover {
            background: #028a2e;
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
        }
        .email-url {
            display: block;
            margin-top: 16px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #6b7280;
            word-break: break-all;
            border: 1px solid #e4e7ef;
        }
        .email-footer {
            padding: 20px 24px;
            border-top: 1px solid #e4e7ef;
            text-align: center;
        }
        .email-footer p {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
            line-height: 1.5;
        }
        .email-footer a {
            color: #03A737;
            text-decoration: none;
            font-weight: 500;
        }
        .subcopy {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            {{-- Header --}}
            <div class="email-header">
                <img src="{{ $headerLogo ?? 'https://sellsync-pos-production.up.railway.app/images/sellsyncLogo.png' }}" 
                     alt="SellSync POS" 
                     style="filter: brightness(0) invert(1);"
                     onerror="this.style.display='none'">
                <h1>SellSync</h1>
                <p>POS</p>
            </div>

            {{-- Body --}}
            <div class="email-body">
                <h2>{{ $greeting ?? 'Hello!' }}</h2>
                
                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                {{-- Action Button --}}
                @isset($actionText)
                    <a href="{{ $actionUrl }}" class="email-button" target="_blank">
                        {{ $actionText }}
                    </a>
                @endisset

                {{-- Action URL (fallback) --}}
                @isset($actionUrl)
                    <div class="email-url">{{ $actionUrl }}</div>
                @endisset

                {{-- Outro Lines --}}
                @foreach ($outroLines as $line)
                    <p style="margin-top:16px">{{ $line }}</p>
                @endforeach

                {{-- Salutation --}}
                @if (!empty($salutation))
                    <p style="margin-top:24px;color:#374151;font-weight:500">{{ $salutation }}</p>
                @endif
            </div>

            {{-- Footer --}}
            <div class="email-footer">
                <p>
                    &copy; {{ date('Y') }} <a href="https://sellsync.co.ke">SellSync POS</a>. 
                    All rights reserved.
                </p>
                <p class="subcopy">
                    If you're having trouble clicking the button, copy and paste the URL above into your web browser.
                </p>
            </div>
        </div>
    </div>
</body>
</html>