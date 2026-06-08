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
        }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600; color: #374151;
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 6px; display: block;
        }
        
        .form-input {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid #e4e7ef; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 14px;
            color: var(--brand-black); background: #fafbff;
            transition: all 0.15s ease; outline: none;
        }
        .form-input:focus {
            border-color: var(--brand-green); background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3,167,55,0.08);
        }
        .form-input::placeholder { color: #c4c9d6; }
        
        .btn-submit {
            width: 100%; padding: 11px 20px;
            background: var(--brand-green); color: var(--brand-white);
            border: none; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 600; letter-spacing: 0.01em; cursor: pointer;
            box-shadow: 0 2px 8px rgba(3,167,55,0.25);
            transition: all 0.15s ease;
        }
        .btn-submit:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3,167,55,0.35);
            transform: translateY(-1px);
        }
        .btn-submit:active { transform: translateY(0); }
        
        .session-status {
            background: #e6f7eb; border: 1px solid #b8e6c4;
            border-radius: 10px; padding: 12px 16px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            color: #028a2e; font-weight: 500; margin-bottom: 16px;
        }
        
        .error-message {
            font-family: 'Outfit', sans-serif; font-size: 12px;
            color: #dc2626; margin-top: 6px;
            padding: 8px 12px; border-radius: 8px;
            background: #fef2f2; border: 1px solid #fecaca;
            display: flex; align-items: flex-start; gap: 6px;
            line-height: 1.4;
        }
        .error-message svg { flex-shrink: 0; margin-top: 1px; }
        
        .back-link {
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 500; color: #6b7280; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: color 0.15s;
        }
        .back-link:hover { color: var(--brand-green); }
    </style>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:400px">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync-POS" class="brand-logo">
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            {{-- Session Status (success message after email sent) --}}
            @if (session('status'))
                <div class="session-status">
                    <div style="display:flex;align-items:center;gap:8px">
                        <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            {{-- Forgot Password Form --}}
            <div class="form-card">
                <div style="margin-bottom:20px">
                    <h2 style="font-family:'Outfit',sans-serif;font-size:18px;font-weight:600;color:var(--brand-black);margin-bottom:6px">
                        Reset your password
                    </h2>
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#6b7280;line-height:1.5">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div style="margin-bottom:20px">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" class="form-input" type="email" name="email" 
                            value="{{ old('email') }}" placeholder="you@example.com"
                            required autofocus autocomplete="email">
                        
                        @if ($errors->has('email'))
                            <div class="error-message">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>{{ $errors->first('email') }}</span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">
                        Send Reset Link
                    </button>
                </form>
            </div>

            {{-- Back to Login --}}
            <div style="text-align:center;margin-top:20px">
                <a href="{{ route('login') }}" class="back-link">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>