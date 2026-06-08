<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        :root {
            --brand-green: #03A737;
            --brand-green-light: #e6f7eb;
            --brand-green-dark: #028a2e;
            --brand-blue: #3D7BE7;
            --brand-blue-light: #edf3fd;
            --brand-black: #02182F;
            --brand-white: #FFFEFE;
        }

        .bg-gray-100, .min-h-screen, body, .dark\:bg-black, [class*="bg-gray"] {
            background: linear-gradient(160deg, #FFFEFE 0%, #e6f7eb 50%, #edf3fd 100%) !important;
            background-image: none !important;
        }
        
        .brand-header { text-align: center; margin-bottom: 28px; }
        
        .brand-logo {
            width: 80px; height: 80px;
            margin: 0 auto 16px;
            object-fit: contain; display: block;
        }
        @media(min-width: 640px) {
            .brand-logo { width: 96px; height: 96px; }
        }
        
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 22px; font-weight: 700;
            color: var(--brand-black); line-height: 1;
        }
        
        .brand-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px; color: var(--brand-green);
            font-weight: 600; letter-spacing: 0.08em; margin-top: 4px;
        }
        
        .form-card {
            background: var(--brand-white);
            border: 1px solid #e4e7ef; border-radius: 14px;
            padding: 32px; box-shadow: 0 1px 3px rgba(2,24,47,0.04);
            text-align: center;
        }
        
        .verify-icon {
            width: 56px; height: 56px;
            background: var(--brand-green-light);
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .verify-icon svg {
            width: 28px; height: 28px;
            color: var(--brand-green);
        }
        
        .btn-primary {
            display: inline-block; padding: 11px 24px;
            background: var(--brand-green); color: var(--brand-white);
            border: none; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 600; letter-spacing: 0.01em; cursor: pointer;
            box-shadow: 0 2px 8px rgba(3,167,55,0.25);
            transition: all 0.15s ease; text-decoration: none;
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3,167,55,0.35);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            display: inline-block; padding: 11px 24px;
            background: var(--brand-white); color: #374151;
            border: 1.5px solid #e4e7ef; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 500; cursor: pointer;
            transition: all 0.15s ease; text-decoration: none;
        }
        .btn-secondary:hover { border-color: var(--brand-green); background: var(--brand-green-light); }
        
        .session-status {
            background: #e6f7eb; border: 1px solid #b8e6c4;
            border-radius: 10px; padding: 12px 16px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            color: #028a2e; font-weight: 500; margin-bottom: 16px;
        }
    </style>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:400px">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync-POS" class="brand-logo">
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            {{-- Session Status --}}
            @if (session('status') == 'verification-link-sent')
                <div class="session-status">
                    <div style="display:flex;align-items:center;gap:8px">
                        <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            @endif

            {{-- Verify Email Card --}}
            <div class="form-card">
                <div class="verify-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h2 style="font-family:'Outfit',sans-serif;font-size:18px;font-weight:600;color:#02182F;margin-bottom:8px">
                    Verify your email
                </h2>
                <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:24px">
                    Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent to you. If you didn't receive the email, we'll gladly send you another.
                </p>

                <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Resend Verification Email</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>