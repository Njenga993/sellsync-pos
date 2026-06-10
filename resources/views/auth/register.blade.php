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
        
        .register-container { font-family: 'Outfit', sans-serif; }
        
        .brand-header { text-align: center; margin-bottom: 24px; }
        
        .brand-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            object-fit: contain;
            display: block;
        }
        @media(min-width: 640px) {
            .brand-logo { width: 84px; height: 84px; }
        }
        
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 20px; font-weight: 700;
            color: var(--brand-black); line-height: 1;
        }
        
        .brand-sub {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px; color: var(--brand-green);
            font-weight: 600; letter-spacing: 0.08em; margin-top: 4px;
        }
        
        .register-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px; font-weight: 700;
            color: var(--brand-black); margin-bottom: 4px;
        }
        .register-subtitle { font-family: 'Outfit', sans-serif; font-size: 13px; color: #9ca3af; }
        
        .form-card {
            background: var(--brand-white);
            border: 1px solid #e4e7ef; border-radius: 14px;
            padding: 32px; box-shadow: 0 1px 3px rgba(2,24,47,0.04);
        }
        
        .section-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px; font-weight: 700; color: #c4c9d6;
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 14px; padding-bottom: 10px;
            border-bottom: 1px solid #f1f3f8;
        }
        
        .form-group { margin-bottom: 16px; }
        
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
        
        .form-select {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid #e4e7ef; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 14px;
            color: var(--brand-black); background: #fafbff;
            transition: all 0.15s ease; outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
            padding-right: 40px; cursor: pointer;
        }
        .form-select:focus {
            border-color: var(--brand-green); background-color: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3,167,55,0.08);
        }
        
        .error-message { font-family: 'Outfit', sans-serif; font-size: 12px; color: #dc2626; margin-top: 6px; }
        
        .btn-register {
            width: 100%; padding: 12px 20px;
            background: var(--brand-green); color: var(--brand-white);
            border: none; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 14px;
            font-weight: 600; letter-spacing: 0.01em; cursor: pointer;
            box-shadow: 0 2px 8px rgba(3,167,55,0.25);
            transition: all 0.15s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-register:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3,167,55,0.35);
            transform: translateY(-1px);
        }
        .btn-register:active { transform: translateY(0); }
        
        .link-signin {
            font-family: 'Outfit', sans-serif; font-size: 13px;
            font-weight: 600; color: var(--brand-green); text-decoration: none;
            transition: color 0.15s;
        }
        .link-signin:hover { color: var(--brand-green-dark); text-decoration: underline; }
        
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 480px) { .two-col { grid-template-columns: 1fr; } }
        
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

    <div class="register-container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
        <div style="width:100%;max-width:480px">
            
            <div class="brand-header">
                <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync-POS" class="brand-logo">
                <div class="brand-name">SellSync</div>
                <div class="brand-sub">POS</div>
            </div>

            <div class="form-card">
                <div style="text-align:center;margin-bottom:24px">
                    <div class="register-title">Create your POS account</div>
                    <p class="register-subtitle">Set up your business in under 2 minutes</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="section-label">Business Information</div>
                    
                    <div class="form-group">
                        <label class="form-label" for="business_name">Business Name</label>
                        <input id="business_name" class="form-input" type="text" name="business_name" 
                            value="{{ old('business_name') }}" required autofocus placeholder="e.g. Kamau Supermarket">
                        @if ($errors->has('business_name'))
                            <div class="error-message">{{ $errors->first('business_name') }}</div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom:20px">
                        <label class="form-label" for="business_type">Business Type</label>
                        <select id="business_type" name="business_type" class="form-select">
                            <option value="">Select business type</option>
                            <option value="retail" {{ old('business_type')=='retail' ? 'selected' : '' }}>Retail Shop</option>
                            <option value="restaurant" {{ old('business_type')=='restaurant' ? 'selected' : '' }}>Restaurant / Cafe</option>
                            <option value="salon" {{ old('business_type')=='salon' ? 'selected' : '' }}>Salon / Spa</option>
                            <option value="pharmacy" {{ old('business_type')=='pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="wholesale" {{ old('business_type')=='wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="other" {{ old('business_type')=='other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @if ($errors->has('business_type'))
                            <div class="error-message">{{ $errors->first('business_type') }}</div>
                        @endif
                    </div>

                    {{-- Number of Branches --}}
<div class="mb-4">
    <x-input-label for="branch_count" :value="__('How many branches do you have?')" />
    <select id="branch_count" name="branch_count"
        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500 text-sm">
        <option value="1"  {{ old('branch_count') == '1'  ? 'selected' : '' }}>Just 1 (single location)</option>
        <option value="2"  {{ old('branch_count') == '2'  ? 'selected' : '' }}>2 branches</option>
        <option value="3"  {{ old('branch_count') == '3'  ? 'selected' : '' }}>3 branches</option>
        <option value="4"  {{ old('branch_count') == '4'  ? 'selected' : '' }}>4 branches</option>
        <option value="5"  {{ old('branch_count') == '5'  ? 'selected' : '' }}>5 branches</option>
        <option value="6"  {{ old('branch_count', '1') > '5' ? 'selected' : '' }}>More than 5</option>
    </select>
    <p class="text-xs text-gray-400 mt-1">You can add and configure each branch after registration.</p>
    <x-input-error :messages="$errors->get('branch_count')" class="mt-2" />
</div>

                    <div class="section-label">Your Details</div>

                    <div class="two-col">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input id="name" class="form-input" type="text" name="name" 
                                value="{{ old('name') }}" required placeholder="John Doe">
                            @if ($errors->has('name'))
                                <div class="error-message">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input id="phone" class="form-input" type="text" name="phone" 
                                value="{{ old('phone') }}" required placeholder="0712 345 678">
                            @if ($errors->has('phone'))
                                <div class="error-message">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" class="form-input" type="email" name="email" 
                            value="{{ old('email') }}" required placeholder="you@yourbusiness.com">
                        @if ($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="section-label">Security</div>

                    <div class="two-col">
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input id="password" class="form-input" type="password" name="password" 
                                required autocomplete="new-password" placeholder="Min. 8 characters"
                                x-data @input="password = $el.value">
                            @if ($errors->has('password'))
                                <div class="error-message">{{ $errors->first('password') }}</div>
                            @endif
                            
                            {{-- Password Strength Checker --}}
                            <div x-data="{
                                password: '',
                                get lengthOk() { return this.password.length >= 8; },
                                get uppercaseOk() { return /[A-Z]/.test(this.password); },
                                get lowercaseOk() { return /[a-z]/.test(this.password); },
                                get numberOk() { return /[0-9]/.test(this.password); },
                                get specialOk() { return /[\W_]/.test(this.password); },
                                get strength() { let s=0; if(this.lengthOk)s++; if(this.uppercaseOk)s++; if(this.lowercaseOk)s++; if(this.numberOk)s++; if(this.specialOk)s++; return s; },
                                get strengthLabel() { if(this.strength<=1)return'Weak'; if(this.strength<=3)return'Fair'; if(this.strength<=4)return'Good'; return'Strong'; },
                                get strengthColor() { if(this.strength<=1)return'#dc2626'; if(this.strength<=3)return'#d97706'; if(this.strength<=4)return'#3D7BE7'; return'#03A737'; },
                                init() { const pw=document.getElementById('password'); pw.addEventListener('input',()=>{this.password=pw.value;}); }
                            }" style="margin-top:10px">
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                                    <div style="flex:1;height:4px;background:#e4e7ef;border-radius:99px;overflow:hidden">
                                        <div style="height:100%;width:0%;border-radius:99px;transition:all 0.3s"
                                             :style="'width:'+(strength/5*100)+'%;background:'+strengthColor"></div>
                                    </div>
                                    <span style="font-size:11px;font-weight:600;font-family:'Outfit',sans-serif"
                                          :style="'color:'+strengthColor" x-text="strengthLabel"></span>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:3px;font-size:11px;font-family:'Outfit',sans-serif">
                                    <div style="display:flex;align-items:center;gap:5px;color:#9ca3af;transition:color 0.2s" :style="lengthOk?'color:#03A737':''"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>8+ characters</div>
                                    <div style="display:flex;align-items:center;gap:5px;color:#9ca3af;transition:color 0.2s" :style="uppercaseOk?'color:#03A737':''"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Uppercase</div>
                                    <div style="display:flex;align-items:center;gap:5px;color:#9ca3af;transition:color 0.2s" :style="lowercaseOk?'color:#03A737':''"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Lowercase</div>
                                    <div style="display:flex;align-items:center;gap:5px;color:#9ca3af;transition:color 0.2s" :style="numberOk?'color:#03A737':''"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Number</div>
                                </div>
                                <div style="display:flex;align-items:center;gap:5px;margin-top:3px;font-size:11px;font-family:'Outfit',sans-serif;color:#9ca3af;transition:color 0.2s" :style="specialOk?'color:#03A737':''"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Special character</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" 
                                required autocomplete="new-password" placeholder="Re-enter password">
                            @if ($errors->has('password_confirmation'))
                                <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn-register" style="margin-top:4px">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Create My POS Account
                    </button>
                </form>

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

            <div style="text-align:center;margin-top:20px">
                <span style="font-family:'Outfit',sans-serif;font-size:13px;color:#9ca3af">Already have an account?</span>
                <a href="{{ route('login') }}" class="link-signin" style="margin-left:4px">Sign in instead</a>
            </div>
        </div>
    </div>
</x-guest-layout>