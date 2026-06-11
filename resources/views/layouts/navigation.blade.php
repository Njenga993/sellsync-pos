<nav style="
    position:fixed; left:0; top:0; width:260px; height:100vh;
    background:#FFFEFE; border-right:1px solid #e4e7ef;
    display:flex; flex-direction:column; overflow-y:auto;
    z-index:50; font-family:'Outfit',sans-serif;
    box-shadow:1px 0 0 #f1f3f8;
" id="pos-sidebar">

    {{-- Logo / Brand --}}
    <div style="padding:24px 20px 20px;border-bottom:1px solid #f1f3f8">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px">
            <div style="flex-shrink:0">
                <img src="{{ asset('images/sellsyncLogo.png') }}" 
                     alt="SellSync-POS" 
                     style="width:64px;height:64px;object-fit:contain;display:block">
            </div>
            <div>
                <div style="font-size:17px;font-weight:700;color:#02182F;line-height:1">SellSync</div>
                <div style="font-size:11px;color:#03A737;margin-top:3px;font-weight:600;font-family:'JetBrains Mono',monospace;letter-spacing:0.08em">POS</div>
            </div>
        </div>
        <div style="font-size:11px;color:#9ca3af;font-weight:500">{{ auth()->user()->tenant->name ?? 'Your Store' }}</div>
    </div>

    {{-- Navigation --}}
    <div style="flex:1;padding:12px 10px;display:flex;flex-direction:column;gap:1px;overflow-y:auto">

        @php
