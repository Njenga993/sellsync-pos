<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Branches — SellSync</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green:       #03A737;
            --brand-green-light: #e6f7eb;
            --brand-green-dark:  #028a2e;
            --brand-black:       #02182F;
            --brand-white:       #FFFEFE;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(160deg, #FFFEFE 0%, #e6f7eb 50%, #edf3fd 100%);
            min-height: 100vh;
        }
        .form-input {
            width: 100%; padding: 9px 14px;
            border: 1.5px solid #e4e7ef; border-radius: 10px;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            color: var(--brand-black); background: #fafbff;
            transition: all .15s; outline: none;
        }
        .form-input:focus {
            border-color: var(--brand-green); background: white;
            box-shadow: 0 0 0 3px rgba(3,167,55,.08);
        }
        .form-input::placeholder { color: #c4c9d6; }
        .form-label {
            font-size: 11px; font-weight: 600; color: #374151;
            text-transform: uppercase; letter-spacing: .05em;
            margin-bottom: 5px; display: block;
        }
        .branch-card {
            background: white; border: 1.5px solid #e4e7ef;
            border-radius: 14px; padding: 20px; margin-bottom: 14px;
            transition: border-color .15s;
        }
        .branch-card:hover { border-color: var(--brand-green); }
        .main-badge {
            background: var(--brand-green-light); color: var(--brand-green);
            font-size: 10px; font-weight: 700; padding: 2px 10px;
            border-radius: 99px; letter-spacing: .05em; text-transform: uppercase;
        }
        .btn-primary {
            background: var(--brand-green); color: white;
            padding: 12px 28px; border-radius: 10px; border: none;
            font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all .15s;
            box-shadow: 0 2px 8px rgba(3,167,55,.25);
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3,167,55,.35);
            transform: translateY(-1px);
        }
        .btn-skip {
            background: transparent; color: #9ca3af;
            padding: 12px 20px; border-radius: 10px;
            border: 1px solid #e4e7ef;
            font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all .15s; text-decoration: none;
            display: inline-block;
        }
        .btn-skip:hover { border-color: #d1d5db; color: #6b7280; }
        .progress-bar {
            height: 4px; background: #e4e7ef; border-radius: 99px;
            margin-bottom: 32px; overflow: hidden;
        }
        .progress-fill {
            height: 100%; width: 66%;
            background: var(--brand-green); border-radius: 99px;
        }
    </style>
</head>
<body>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
    <div style="width:100%;max-width:640px">

        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:28px">
            <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync"
                 style="width:72px;height:72px;object-fit:contain;margin:0 auto 12px;display:block">
            <div style="font-size:20px;font-weight:700;color:var(--brand-black)">SellSync</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--brand-green);font-weight:600;letter-spacing:.08em;margin-top:3px">POS</div>
        </div>

        {{-- Progress --}}
        <div class="progress-bar"><div class="progress-fill"></div></div>

        {{-- Header --}}
        <div style="margin-bottom:24px">
            <h1 style="font-size:22px;font-weight:700;color:var(--brand-black);margin-bottom:6px">
                Set up your branches
            </h1>
            <p style="font-size:14px;color:#6b7280;line-height:1.5">
                Give each branch a proper name and location. You can always update these later from your settings.
            </p>
        </div>

        @if(session('info'))
            <div style="background:#eff4ff;border:1px solid #c7d7fb;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#1a56db;font-weight:500">
                {{ session('info') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('branch.setup.update') }}">
            @csrf

            @foreach($branches as $index => $branch)
                <div class="branch-card">
                    <input type="hidden" name="branches[{{ $index }}][id]" value="{{ $branch->id }}" />

                    {{-- Branch header --}}
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                        <div style="width:34px;height:34px;background:var(--brand-green-light);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div style="flex:1">
                            <div style="font-size:13px;font-weight:600;color:#374151">
                                {{ $branch->is_main ? 'Main Branch' : 'Branch ' . ($index + 1) }}
                            </div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:1px">
                                {{ $branch->is_main ? 'Your primary location' : 'Additional location' }}
                            </div>
                        </div>
                        @if($branch->is_main)
                            <span class="main-badge">Main</span>
                        @endif
                    </div>

                    {{-- Branch Name --}}
                    <div style="margin-bottom:12px">
                        <label class="form-label">Branch Name *</label>
                        <input type="text"
                            name="branches[{{ $index }}][name]"
                            class="form-input"
                            value="{{ old('branches.'.$index.'.name', $branch->is_main ? auth()->user()->tenant->name . ' — Main Branch' : '') }}"
                            placeholder="{{ $branch->is_main ? 'e.g. Westlands Branch' : 'e.g. CBD Branch, Mombasa Road Branch' }}"
                            required />
                        @error('branches.'.$index.'.name')
                            <p style="font-size:12px;color:#dc2626;margin-top:4px">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address + City --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                        <div>
                            <label class="form-label">City / Town</label>
                            <input type="text"
                                name="branches[{{ $index }}][city]"
                                class="form-input"
                                value="{{ old('branches.'.$index.'.city') }}"
                                placeholder="e.g. Nairobi" />
                        </div>
                        <div>
                            <label class="form-label">Phone (optional)</label>
                            <input type="text"
                                name="branches[{{ $index }}][phone]"
                                class="form-input"
                                value="{{ old('branches.'.$index.'.phone') }}"
                                placeholder="e.g. 0712 345 678" />
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="form-label">Physical Address (optional)</label>
                        <input type="text"
                            name="branches[{{ $index }}][address]"
                            class="form-input"
                            value="{{ old('branches.'.$index.'.address') }}"
                            placeholder="e.g. Ground Floor, ABC Plaza, Kimathi Street" />
                    </div>
                </div>
            @endforeach

            {{-- Actions --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px">
                <a href="{{ route('branch.setup.skip') }}" class="btn-skip">
                    Skip for now
                </a>
                <button type="submit" class="btn-primary">
                    Save Branches & Continue →
                </button>
            </div>
        </form>

        {{-- Note --}}
        <p style="text-align:center;font-size:12px;color:#9ca3af;margin-top:20px">
            You can rename or add more branches anytime from
            <strong style="color:#6b7280">Settings → Branches</strong>
        </p>
    </div>
</div>
</body>
</html>