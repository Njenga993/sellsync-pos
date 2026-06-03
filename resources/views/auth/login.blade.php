<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        /* Override guest layout background */
        .min-h-screen {
            background: linear-gradient(135deg, #fafbff 0%, #eff4ff 100%) !important;
        }
        
        .login-container {
            font-family: 'Outfit', sans-serif;
        }
        
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .brand-icon {
            width: 48px;
            height: 48px;
            background: #eff4ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
        
        .brand-icon svg {
            width: 24px;
            height: 24px;
            color: #1a56db;
        }
        
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }
        
        .brand-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
            letter-spacing: 0.05em;
            margin-top: 4px;
        }
        
        .form-card {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
        }
        
        .form-input:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .form-input::placeholder {
            color: #c4c9d6;
        }
        
        .checkbox-custom {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 4px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
            flex-shrink: 0;
        }
        
        .checkbox-custom:checked {
            background: #1a56db;
            border-color: #1a56db;
        }
        
        .checkbox-custom:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .checkbox-custom:focus {
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.15);
            border-color: #1a56db;
        }
        
        .btn-login {
            width: 100%;
            padding: 11px 20px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s ease;
        }
        
        .btn-login:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .link-forgot {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.15s;
        }
        
        .link-forgot:hover {
            color: #1a56db;
            text-decoration: underline;
        }
        
        .error-message {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
        
        .remember-label {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }
        
        .session-status {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 12px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #15803d;
            font-weight: 500;
            margin-bottom: 16px;
        }
    </style>

    <div class="login-container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:400px">
            
            {{-- Brand Header --}}
            <div class="brand-header">
                <div class="brand-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="session-status">
                    <div style="display:flex;align-items:center;gap:8px">
                        <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
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

                    {{-- Email Address --}}
                    <div style="margin-bottom:16px">
                        <label class="form-label" for="email">Email Address</label>
                        <input 
                            id="email" 
                            class="form-input" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="you@example.com"
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                        @if ($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div style="margin-bottom:16px">
                        <label class="form-label" for="password">Password</label>
                        <input 
                            id="password" 
                            class="form-input" 
                            type="password" 
                            name="password" 
                            placeholder="••••••••"
                            required 
                            autocomplete="current-password"
                        >
                        @if ($errors->has('password'))
                            <div class="error-message">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    {{-- Remember Me + Forgot Password --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                        <label for="remember_me" style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                class="checkbox-custom" 
                                name="remember"
                            >
                            <span class="remember-label">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="link-forgot" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn-login">
                        Sign In
                    </button>
                </form>
            </div>

            {{-- Register Link --}}
            @if (Route::has('register'))
                <div style="text-align:center;margin-top:20px">
                    <span style="font-family:'Outfit',sans-serif;font-size:13px;color:#9ca3af">
                        Don't have an account?
                    </span>
                    <a href="{{ route('register') }}" style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#1a56db;text-decoration:none;margin-left:4px">
                        Start free trial →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>