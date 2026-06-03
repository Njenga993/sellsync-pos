<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">System</p>
            <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                Business Settings & Receipt Customisation
            </h1>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 16px; }
        
        .form-panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 24px;
        }
        
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: #c4c9d6;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f3f8;
        }
        
        .section-subtitle {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 14px;
            margin-top: -8px;
        }
        
        .form-group {
            margin-bottom: 14px;
        }
        .form-group:last-child { margin-bottom: 0; }
        
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
        .form-input::placeholder { color: #c4c9d6; }
        
        .form-textarea {
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
            resize: vertical;
            min-height: 60px;
        }
        .form-textarea:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .form-hint {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        @media (max-width: 640px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
        
        .checkbox-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }
        .checkbox-card:hover {
            border-color: #1a56db;
            background: #eff4ff;
        }
        .checkbox-card.checked {
            border-color: #1a56db;
            background: #eff4ff;
        }
        
        .checkbox-custom {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid #d1d5db;
            border-radius: 5px;
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
            left: 5px;
            top: 2px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .file-upload-area {
            border: 2px dashed #e4e7ef;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            transition: all 0.15s;
            cursor: pointer;
        }
        .file-upload-area:hover {
            border-color: #1a56db;
            background: #eff4ff;
        }
        
        .file-input {
            width: 100%;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #6b7280;
        }
        .file-input::file-selector-button {
            margin-right: 14px;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            background: #eff4ff;
            color: #1a56db;
            cursor: pointer;
            transition: all 0.15s;
        }
        .file-input::file-selector-button:hover {
            background: #dbeafe;
        }
        
        .logo-preview {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid #e4e7ef;
            border-radius: 10px;
            padding: 8px;
            background: #ffffff;
        }
        .logo-placeholder {
            width: 80px;
            height: 80px;
            border: 2px dashed #e4e7ef;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-logo-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            cursor: pointer;
            margin-top: 8px;
        }
        
        .btn-primary {
            background: #1a56db;
            color: white;
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.01em;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-cancel {
            background: transparent;
            color: #6b7280;
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: 1.5px solid #e4e7ef;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-cancel:hover {
            border-color: #1a56db;
            color: #1a56db;
            background: #eff4ff;
        }
        
        .receipt-preview {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 12px;
            padding: 20px 18px;
            width: 260px;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: #374151;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .receipt-preview .r-divider {
            border-top: 1px dashed #d1d5db;
            margin: 8px 0;
        }
        .receipt-preview .r-center { text-align: center; }
        .receipt-preview .r-bold { font-weight: 700; }
        .receipt-preview .r-mono { font-family: 'JetBrains Mono', monospace; font-size: 10px; }
        .receipt-preview .r-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            margin: 0 auto 6px;
            display: block;
        }
        .receipt-preview .r-flex {
            display: flex;
            justify-content: space-between;
        }
        .receipt-preview .r-total {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }
        .receipt-preview .r-italic { font-style: italic; }
        .receipt-preview .r-text-xs { font-size: 9px; color: #9ca3af; }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#15803d;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- Logo --}}
            <div class="form-panel">
                <div class="section-title">Business Logo</div>

                <div style="display:flex;align-items:flex-start;gap:20px">
                    <div class="flex-shrink-0">
                        @if($settings->logo_path)
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Business Logo" class="logo-preview" />
                        @else
                            <div class="logo-placeholder">
                                <svg width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1">
                        <div class="file-upload-area">
                            <input type="file" name="logo" accept="image/png,image/jpeg" class="file-input" />
                        </div>
                        <p class="form-hint">PNG or JPG, max 2MB. Recommended: 200x200px</p>

                        @if($settings->logo_path)
                            <label class="remove-logo-btn">
                                <input type="checkbox" name="remove_logo" value="1" class="checkbox-custom" />
                                Remove current logo
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Business Information --}}
            <div class="form-panel">
                <div class="section-title">Business Information</div>
                <p class="section-subtitle">This information appears on your receipts.</p>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="text" class="form-input"
                            value="{{ old('phone', $settings->phone) }}" placeholder="e.g. 0712 345 678" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input id="email" name="email" type="email" class="form-input"
                            value="{{ old('email', $settings->email) }}" placeholder="info@yourbusiness.com" />
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="city">City / Town</label>
                        <input id="city" name="city" type="text" class="form-input"
                            value="{{ old('city', $settings->city) }}" placeholder="e.g. Nairobi" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="website">Website (optional)</label>
                        <input id="website" name="website" type="text" class="form-input"
                            value="{{ old('website', $settings->website) }}" placeholder="www.yourbusiness.com" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Physical Address</label>
                    <textarea id="address" name="address" rows="2" class="form-textarea"
                        placeholder="Full street address">{{ old('address', $settings->address) }}</textarea>
                </div>
            </div>

            {{-- Tax & Currency --}}
            <div class="form-panel">
                <div class="section-title">Tax & Currency</div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="currency_symbol">Currency Symbol *</label>
                        <input id="currency_symbol" name="currency_symbol" type="text" class="form-input"
                            value="{{ old('currency_symbol', $settings->currency_symbol) }}" placeholder="KES" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="currency_code">Currency Code *</label>
                        <input id="currency_code" name="currency_code" type="text" class="form-input"
                            value="{{ old('currency_code', $settings->currency_code) }}" placeholder="KES" />
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="tax_name">Tax Name</label>
                        <input id="tax_name" name="tax_name" type="text" class="form-input"
                            value="{{ old('tax_name', $settings->tax_name) }}" placeholder="VAT" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tax_number">Tax / PIN Number</label>
                        <input id="tax_number" name="tax_number" type="text" class="form-input"
                            value="{{ old('tax_number', $settings->tax_number) }}" placeholder="P051234567A" />
                    </div>
                </div>
            </div>

            {{-- Receipt Customisation --}}
            <div class="form-panel">
                <div class="section-title">Receipt Customisation</div>

                <div class="form-group">
                    <label class="form-label" for="receipt_header">Receipt Header Message</label>
                    <textarea id="receipt_header" name="receipt_header" rows="2" class="form-textarea"
                        placeholder="e.g. Welcome to Nyakazi Organics!">{{ old('receipt_header', $settings->receipt_header) }}</textarea>
                    <p class="form-hint">Shown at the top of every receipt</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="receipt_footer">Receipt Footer Message</label>
                    <textarea id="receipt_footer" name="receipt_footer" rows="2" class="form-textarea"
                        placeholder="e.g. Thank you for your business!">{{ old('receipt_footer', $settings->receipt_footer) }}</textarea>
                    <p class="form-hint">Shown at the bottom of every receipt</p>
                </div>

                <div style="margin-bottom:14px">
                    <label class="form-label" style="margin-bottom:10px">Show on Receipt</label>
                    <div class="grid-3">
                        @foreach([
                            ['receipt_show_logo',    'Business Logo'],
                            ['receipt_show_address', 'Business Address'],
                            ['receipt_show_phone',   'Phone Number'],
                            ['receipt_show_email',   'Email Address'],
                            ['receipt_show_tax',     'Tax / PIN Number'],
                            ['receipt_show_loyalty', 'Loyalty Points'],
                        ] as [$field, $label])
                            @php $isChecked = old($field, $settings->$field); @endphp
                            <label class="checkbox-card {{ $isChecked ? 'checked' : '' }}" id="card-{{ $field }}">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                    {{ $isChecked ? 'checked' : '' }}
                                    class="checkbox-custom"
                                    onchange="document.getElementById('card-{{ $field }}').classList.toggle('checked', this.checked)" />
                                <span style="font-family:'Outfit',sans-serif;font-size:13px;color:#374151;font-weight:500">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Receipt Preview --}}
            <div class="form-panel">
                <div class="section-title">Receipt Preview</div>
                <div style="display:flex;justify-content:center">
                    <div class="receipt-preview">
                        @if($settings->receipt_show_logo && $settings->logo_path)
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="r-logo" />
                        @endif
                        <div class="r-center r-bold" style="font-size:13px">{{ auth()->user()->tenant->name }}</div>
                        @if($settings->receipt_show_address && $settings->address)
                            <div class="r-center r-text-xs">{{ $settings->address }}</div>
                        @endif
                        @if($settings->receipt_show_phone && $settings->phone)
                            <div class="r-center r-text-xs">{{ $settings->phone }}</div>
                        @endif
                        @if($settings->receipt_show_email && $settings->email)
                            <div class="r-center r-text-xs">{{ $settings->email }}</div>
                        @endif
                        @if($settings->receipt_header)
                            <div class="r-center r-italic" style="margin-top:4px;color:#1a56db;font-weight:500">{{ $settings->receipt_header }}</div>
                        @endif
                        <div class="r-divider"></div>
                        <div class="r-flex r-mono"><span>Invoice:</span><span>INV-XXXX-000001</span></div>
                        <div class="r-flex r-mono"><span>Date:</span><span>{{ now()->format('d/m/Y') }}</span></div>
                        <div class="r-divider"></div>
                        <div>
                            <div class="r-flex">
                                <span>Sample Product</span>
                            </div>
                            <div class="r-flex r-mono" style="padding-left:8px">
                                <span>2 x {{ $settings->currency_symbol }} 100.00</span>
                                <span>{{ $settings->currency_symbol }} 200.00</span>
                            </div>
                        </div>
                        <div class="r-divider"></div>
                        <div class="r-flex r-total"><span>TOTAL</span><span>{{ $settings->currency_symbol }} 200.00</span></div>
                        @if($settings->receipt_show_tax && $settings->tax_number)
                            <div class="r-text-xs" style="margin-top:4px">{{ $settings->tax_name }} No: {{ $settings->tax_number }}</div>
                        @endif
                        @if($settings->receipt_show_loyalty)
                            <div class="r-text-xs" style="margin-top:2px">Loyalty Points Earned: 2</div>
                        @endif
                        <div class="r-divider"></div>
                        @if($settings->receipt_footer)
                            <div class="r-center r-italic r-text-xs">{{ $settings->receipt_footer }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-primary">Save Settings</button>
                <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>