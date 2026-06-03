<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Expense Tracker
                </h1>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .filter-bar {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: flex-end;
            gap: 14px;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .filter-input {
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
        }
        .filter-input:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .btn-filter {
            padding: 9px 18px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
        }
        .btn-filter:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
        }
        
        .btn-reset {
            padding: 9px 18px;
            background: transparent;
            color: #6b7280;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-reset:hover {
            border-color: #1a56db;
            color: #1a56db;
            background: #eff4ff;
        }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }
        @media (max-width: 768px) { .stat-grid { grid-template-columns: 1fr; } }
        
        .stat-card {
            border-radius: 14px;
            padding: 20px 22px 18px;
            border: 1px solid transparent;
        }
        .stat-card-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 8px;
        }
        .stat-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
        }
        
        .card-red    { background: #fff1f2; border-color: #fecdd3; }
        .card-red    .stat-card-label  { color: #be123c; }
        .card-red    .stat-card-value  { color: #881337; }
        .card-red    .stat-card-sub    { color: #e11d48; }
        
        .card-green  { background: #f0fdf4; border-color: #bbf7d0; }
        .card-green  .stat-card-label  { color: #15803d; }
        .card-green  .stat-card-value  { color: #14532d; }
        .card-green  .stat-card-sub    { color: #22c55e; }
        
        .card-blue   { background: #eff4ff; border-color: #c7d7fb; }
        .card-blue   .stat-card-label  { color: #3b5fd6; }
        .card-blue   .stat-card-value  { color: #1a3fad; }
        .card-blue   .stat-card-sub    { color: #6b85d6; }
        
        .layout-3-2 {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 16px;
            align-items: start;
        }
        @media (max-width: 1024px) { .layout-3-2 { grid-template-columns: 1fr; } }
        
        .form-panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 14px;
        }
        .form-panel:last-child { margin-bottom: 0; }
        
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
            margin-bottom: 12px;
        }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
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
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: #1a56db;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            resize: vertical;
            min-height: 56px;
        }
        .form-textarea:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .color-input {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1.5px solid #e4e7ef;
            cursor: pointer;
            padding: 2px;
        }
        
        .btn-primary {
            width: 100%;
            padding: 10px 16px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
        }
        .btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-dark {
            width: 100%;
            padding: 10px 16px;
            background: #374151;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-dark:hover {
            background: #1f2937;
        }
        
        .panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        .panel-padded { padding: 22px; }
        
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #f1f3f8;
        }
        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }
        .panel-meta {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #9ca3af;
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
            padding: 12px 16px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .data-table td {
            padding: 14px 16px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        
        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            color: white;
        }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-danger { color: #dc2626; }
        
        .action-link-delete {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.15s;
        }
        .action-link-delete:hover {
            color: #dc2626;
            background: #fef2f2;
        }
        
        .progress-bar {
            height: 6px;
            background: #f1f3f8;
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.4s ease;
        }
        
        .category-breakdown-row {
            margin-bottom: 10px;
        }
        .category-breakdown-row:last-child { margin-bottom: 0; }
        
        .color-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            flex-shrink: 0;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
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

        {{-- Date Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('expenses.index') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
                <div class="filter-group">
                    <span class="filter-label">From</span>
                    <input type="date" name="from" value="{{ $from }}" class="filter-input" />
                </div>
                <div class="filter-group">
                    <span class="filter-label">To</span>
                    <input type="date" name="to" value="{{ $to }}" class="filter-input" />
                </div>
                <button type="submit" class="btn-filter">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Apply
                </button>
                <a href="{{ route('expenses.index') }}" class="btn-reset">Reset</a>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="stat-grid">
            <div class="stat-card card-red">
                <div class="stat-card-label">Total Expenses</div>
                <div class="stat-card-value">KES {{ number_format($summary->total_expenses ?? 0, 2) }}</div>
                <div class="stat-card-sub">{{ $summary->total_count ?? 0 }} records</div>
            </div>
            <div class="stat-card card-green">
                <div class="stat-card-label">Total Revenue</div>
                <div class="stat-card-value">KES {{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-card-sub">Same period</div>
            </div>
            <div class="stat-card card-blue">
                @php $net = $totalRevenue - ($summary->total_expenses ?? 0); @endphp
                <div class="stat-card-label">Net Profit</div>
                <div class="stat-card-value">KES {{ number_format($net, 2) }}</div>
                <div class="stat-card-sub">Revenue minus expenses</div>
            </div>
        </div>

        {{-- Main Layout --}}
        <div class="layout-3-2">

            {{-- LEFT: Forms + Breakdown --}}
            <div>

                {{-- Add Expense Form --}}
                <div class="form-panel">
                    <div class="section-title">Record Expense</div>
                    <form method="POST" action="{{ route('expenses.store') }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="exp-title">Title *</label>
                            <input type="text" name="title" id="exp-title" required
                                class="form-input" placeholder="e.g. Electricity bill" />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="exp-amount">Amount (KES) *</label>
                            <input type="number" name="amount" id="exp-amount" step="0.01" min="0" required
                                class="form-input" placeholder="0.00" />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="exp-date">Date *</label>
                            <input type="date" name="expense_date" id="exp-date" required
                                value="{{ now()->toDateString() }}" class="form-input" />
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="exp-category">Category</label>
                            <select name="expense_category_id" id="exp-category" class="form-select">
                                <option value="">No category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="exp-method">Payment Method</label>
                            <select name="payment_method" id="exp-method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile">M-Pesa</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="exp-ref">Reference (optional)</label>
                            <input type="text" name="reference" id="exp-ref"
                                class="form-input" placeholder="Receipt or invoice number" />
                        </div>

                        <div class="form-group" style="margin-bottom:16px">
                            <label class="form-label" for="exp-notes">Notes (optional)</label>
                            <textarea name="notes" id="exp-notes" rows="2" class="form-textarea"
                                placeholder="Any additional details"></textarea>
                        </div>

                        <button type="submit" class="btn-primary">Save Expense</button>
                    </form>
                </div>

                {{-- Add Category Form --}}
                <div class="form-panel">
                    <div class="section-title">Add Category</div>
                    <form method="POST" action="{{ route('expense-categories.store') }}">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="cat-name">Category Name *</label>
                            <input type="text" name="name" id="cat-name" required
                                class="form-input" placeholder="e.g. Utilities, Rent, Salaries" />
                        </div>

                        <div class="form-group" style="margin-bottom:16px">
                            <label class="form-label">Colour</label>
                            <div style="display:flex;align-items:center;gap:10px">
                                <input type="color" name="color" value="#6366f1" class="color-input" />
                                <span style="font-size:12px;color:#9ca3af;font-family:'Outfit',sans-serif">Pick a colour</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-dark">Save Category</button>
                    </form>
                </div>

                {{-- By Category Breakdown --}}
                @if($byCategory->count() > 0)
                    <div class="form-panel">
                        <div class="section-title">By Category</div>
                        @foreach($byCategory as $cat)
                            @php
                                $total = $summary->total_expenses ?? 1;
                                $pct   = $total > 0 ? round(($cat->total / $total) * 100) : 0;
                                $color = $cat->category->color ?? '#6366f1';
                            @endphp
                            <div class="category-breakdown-row">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                    <div style="display:flex;align-items:center;gap:6px">
                                        <span class="color-dot" style="background:{{ $color }}"></span>
                                        <span style="font-size:12px;color:#374151;font-weight:500;font-family:'Outfit',sans-serif">
                                            {{ $cat->category->name ?? 'Uncategorised' }}
                                        </span>
                                    </div>
                                    <span class="mono" style="font-size:12px;font-weight:600;color:#111827">
                                        KES {{ number_format($cat->total, 2) }}
                                        <span style="color:#9ca3af;font-weight:400;margin-left:4px">({{ $pct }}%)</span>
                                    </span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT: Expense List --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">All Expenses</span>
                    <span class="panel-meta">{{ $expenses->total() }} records</span>
                </div>
                @if($expenses->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Method</th>
                                <th style="text-align:right">Amount</th>
                                <th style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td style="font-size:12px;color:#6b7280">
                                        {{ $expense->expense_date->format('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="fw6" style="color:#111827">{{ $expense->title }}</div>
                                        @if($expense->notes)
                                            <div style="font-size:11px;color:#9ca3af;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" 
                                                 title="{{ $expense->notes }}">
                                                {{ $expense->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($expense->category)
                                            <span class="category-badge" style="background:{{ $expense->category->color }}">
                                                {{ $expense->category->name }}
                                            </span>
                                        @else
                                            <span style="font-size:11px;color:#9ca3af">—</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#6b7280;text-transform:capitalize">
                                        {{ $expense->payment_method }}
                                    </td>
                                    <td class="mono fw7 text-danger" style="text-align:right;font-size:13px">
                                        KES {{ number_format($expense->amount, 2) }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                              onsubmit="return confirm('Delete this expense?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link-delete" title="Delete">×</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($expenses->hasPages())
                        <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                            {{ $expenses->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">No expenses recorded yet. Use the form to add one.</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>