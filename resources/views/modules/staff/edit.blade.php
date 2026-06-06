<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('staff.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">People</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Edit Staff Member
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
            --brand-blue: #3D7BE7;
            --brand-blue-light: #edf3fd;
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
        
        .form-select {
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
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: var(--brand-green);
            background-color: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
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
        <div style="max-width:580px;margin:0 auto;padding:0 16px">

            {{-- Staff Info Bar --}}
            <div class="info-bar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Editing: <strong>{{ $staff->name }}</strong>
                · Role: <strong>{{ ucfirst($staff->roles->first()?->name ?? 'staff') }}</strong>
                · Branch: <strong>{{ $staff->branch->name ?? '—' }}</strong>
            </div>

            <div class="form-panel">
                <form method="POST" action="{{ route('staff.update', $staff) }}">
                    @csrf @method('PUT')

                    <div class="section-title">Personal Information</div>

                    <div class="form-group">
                        <label class="form-label" for="name">Full Name *</label>
                        <input id="name" name="name" type="text" class="form-input"
                            value="{{ old('name', $staff->name) }}" required />
                        @if($errors->has('name'))
                            <div class="error-msg">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address *</label>
                            <input id="email" name="email" type="email" class="form-input"
                                value="{{ old('email', $staff->email) }}" required />
                            @if($errors->has('email'))
                                <div class="error-msg">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input id="phone" name="phone" type="text" class="form-input"
                                value="{{ old('phone', $staff->phone) }}" />
                        </div>
                    </div>

                    <div class="section-title" style="margin-top:4px">Role & Assignment</div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="role">Role *</label>
                            <select id="role" name="role" class="form-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role', $staff->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="branch_id">Branch *</label>
                            <select id="branch_id" name="branch_id" class="form-select">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id', $staff->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active"   {{ old('status', $staff->status) === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $staff->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="section-title" style="margin-top:4px">Change Password (optional)</div>

                    <div class="grid-2" style="margin-bottom:20px">
                        <div class="form-group">
                            <label class="form-label" for="password">New Password</label>
                            <input id="password" name="password" type="password" class="form-input"
                                placeholder="Leave blank to keep current" />
                            @if($errors->has('password'))
                                <div class="error-msg">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation"
                                type="password" class="form-input" placeholder="Re-enter new password" />
                        </div>
                    </div>

                    <div style="display:flex;gap:12px">
                        <button type="submit" class="btn-primary">Update Staff Member</button>
                        <a href="{{ route('staff.index') }}" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>