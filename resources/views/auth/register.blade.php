<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .min-h-screen {
            background: linear-gradient(135deg, #fafbff 0%, #eff4ff 100%) !important;
        }
        
        .register-container {
            font-family: 'Outfit', sans-serif;
        }
        
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        
        .brand-icon {
            width: 48px;
            height: 48px;
            background: #eff4ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
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
        
        .register-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .register-subtitle {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
        
        .form-card {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        
        .section-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: #c4c9d6;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f3f8;
        }
        
        .form-group {
            margin-bottom: 16px;
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
        
        .form-select {
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
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }
        
        .form-select:focus {
            border-color: #1a56db;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .error-message {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
        
        .btn-register {
            width: 100%;
            padding: 12px 20px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-register:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .link-signin {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #1a56db;
            text-decoration: none;
            transition: color 0.15s;
        }
        
        .link-signin:hover {
            color: #1e40af;
            text-decoration: underline;
        }
        
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        
        @media (max-width: 480px) {
            .two-col {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="register-container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:480px">
            
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

            {{-- Form Card --}}
            <div class="form-card">
                <div style="text-align:center;margin-bottom:24px">
                    <div class="register-title">Create your POS account</div>
                    <p class="register-subtitle">Set up your business in under 2 minutes</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Business Information Section --}}
                    <div class="section-label">Business Information</div>
                    
                    <div class="form-group">
                        <label class="form-label" for="business_name">Business Name</label>
                        <input 
                            id="business_name" 
                            class="form-input" 
                            type="text"
                            name="business_name" 
                            value="{{ old('business_name') }}" 
                            required 
                            autofocus
                            placeholder="e.g. Kamau Supermarket"
                        >
                        @if ($errors->has('business_name'))
                            <div class="error-message">{{ $errors->first('business_name') }}</div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom:20px">
                        <label class="form-label" for="business_type">Business Type</label>
                        <select id="business_type" name="business_type" class="form-select">
                            <option value="">Select business type</option>
                            <option value="retail"      {{ old('business_type') == 'retail'      ? 'selected' : '' }}>🛍️ Retail Shop</option>
                            <option value="restaurant"  {{ old('business_type') == 'restaurant'  ? 'selected' : '' }}>🍽️ Restaurant / Cafe</option>
                            <option value="salon"       {{ old('business_type') == 'salon'       ? 'selected' : '' }}>💇 Salon / Spa</option>
                            <option value="pharmacy"    {{ old('business_type') == 'pharmacy'    ? 'selected' : '' }}>💊 Pharmacy</option>
                            <option value="wholesale"   {{ old('business_type') == 'wholesale'   ? 'selected' : '' }}>📦 Wholesale</option>
                            <option value="other"       {{ old('business_type') == 'other'       ? 'selected' : '' }}>🏢 Other</option>
                        </select>
                        @if ($errors->has('business_type'))
                            <div class="error-message">{{ $errors->first('business_type') }}</div>
                        @endif
                    </div>

                    {{-- Owner Information Section --}}
                    <div class="section-label">Your Details</div>

                    <div class="two-col">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input 
                                id="name" 
                                class="form-input" 
                                type="text"
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="John Doe"
                            >
                            @if ($errors->has('name'))
                                <div class="error-message">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input 
                                id="phone" 
                                class="form-input" 
                                type="text"
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required
                                placeholder="0712 345 678"
                            >
                            @if ($errors->has('phone'))
                                <div class="error-message">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input 
                            id="email" 
                            class="form-input" 
                            type="email"
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            placeholder="you@yourbusiness.com"
                        >
                        @if ($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    {{-- Security Section --}}
                    <div class="section-label">Security</div>

                    <div class="two-col">
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input 
                                id="password" 
                                class="form-input" 
                                type="password"
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Min. 8 characters"
                            >
                            @if ($errors->has('password'))
                                <div class="error-message">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input 
                                id="password_confirmation" 
                                class="form-input" 
                                type="password"
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Re-enter password"
                            >
                            @if ($errors->has('password_confirmation'))
                                <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn-register" style="margin-top:4px">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Create My POS Account
                    </button>
                </form>
            </div>

            {{-- Sign In Link --}}
            <div style="text-align:center;margin-top:20px">
                <span style="font-family:'Outfit',sans-serif;font-size:13px;color:#9ca3af">
                    Already have an account?
                </span>
                <a href="{{ route('login') }}" class="link-signin" style="margin-left:4px">
                    Sign in instead
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>