<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your SellSync Login Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
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
        .email-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
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
            text-align: center;
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
        .otp-code {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 36px;
            font-weight: 700;
            color: #03A737;
            letter-spacing: 8px;
            padding: 16px 32px;
            background: #e6f7eb;
            border-radius: 12px;
            border: 2px dashed #03A737;
            margin-bottom: 24px;
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
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            <div class="email-header">
                <h1>SellSync</h1>
                <p>POS</p>
            </div>
            <div class="email-body">
                <h2>Hello {{ $user->name }}!</h2>
                <p>Here's your one-time login code. It expires in <strong>5 minutes</strong>.</p>
                <div class="otp-code">{{ $otp }}</div>
                <p style="font-size:12px;color:#9ca3af">
                    If you didn't request this, please ignore this email. Your account is safe.
                </p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} SellSync POS. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>