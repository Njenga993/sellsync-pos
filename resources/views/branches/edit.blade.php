<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('branches.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">System</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Edit Branch
                </h1>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        :root {
            --brand-green: #03A737;
            --brand-green-light: #e6f7eb;
            --brand-green-dark: #028a2e;
            --brand-black: #02182F;
            --brand-white: #FFFEFE;
        }
        
        .form-panel {
            background: var(--brand-white);
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 24px;
        }
        
        .info-bar {
            background: var(--brand-green-light);
            border: 1px solid #b8e6c4;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #028a2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group { margin-bottom: 14px; }
        
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
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--brand-green);
            background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        .form-input::placeholder { color: #c4c9d6; }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }
        
        .btn-primary {
            background: var(--brand-green);
            color: var(--brand-white);
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.01em;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
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
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-light);
        }
        
        .error-msg {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:540px;margin:0 auto;padding:0 16px">

            <div class="info-bar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Editing: <strong>{{ $branch->name }}</strong>
                @if($branch->is_main)
                    · <span style="background:#e6f7eb;color:#028a2e;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Main Branch</span>
                @endif
            </div>

            <div class="form-panel">
                <form method="POST" action="{{ route('branches.update', $branch) }}">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="name">Branch Name *</label>
                        <input id="name" name="name" type="text" class="form-input"
                            value="{{ old('name', $branch->name) }}" required />
                        @if($errors->has('name'))
                            <div class="error-msg">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="city">City / Town</label>
                            <input id="city" name="city" type="text" class="form-input"
                                value="{{ old('city', $branch->city) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone (optional)</label>
                            <input id="phone" name="phone" type="text" class="form-input"
                                value="{{ old('phone', $branch->phone) }}" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:20px">
                        <label class="form-label" for="address">Physical Address (optional)</label>
                        <input id="address" name="address" type="text" class="form-input"
                            value="{{ old('address', $branch->address) }}" />
                    </div>

                    <div style="display:flex;gap:12px">
                        <button type="submit" class="btn-primary">Update Branch</button>
                        <a href="{{ route('branches.index') }}" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>