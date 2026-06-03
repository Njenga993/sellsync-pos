<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">People</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Staff Members
                </h1>
            </div>
            <a href="{{ route('staff.create') }}"
               style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s">
                + Add Staff
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: 'Outfit', sans-serif; 
        }
        .data-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 20px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .data-table td {
            padding: 14px 20px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        
        .staff-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .staff-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: white;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }
        .badge-teal   { background: #ccfbf1; color: #0f766e; }
        .badge-amber  { background: #fef3c7; color: #b45309; }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #1a56db; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.edit { color: #1a56db; }
        .action-link.edit:hover { color: #1e40af; text-decoration: underline; }
        .action-link.remove { color: #dc2626; background: none; border: none; cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 600; }
        .action-link.remove:hover { color: #b91c1c; text-decoration: underline; }
        
        .you-tag {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            background: #eff4ff;
            color: #1a56db;
            letter-spacing: 0.03em;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#15803d;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#b91c1c;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Staff Table --}}
        <div class="panel">
            @if($staff->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = ['#1a56db','#0d9488','#7c3aed','#d97706','#dc2626','#0891b2','#4f46e5','#16a34a'];
                            $roleColors = [
                                'admin'     => 'badge-purple',
                                'manager'   => 'badge-blue',
                                'cashier'   => 'badge-teal',
                                'staff'     => 'badge-amber',
                            ];
                        @endphp
                        @foreach($staff as $member)
                            @php
                                $initials = strtoupper(substr($member->name, 0, 2));
                                $avatarBg = $avatarColors[$member->id % count($avatarColors)];
                                $roleName = $member->roles->first()?->name ?? 'staff';
                                $roleBadge = $roleColors[$roleName] ?? 'badge-blue';
                            @endphp
                            <tr>
                                <td>
                                    <div class="staff-name-cell">
                                        <div class="staff-avatar" style="background:{{ $avatarBg }}">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw6" style="color:#111827">{{ $member->name }}</div>
                                            @if($member->phone)
                                                <div class="mono" style="font-size:11px;color:#9ca3af">{{ $member->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;color:#6b7280">{{ $member->email }}</td>
                                <td>
                                    <span class="role-badge {{ $roleBadge }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:#6b7280">
                                    {{ $member->branch->name ?? '—' }}
                                </td>
                                <td>
                                    <span class="badge {{ $member->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <a href="{{ route('staff.edit', $member) }}" class="action-link edit">Edit</a>
                                        @if($member->id !== auth()->id())
                                            <form method="POST" action="{{ route('staff.destroy', $member) }}"
                                                  onsubmit="return confirm('Remove this staff member?')" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-link remove">Remove</button>
                                            </form>
                                        @else
                                            <span class="you-tag">YOU</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($staff->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $staff->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No staff members yet</p>
                    <a href="{{ route('staff.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#1a56db;text-decoration:none">
                        Add your first staff member →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>