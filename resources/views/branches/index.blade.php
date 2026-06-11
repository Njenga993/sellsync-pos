<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">System</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Branches
                </h1>
            </div>
            <a href="{{ route('branches.create') }}"
               style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
               onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
               onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                + Add Branch
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
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
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .badge-green { background: #e6f7eb; color: #028a2e; }
        .badge-blue  { background: #edf3fd; color: #2b5fc4; }
        
        .main-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            background: #e6f7eb;
            color: #028a2e;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.edit { color: #03A737; }
        .action-link.edit:hover { color: #028a2e; text-decoration: underline; }
        .action-link.delete { color: #dc2626; background: none; border: none; cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 600; }
        .action-link.delete:hover { color: #b91c1c; text-decoration: underline; }
        .action-link.switch { color: #3D7BE7; }
        .action-link.switch:hover { color: #2b5fc4; text-decoration: underline; }
        
        .current-branch {
            background: #e6f7eb;
        }
    </style>

    <div style="padding:0 0 20px">

        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:16px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#b91c1c;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:16px">
                <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="panel">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Branch Name</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="width:160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr class="{{ auth()->user()->branch_id === $branch->id ? 'current-branch' : '' }}">
                            <td>
                                <div style="font-weight:600;color:#02182F">{{ $branch->name }}</div>
                                @if(auth()->user()->branch_id === $branch->id)
                                    <div style="font-size:10px;color:#03A737;font-weight:600;margin-top:2px">● Current branch</div>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#6b7280">{{ $branch->city ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $branch->phone ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $branch->address ?? '—' }}
                            </td>
                            <td>
                                @if($branch->is_main)
                                    <span class="main-badge">
                                        <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Main
                                    </span>
                                @else
                                    <span style="font-size:11px;color:#9ca3af">Branch</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $branch->status === 'active' ? 'badge-green' : 'badge-blue' }}">
                                    {{ ucfirst($branch->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    @if(auth()->user()->branch_id !== $branch->id)
                                        <form method="POST" action="{{ route('branches.switch', $branch) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="action-link switch">Switch</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('branches.edit', $branch) }}" class="action-link edit">Edit</a>
                                    @if(!$branch->is_main)
                                        <form method="POST" action="{{ route('branches.destroy', $branch) }}"
                                              onsubmit="return confirm('Delete this branch? Users will be moved to the main branch.')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link delete">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>