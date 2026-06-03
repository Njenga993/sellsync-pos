<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('suppliers.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#1a56db';this.style.color='#1a56db';this.style.background='#eff4ff'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Add Supplier
                </h1>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
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
            min-height: 70px;
        }
        .form-textarea:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }
        
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
        
        .error-msg {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:620px;margin:0 auto;padding:0 16px">
            <div class="form-panel">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf

                    <div class="section-title">Company Information</div>

                    <div class="form-group">
                        <label class="form-label" for="name">Supplier / Company Name *</label>
                        <input id="name" name="name" type="text" class="form-input"
                            value="{{ old('name') }}" placeholder="e.g. Unga Group Ltd" required />
                        @if($errors->has('name'))
                            <div class="error-msg">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="contact_person">Contact Person</label>
                            <input id="contact_person" name="contact_person" type="text" class="form-input"
                                value="{{ old('contact_person') }}" placeholder="Sales rep name" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input id="phone" name="phone" type="text" class="form-input"
                                value="{{ old('phone') }}" placeholder="e.g. 0712 345 678" />
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input id="email" name="email" type="email" class="form-input"
                                value="{{ old('email') }}" placeholder="orders@supplier.com" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="city">City / Town</label>
                            <input id="city" name="city" type="text" class="form-input"
                                value="{{ old('city') }}" placeholder="e.g. Nairobi" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Address (optional)</label>
                        <textarea id="address" name="address" rows="2" class="form-textarea"
                            placeholder="Full street address">{{ old('address') }}</textarea>
                    </div>

                    <div class="section-title" style="margin-top:4px">Additional Details</div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Notes (optional)</label>
                        <textarea id="notes" name="notes" rows="2" class="form-textarea"
                            placeholder="Payment terms, delivery notes, etc.">{{ old('notes') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:20px">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div style="display:flex;gap:12px">
                        <button type="submit" class="btn-primary">Save Supplier</button>
                        <a href="{{ route('suppliers.index') }}" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>