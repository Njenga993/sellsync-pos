<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">People</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Customers
                </h1>
            </div>
            <a href="{{ route('customers.create') }}"
               style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
               onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
               onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                + Add Customer
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .panel {
            background: #FFFEFE;
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
        
        .customer-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .customer-avatar {
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
            color: #FFFEFE;
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
        .badge-green  { background: #e6f7eb; color: #028a2e; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-indigo { background: #e0e7ff; color: #4338ca; }
        
        .loyalty-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 99px;
            background: #fffbeb;
            color: #a16207;
            font-size: 11px;
            font-weight: 600;
            font-family: 'JetBrains Mono', monospace;
        }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #03A737; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.view { color: #6b7280; }
        .action-link.view:hover { color: #02182F; text-decoration: underline; }
        .action-link.edit { color: #03A737; }
        .action-link.edit:hover { color: #028a2e; text-decoration: underline; }
        .action-link.delete { color: #dc2626; background: none; border: none; cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 600; }
        .action-link.delete:hover { color: #b91c1c; text-decoration: underline; }
        
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

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Customers Table --}}
        <div class="panel">
            @if($customers->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Loyalty Points</th>
                            <th>Total Spent</th>
                            <th>Status</th>
                            <th style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = ['#03A737','#3D7BE7','#02182F','#d97706','#dc2626','#0891b2','#7c3aed','#028a2e'];
                        @endphp
                        @foreach($customers as $customer)
                            @php
                                $initials = strtoupper(substr($customer->name, 0, 2));
                                $avatarBg = $avatarColors[$customer->id % count($avatarColors)];
                            @endphp
                            <tr>
                                <td>
                                    <div class="customer-name-cell">
                                        <div class="customer-avatar" style="background:{{ $avatarBg }}">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw6" style="color:#02182F">{{ $customer->name }}</div>
                                            @if($customer->email)
                                                <div style="font-size:11px;color:#9ca3af">{{ $customer->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="mono" style="font-size:12px;color:#6b7280">{{ $customer->phone ?? '—' }}</td>
                                <td style="font-size:12px;color:#6b7280">{{ $customer->city ?? '—' }}</td>
                                <td>
                                    <span class="loyalty-chip">
                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        {{ number_format($customer->loyalty_points) }} pts
                                    </span>
                                </td>
                                <td class="mono fw6" style="color:#02182F">KES {{ number_format($customer->total_spent, 2) }}</td>
                                <td>
                                    <span class="badge {{ $customer->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <a href="{{ route('customers.show', $customer) }}" class="action-link view">View</a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="action-link edit">Edit</a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                              onsubmit="return confirm('Delete this customer?')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($customers->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $customers->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No customers yet</p>
                    <a href="{{ route('customers.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#03A737;text-decoration:none">
                        Add your first customer →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>