if (!function_exists('navItem')) {
    function navItem($route, $label, $icon, $match = null) {
        $active = $match ? request()->routeIs($match) : request()->routeIs($route);
        $bg     = $active ? 'background:#e6f7eb' : '';
        $color  = $active ? 'color:#03A737;font-weight:600' : 'color:#4b5563;font-weight:500';
        $bar    = $active ? '<span style="position:absolute;left:0;top:20%;height:60%;width:3px;background:#03A737;border-radius:0 3px 3px 0"></span>' : '';
        $url    = route($route);
        return "
        <a href=\"{$url}\" style=\"display:flex;align-items:center;gap:11px;padding:9px 14px;border-radius:10px;text-decoration:none;font-size:13.5px;transition:all .12s;position:relative;{$bg};{$color}\">
            {$bar}
            <span style=\"width:30px;display:flex;align-items:center;justify-content:center;flex-shrink:0\">{$icon}</span>
            <span style=\"flex:1\">{$label}</span>
        </a>";
    }
}
@endphp

        {{-- Branch Switcher --}}
        @php
            $navBranches = \App\Models\Branch::where('tenant_id', auth()->user()->tenant_id)
                ->where('status', 'active')
                ->orderBy('is_main', 'desc')
                ->orderBy('name')
                ->get();
        @endphp
        @if($navBranches->count() > 1)
        <div style="padding:6px 10px;margin-bottom:6px">
            <form method="POST" action="" id="branch-switch-form" style="position:relative">
                @csrf
                <select name="branch_id" onchange="document.getElementById('branch-switch-form').submit()" 
                    style="width:100%;padding:8px 12px;padding-right:32px;border:1.5px solid #e4e7ef;border-radius:10px;font-family:'Outfit',sans-serif;font-size:12px;font-weight:500;color:#02182F;background:#fafbff;cursor:pointer;outline:none;appearance:none;
                    background-image:url('data:image/svg+xml,%3Csvg width=%2710%27 height=%277%27 fill=%27none%27 stroke=%27%239ca3af%27 stroke-width=%272%27 viewBox=%270 0 24 24%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 d=%27M6 9l6 6 6-6%27/%3E%3C/svg%3E');
                    background-repeat:no-repeat;background-position:right 10px center">
                    @foreach($navBranches as $navBranch)
                        <option value="{{ $navBranch->id }}" 
                            {{ auth()->user()->branch_id === $navBranch->id ? 'selected' : '' }}>
                            {{ $navBranch->name }} {{ $navBranch->is_main ? ' (Main)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif

        {{-- Main --}}
        <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:4px">Main</div>

        @can('view_dashboard')
            {!! navItem('dashboard', 'Dashboard',
                '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>'
            ) !!}
        @endcan

        @can('access_pos')
            {!! navItem('pos.index', 'POS Terminal',
                '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'pos.*'
            ) !!}
        @endcan

        {{-- Inventory --}}
        @canany(['view_products', 'manage_products', 'manage_stock', 'manage_suppliers'])
            <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">Inventory</div>

            @can('view_products')
                {!! navItem('products.index', 'Products',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                    'products.*'
                ) !!}
            @endcan

            @can('manage_categories')
                {!! navItem('categories.index', 'Categories',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>',
                    'categories.*'
                ) !!}
            @endcan

            @can('manage_stock')
                {!! navItem('stock.index', 'Stock Management',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                    'stock.*'
                ) !!}
            @endcan

            @can('manage_suppliers')
                {!! navItem('suppliers.index', 'Suppliers & POs',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
                    'suppliers.*'
                ) !!}
            @endcan
        @endcanany

        {{-- People --}}
        @canany(['view_customers', 'manage_staff'])
            <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">People</div>

            @can('view_customers')
                {!! navItem('customers.index', 'Customers',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                    'customers.*'
                ) !!}
            @endcan

            @can('manage_staff')
                {!! navItem('staff.index', 'Staff',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                    'staff.*'
                ) !!}
            @endcan
        @endcanany

        {{-- Finance & Reports --}}
        @canany(['view_reports', 'view_profit_loss', 'view_stock_valuation', 'view_cashier_performance', 'manage_expenses', 'process_returns'])
            <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">Finance</div>

            @can('view_reports')
                {!! navItem('reports.sales', 'Sales Report',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                ) !!}
            @endcan

            @can('view_profit_loss')
                {!! navItem('reports.profit-loss', 'Profit & Loss',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                ) !!}
            @endcan

            @can('view_stock_valuation')
                {!! navItem('reports.stock-valuation', 'Stock Valuation',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>'
                ) !!}
            @endcan

            @can('view_cashier_performance')
                {!! navItem('reports.cashier-performance', 'Performance',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>'
                ) !!}
            @endcan

            @can('manage_expenses')
                {!! navItem('expenses.index', 'Expenses',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                    'expenses.*'
                ) !!}
            @endcan

            @can('process_returns')
                {!! navItem('returns.index', 'Returns & Refunds',
                    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>',
                    'returns.*'
                ) !!}
            @endcan

            @can('view_reports')
              {!! navItem('zreports.index', 'Z-Report (End of Day)',
              '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
              'zreports.*'
              ) !!}
            @endcan
        @endcanany

        {{-- System --}}
        @can('manage_settings')
            <div style="font-size:10px;font-weight:700;color:#c4c9d6;text-transform:uppercase;letter-spacing:.08em;padding:6px 14px 4px;margin-top:8px">System</div>

            {!! navItem('settings.index', 'Settings',
                '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>',
                'settings.*'
            ) !!}

            {!! navItem('branches.index', 'Branches',
                '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'branches.*'
            ) !!}
        @endcan
    </div>

    {{-- Footer / User --}}
    <div style="padding:14px 16px 16px;border-top:1px solid #f1f3f8;margin-top:auto">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
            <div style="width:36px;height:36px;background:#03A737;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#FFFEFE;font-weight:700;font-size:13px;flex-shrink:0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:#02182F;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="
                display:flex;align-items:center;gap:8px;width:100%;padding:9px 12px;
                border-radius:10px;background:transparent;border:1.5px solid #e4e7ef;
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

<script>
// Branch switcher - set the form action dynamically
(function() {
    var form = document.getElementById('branch-switch-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var branchId = this.querySelector('select').value;
            this.action = '/branches/' + branchId + '/switch';
            this.submit();
        });
    }
})();
</script>