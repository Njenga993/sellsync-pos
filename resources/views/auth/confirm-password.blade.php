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
        
        .error-message {
            font-family: 'Outfit', sans-serif; font-size: 12px;
            color: #dc2626; margin-top: 6px;
            padding: 8px 12px; border-radius: 8px;
            background: #fef2f2; border: 1px solid #fecaca;
            display: flex; align-items: flex-start; gap: 6px;
        }
        .error-message svg { flex-shrink: 0; margin-top: 1px; }
    </style>

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:400px">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync-POS" class="brand-logo">
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            {{-- Confirm Password Card --}}
            <div class="form-card">
                <div style="margin-bottom:20px">
                    <h2 style="font-family:'Outfit',sans-serif;font-size:18px;font-weight:600;color:#02182F;margin-bottom:6px">
                        Confirm your password
                    </h2>
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#6b7280;line-height:1.5">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div style="margin-bottom:20px">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" class="form-input" type="password" name="password" 
                            placeholder="••••••••" required autocomplete="current-password">
                        
                        @if ($errors->has('password'))
                            <div class="error-message">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>{{ $errors->first('password') }}</span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>