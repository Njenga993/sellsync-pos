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

        /* Remove Laravel default background pattern */
        .bg-gray-100, .min-h-screen, body, .dark\:bg-black, [class*="bg-gray"] {
            background: linear-gradient(160deg, #FFFEFE 0%, #e6f7eb 50%, #edf3fd 100%) !important;
            background-image: none !important;
        }
        
        .login-container { font-family: 'Outfit', sans-serif; }
        
        .brand-header { text-align: center; margin-bottom: 28px; }
        
        .brand-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            object-fit: contain;
            display: block;
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
        
        .checkbox-custom {
            appearance: none; width: 16px; height: 16px;
            border: 1.5px solid #d1d5db; border-radius: 4px;
            background: var(--brand-white); cursor: pointer;
            transition: all 0.15s; position: relative; flex-shrink: 0;
        }
        .checkbox-custom:checked { background: var(--brand-green); border-color: var(--brand-green); }
        .checkbox-custom:checked::after {
            content: ''; position: absolute; left: 4px; top: 1px;
            width: 5px; height: 9px; border: solid white;
            border-width: 0 2px 2px 0; transform: rotate(45deg);
        }
        .checkbox-custom:focus { box-shadow: 0 0 0 3px rgba(3,167,55,0.15); border-color: var(--brand-green); }
        
        .btn-login {
            width: 100%; padding: 11px 20px;
            background: var(--brand-green); color: var(--brand-white);
            border: none; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 600; letter-spacing: 0.01em; cursor: pointer;
            box-shadow: 0 2px 8px rgba(3,167,55,0.25);
            transition: all 0.15s ease;
        }
        .btn-login:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3,167,55,0.35);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }
        
        .link-forgot {
            font-family: 'Outfit', sans-serif; font-size: 12px;
            font-weight: 500; color: #6b7280; text-decoration: none;
            transition: color 0.15s;
        }
        .link-forgot:hover { color: var(--brand-green); text-decoration: underline; }
        
        .link-signup {
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 600; color: var(--brand-green); text-decoration: none;
        }
        .link-signup:hover { color: var(--brand-green-dark); text-decoration: underline; }
        
        .error-message {
            font-family: 'Outfit', sans-serif; font-size: 12px;
            color: #dc2626; margin-top: 6px;
        }
        .remember-label {
            font-family: 'Outfit', sans-serif; font-size: 13px;
            color: #6b7280; cursor: pointer; user-select: none;
        }
        .session-status {
            background: #e6f7eb; border: 1px solid #b8e6c4;
            border-radius: 10px; padding: 12px 16px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            color: #028a2e; font-weight: 500; margin-bottom: 16px;
        }
        .google-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 10px 20px;
            background: var(--brand-white); border: 1.5px solid #e4e7ef;
            border-radius: 10px; font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 500; color: #374151;
            text-decoration: none; cursor: pointer; transition: all 0.15s ease;
        }
        .google-btn:hover { border-color: var(--brand-green); background: var(--brand-green-light); }
    </style>

    <div class="login-container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:400px">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync-POS" class="brand-logo">
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            {{-- Session Status --}}
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

            {{-- Login Form --}}
            <div class="form-card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div style="margin-bottom:16px">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" class="form-input" type="email" name="email" 
                            value="{{ old('email') }}" placeholder="you@example.com"
                            required autofocus autocomplete="username">
                        @if ($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div style="margin-bottom:16px">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" class="form-input" type="password" name="password" 
                            placeholder="••••••••" required autocomplete="current-password">
                        @if ($errors->has('password'))
                            <div class="error-message">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                        <label for="remember_me" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input id="remember_me" type="checkbox" class="checkbox-custom" name="remember">
                            <span class="remember-label">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="link-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">Sign In</button>
                </form>

                {{-- Divider --}}
                <div style="display:flex;align-items:center;gap:12px;margin:20px 0">
                    <div style="flex:1;height:1px;background:#e4e7ef"></div>
                    <span style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif;text-transform:uppercase;letter-spacing:0.05em">or continue with</span>
                    <div style="flex:1;height:1px;background:#e4e7ef"></div>
                </div>

                <a href="{{ route('google.login') }}" class="google-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
            </div>

            {{-- Register Link --}}
            @if (Route::has('register'))
                <div style="text-align:center;margin-top:20px">
                    <span style="font-family:'Outfit',sans-serif;font-size:13px;color:#9ca3af">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="link-signup" style="margin-left:4px">Start free trial →</a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>