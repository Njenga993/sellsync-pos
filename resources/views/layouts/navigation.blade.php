<nav style="
    position:fixed; left:0; top:0; width:260px; height:100vh;
    background:#ffffff; border-right:1px solid #e4e7ef;
    display:flex; flex-direction:column; overflow-y:auto;
    z-index:50; font-family:'Outfit',sans-serif;
    box-shadow:1px 0 0 #f1f3f8;
" id="pos-sidebar">

    {{-- ── Logo / Brand ── --}}
    <div style="padding:22px 20px 18px;border-bottom:1px solid #f1f3f8">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
            <div style="width:38px;height:38px;background:#eff4ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="20" height="20" fill="none" stroke="#1a56db" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:#111827;line-height:1">RetailHub</div>
                <div style="font-size:11px;color:#9ca3af;margin-top:2px;font-weight:500">{{ auth()->user()->tenant->name ?? 'Your Store' }}</div>
            </div>
        </div>
    </div>

    {{-- ── Navigation ── --}}
    <div style="flex:1;padding:12px 10px;display:flex;flex-direction:column;gap:1px;overflow-y:auto">

        @php
        function navItem($route, $label, $icon, $match = null) {
            $active = $match ? request()->routeIs($match) : request()->routeIs($route);
            $bg     = $active ? 'background:#eff4ff' : '';
            $color  = $active ? 'color:#1a56db;font-weight:600' : 'color:#4b5563;font-weight:500';
            $bar    = $active ? '<span style="position:absolute;left:0;top:20%;height:60%;width:3px;background:#1a56db;border-radius:0 3px 3px 0"></span>' : '';
            $url    = route($route);
            return "
            <a href=\"{$url}\" style=\"display:flex;align-items:center;gap:11px;padding:9px 14px;border-radius:10px;text-decoration:none;font-size:13.5px;transition:all .12s;position:relative;{$bg};{$color}\">
                {$bar}
                <span style=\"width:30px;display:flex;align-items:center;justify-content:center;flex-shrink:0\">{$icon}</span>
                <span style=\"flex:1\">{$label}</span>
            </a>";
        }
        @endphp

        {{-- Main --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:4px">Main</div>

        {!! navItem('dashboard', 'Dashboard',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>'
        ) !!}

        {!! navItem('pos.index', 'POS Terminal',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'pos.*'
        ) !!}

        {{-- Inventory --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">Inventory</div>

        {!! navItem('products.index', 'Products',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            'products.*'
        ) !!}

        {!! navItem('categories.index', 'Categories',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>',
            'categories.*'
        ) !!}

        {!! navItem('stock.index', 'Stock Management',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
            'stock.*'
        ) !!}

        {!! navItem('suppliers.index', 'Suppliers & POs',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
            'suppliers.*'
        ) !!}

        {{-- People --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">People</div>

        {!! navItem('customers.index', 'Customers',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'customers.*'
        ) !!}

        {!! navItem('staff.index', 'Staff',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
            'staff.*'
        ) !!}

        {{-- Finance & Reports --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">Finance</div>

        {!! navItem('reports.sales', 'Sales Report',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
        ) !!}

        {!! navItem('reports.profit-loss', 'Profit & Loss',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ) !!}

        {!! navItem('reports.stock-valuation', 'Stock Valuation',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>'
        ) !!}

        {!! navItem('reports.cashier-performance', 'Performance',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>'
        ) !!}

        {!! navItem('expenses.index', 'Expenses',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
            'expenses.*'
        ) !!}

        {!! navItem('returns.index', 'Returns & Refunds',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>',
            'returns.*'
        ) !!}

        {{-- Settings --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">System</div>

        {!! navItem('settings.index', 'Settings',
            '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>',
            'settings.*'
        ) !!}

    </div>

    {{-- ── Footer / User ── --}}
    <div style="padding:14px 16px 16px;border-top:1px solid #f1f3f8;margin-top:auto">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
            <div style="width:36px;height:36px;background:#1a56db;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="
                display:flex;align-items:center;gap:8px;width:100%;padding:9px 12px;
                border-radius:8px;background:transparent;border:1px solid #e4e7ef;
                font-family:'Outfit',sans-serif;font-size:13px;font-weight:500;
                color:#6b7280;cursor:pointer;transition:all .15s;
            " onmouseover="this.style.background='#fef2f2';this.style.borderColor='#dc2626';this.style.color='#dc2626'"
               onmouseout="this.style.background='transparent';this.style.borderColor='#e4e7ef';this.style.color='#6b7280'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out
            </button>
        </form>
    </div>
</nav>

{{-- Push content right of sidebar --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
    body { font-family:'Outfit',sans-serif; }
    /* Offset main content for sidebar */
    .min-h-screen.bg-gray-100 { padding-left: 260px !important; }
    @media(max-width:768px) {
        .min-h-screen.bg-gray-100 { padding-left:0 !important; }
        #pos-sidebar { transform:translateX(-100%); transition:transform .25s ease; }
        #pos-sidebar.open { transform:translateX(0); }
    }
</style>