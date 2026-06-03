<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Categories
                </h1>
            </div>
            <a href="{{ route('categories.create') }}"
               style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s">
                + Add Category
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
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
        
        .category-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .category-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .category-icon svg {
            width: 18px;
            height: 18px;
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
        
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; }
        .text-brand { color: #1a56db; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.edit { color: #1a56db; }
        .action-link.edit:hover { color: #1e40af; text-decoration: underline; }
        .action-link.delete { color: #dc2626; background: none; border: none; cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 600; }
        .action-link.delete:hover { color: #b91c1c; text-decoration: underline; }
        
        .parent-indent {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #9ca3af;
            font-size: 12px;
        }
        .parent-indent svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }
        
        .count-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            border-radius: 99px;
            background: #eff4ff;
            color: #1a56db;
            font-size: 11px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
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

    <div class="dash-wrap" style="display:flex;flex-direction:column;gap:20px;padding:0 0 20px">

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

        {{-- Categories Table --}}
        <div class="panel">
            @if($categories->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th style="width:100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $iconBgColors = ['#eff4ff','#f0fdfa','#f5f3ff','#f0fdf4','#fffbeb','#fef2f2'];
                            $iconColors   = ['#1a56db','#0d9488','#7c3aed','#16a34a','#d97706','#dc2626'];
                        @endphp
                        @foreach($categories as $category)
                            @php
                                $colorIndex = $category->id % count($iconBgColors);
                                $bgColor = $iconBgColors[$colorIndex];
                                $iconColor = $iconColors[$colorIndex];
                            @endphp
                            <tr>
                                <td>
                                    <div class="category-name-cell">
                                        <div class="category-icon" style="background:{{ $bgColor }}">
                                            <svg fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <span class="fw6" style="color:#111827">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="parent-indent">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7l5 5-5 5"/>
                                            </svg>
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span style="color:#9ca3af;font-size:12px">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="count-chip">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $category->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px">
                                        <a href="{{ route('categories.edit', $category) }}" class="action-link edit">Edit</a>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                              onsubmit="return confirm('Delete this category?')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No categories yet</p>
                    <a href="{{ route('categories.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#1a56db;text-decoration:none">
                        Add your first category →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>