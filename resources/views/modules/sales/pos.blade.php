<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS — {{ auth()->user()->tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:       #1a56db;   /* solid blue — brand colour */
            --brand-dark:  #1348c4;
            --brand-light: #eff4ff;
            --brand-muted: #dbeafe;

            --bg:          #f8f9fc;
            --surface:     #ffffff;
            --surface-2:   #f1f3f8;
            --border:      #e4e7ef;
            --border-2:    #d0d5e8;

            --text-1:      #111827;
            --text-2:      #4b5563;
            --text-3:      #9ca3af;

            --danger:      #dc2626;
            --success:     #16a34a;
            --warning:     #d97706;

            --radius-sm:   8px;
            --radius-md:   12px;
            --radius-lg:   16px;
            --radius-xl:   20px;

            --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:   0 4px 12px rgba(0,0,0,.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text-1);
            height: 100vh;
            overflow: hidden;
        }

        /* ══════════════════════════════════════
           LAYOUT
        ══════════════════════════════════════ */
        #pos-app { display: flex; height: 100vh; }

        /* Left panel */
        .left-panel { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: var(--bg); }

        /* Right panel */
        .right-panel {
            width: 390px; display: flex; flex-direction: column;
            background: var(--surface); border-left: 1px solid var(--border);
            box-shadow: -4px 0 20px rgba(0,0,0,.04);
        }

        /* ══════════════════════════════════════
           TOP BAR
        ══════════════════════════════════════ */
        .top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; height: 60px;
            background: var(--surface); border-bottom: 1px solid var(--border);
        }
        .top-bar-left { display: flex; align-items: center; gap: 16px; }

        .back-btn {
            display: flex; align-items: center; gap: 6px;
            color: var(--text-3); font-size: 13px; text-decoration: none;
            padding: 6px 10px; border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            transition: all .15s;
        }
        .back-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }

        .brand-info { display: flex; flex-direction: column; }
        .brand-name { font-size: 15px; font-weight: 700; color: var(--text-1); line-height: 1; }
        .branch-name { font-size: 11px; color: var(--text-3); margin-top: 2px; }

        .divider-v { width: 1px; height: 24px; background: var(--border); }

        .datetime { font-size: 12px; color: var(--text-3); font-family: 'JetBrains Mono', monospace; }

        /* Search */
        .search-wrap { position: relative; }
        .search-input {
            background: var(--surface-2); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 9px 14px 9px 38px;
            border-radius: var(--radius-md); font-size: 13px; width: 280px;
            outline: none; font-family: 'Outfit', sans-serif; transition: all .2s;
        }
        .search-input::placeholder { color: var(--text-3); }
        .search-input:focus { border-color: var(--brand); background: var(--surface); box-shadow: 0 0 0 3px var(--brand-muted); }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 14px; }

        /* User chip */
        .user-chip {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 12px; border-radius: 99px;
            background: var(--surface-2); border: 1px solid var(--border);
        }
        .user-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--brand); color: white;
            font-size: 10px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .user-name { font-size: 12px; font-weight: 500; color: var(--text-2); }

        /* ══════════════════════════════════════
           CATEGORY BAR
        ══════════════════════════════════════ */
        .cat-bar {
            display: flex; gap: 8px; padding: 12px 24px;
            background: var(--surface); border-bottom: 1px solid var(--border);
            overflow-x: auto; scrollbar-width: none;
        }
        .cat-bar::-webkit-scrollbar { display: none; }
        .cat-btn {
            padding: 6px 16px; border-radius: 99px; font-size: 12px; font-weight: 500;
            white-space: nowrap; border: 1.5px solid var(--border);
            background: transparent; color: var(--text-2); cursor: pointer;
            transition: all .15s; font-family: 'Outfit', sans-serif;
        }
        .cat-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .cat-btn.active { background: var(--brand); border-color: var(--brand); color: white; font-weight: 600; }

        /* ══════════════════════════════════════
           PRODUCT GRID
        ══════════════════════════════════════ */
        .product-grid-wrap { flex: 1; overflow-y: auto; padding: 20px 24px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(148px, 1fr)); gap: 12px; }

        .product-card {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: var(--radius-lg); padding: 16px; cursor: pointer;
            transition: all .2s; position: relative; overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .product-card:hover {
            border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-muted), var(--shadow-md);
            transform: translateY(-1px);
        }
        .product-card:active { transform: scale(0.97); }

        .product-icon {
            width: 100%; aspect-ratio: 1.2; border-radius: var(--radius-md);
            background: var(--brand-light); display: flex; align-items: center;
            justify-content: center; font-size: 30px; margin-bottom: 12px;
        }
        .product-name {
            font-size: 12px; font-weight: 600; color: var(--text-1);
            line-height: 1.35; margin-bottom: 6px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .product-price {
            font-size: 15px; font-weight: 700; color: var(--brand);
            font-family: 'JetBrains Mono', monospace;
        }
        .product-stock { font-size: 10px; margin-top: 4px; font-weight: 500; }
        .stock-ok { color: var(--text-3); }
        .stock-low { color: var(--warning); }
        .stock-out-badge {
            position: absolute; top: 8px; right: 8px;
            background: var(--danger); color: white;
            font-size: 9px; font-weight: 700; padding: 2px 6px;
            border-radius: 99px; letter-spacing: .04em;
        }
        .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }

        .no-results { text-align: center; color: var(--text-3); padding: 60px; font-size: 14px; }
        .no-results-icon { font-size: 40px; margin-bottom: 12px; }

        /* ══════════════════════════════════════
           CART — HEADER
        ══════════════════════════════════════ */
        .cart-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px; border-bottom: 1px solid var(--border);
        }
        .cart-title-wrap { display: flex; align-items: center; gap: 10px; }
        .cart-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
        .cart-badge {
            background: var(--brand); color: white;
            font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 99px;
        }
        .clear-btn {
            font-size: 12px; color: var(--text-3); background: none; border: none;
            cursor: pointer; font-family: 'Outfit', sans-serif; padding: 5px 10px;
            border-radius: var(--radius-sm); border: 1px solid var(--border);
            transition: all .15s;
        }
        .clear-btn:hover { color: var(--danger); border-color: var(--danger); background: #fef2f2; }

        /* Customer */
        .customer-wrap { padding: 12px 20px; border-bottom: 1px solid var(--border); }
        .section-label { font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
        .customer-select {
            width: 100%; background: var(--surface-2); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 8px 12px; border-radius: var(--radius-md);
            font-size: 13px; outline: none; font-family: 'Outfit', sans-serif;
            transition: all .15s; cursor: pointer;
        }
        .customer-select:focus { border-color: var(--brand); background: var(--surface); }

        /* Cart items */
        .cart-items-wrap { flex: 1; overflow-y: auto; padding: 8px 20px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        .cart-empty { text-align: center; padding: 40px 20px; }
        .cart-empty-icon { font-size: 48px; margin-bottom: 12px; opacity: .25; }
        .cart-empty-text { font-size: 13px; color: var(--text-3); }

        .cart-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 0; border-bottom: 1px solid var(--border);
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-num {
            width: 22px; height: 22px; border-radius: 6px;
            background: var(--brand-light); color: var(--brand);
            font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name { font-size: 13px; font-weight: 500; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cart-item-price { font-size: 12px; color: var(--brand); font-family: 'JetBrains Mono', monospace; margin-top: 2px; }

        .qty-control { display: flex; align-items: center; gap: 6px; }
        .qty-btn {
            width: 28px; height: 28px; border-radius: var(--radius-sm);
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text-2); font-size: 15px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s; font-family: 'Outfit', sans-serif; font-weight: 500;
        }
        .qty-btn:hover.plus { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .qty-btn:hover.minus { border-color: var(--danger); color: var(--danger); background: #fef2f2; }
        .qty-num { font-size: 14px; font-weight: 700; color: var(--text-1); width: 28px; text-align: center; font-family: 'JetBrains Mono', monospace; }

        .remove-btn {
            background: none; border: none; color: var(--text-3); cursor: pointer;
            font-size: 15px; width: 26px; height: 26px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        .remove-btn:hover { color: var(--danger); background: #fef2f2; }

        /* ══════════════════════════════════════
           TOTALS
        ══════════════════════════════════════ */
        .totals-wrap { padding: 14px 20px; border-top: 1px solid var(--border); background: var(--surface-2); }
        .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; }
        .total-label { font-size: 13px; color: var(--text-2); }
        .total-val { font-size: 13px; color: var(--text-1); font-family: 'JetBrains Mono', monospace; }
        .total-note { font-size: 11px; color: var(--text-3); }

        .discount-group { display: flex; align-items: center; gap: 6px; }
        .discount-prefix { font-size: 12px; color: var(--text-3); }
        .discount-input {
            width: 80px; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 4px 8px; border-radius: var(--radius-sm);
            font-size: 13px; font-family: 'JetBrains Mono', monospace; outline: none;
            text-align: right; transition: border-color .15s;
        }
        .discount-input:focus { border-color: var(--brand); }

        .grand-row {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 10px; margin-top: 6px; border-top: 2px solid var(--border-2);
        }
        .grand-label { font-size: 16px; font-weight: 700; color: var(--text-1); }
        .grand-val { font-size: 22px; font-weight: 700; color: var(--brand); font-family: 'JetBrains Mono', monospace; }

        /* ══════════════════════════════════════
           PAYMENT
        ══════════════════════════════════════ */
        .payment-wrap { padding: 14px 20px 18px; border-top: 1px solid var(--border); }

        /* Split toggle */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .toggle-label { font-size: 12px; color: var(--text-2); font-weight: 500; }
        .toggle-switch { position: relative; display: inline-block; width: 42px; height: 24px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background: var(--border-2); border-radius: 99px; transition: .2s;
        }
        .toggle-slider::before {
            content: ''; position: absolute; width: 18px; height: 18px;
            left: 3px; top: 3px; background: white; border-radius: 50%;
            transition: .2s; box-shadow: var(--shadow-sm);
        }
        input:checked + .toggle-slider { background: var(--brand); }
        input:checked + .toggle-slider::before { transform: translateX(18px); }

        /* Payment method buttons */
        .pay-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
        .pay-btn {
            padding: 10px 4px; border-radius: var(--radius-md); border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text-2); font-size: 12px; font-weight: 500;
            cursor: pointer; transition: all .15s; font-family: 'Outfit', sans-serif;
            display: flex; flex-direction: column; align-items: center; gap: 5px;
        }
        .pay-btn .p-icon { font-size: 18px; }
        .pay-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .pay-btn.active { background: var(--brand); border-color: var(--brand); color: white; }

        /* Amount received */
        .amount-section { margin-bottom: 10px; }
        .amount-label { font-size: 11px; color: var(--text-3); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
        .amount-input {
            width: 100%; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 11px 14px; border-radius: var(--radius-md);
            font-size: 20px; font-weight: 700; font-family: 'JetBrains Mono', monospace;
            outline: none; transition: all .2s;
        }
        .amount-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-muted); }
        .amount-input::placeholder { color: var(--border-2); font-size: 15px; font-weight: 400; }

        /* Change */
        .change-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .change-label { font-size: 13px; color: var(--text-2); }
        .change-val { font-size: 15px; font-weight: 700; font-family: 'JetBrains Mono', monospace; transition: color .15s; }
        .change-ok { color: var(--success); }
        .change-short { color: var(--danger); }

        /* Split inputs */
        .split-box { background: var(--surface-2); border: 1.5px solid var(--border); border-radius: var(--radius-md); padding: 12px; margin-bottom: 12px; }
        .split-row-inp { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .split-row-inp:last-child { margin-bottom: 0; }
        .split-icon { font-size: 16px; width: 22px; text-align: center; }
        .split-lbl { font-size: 12px; color: var(--text-2); font-weight: 500; width: 48px; }
        .split-inp {
            flex: 1; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 7px 10px; border-radius: var(--radius-sm);
            font-size: 13px; font-family: 'JetBrains Mono', monospace; outline: none;
            transition: border-color .15s;
        }
        .split-inp:focus { border-color: var(--brand); }
        .split-footer { display: flex; justify-content: space-between; padding-top: 10px; border-top: 1px solid var(--border); margin-top: 10px; }
        .split-foot-item { font-size: 12px; }
        .split-foot-lbl { color: var(--text-3); }
        .split-foot-val { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: var(--text-1); }

        /* Complete button */
        .complete-btn {
            width: 100%; padding: 14px; border-radius: var(--radius-md); border: none;
            background: var(--brand); color: white;
            font-size: 15px; font-weight: 700; font-family: 'Outfit', sans-serif;
            cursor: pointer; letter-spacing: .01em; transition: all .2s;
            box-shadow: 0 4px 12px rgba(26, 86, 219, .3);
        }
        .complete-btn:hover { background: var(--brand-dark); box-shadow: 0 6px 20px rgba(26, 86, 219, .4); transform: translateY(-1px); }
        .complete-btn:active { transform: translateY(0); box-shadow: none; }
        .complete-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* ══════════════════════════════════════
           RECEIPT MODAL
        ══════════════════════════════════════ */
        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 100;
            background: rgba(0,0,0,.35); backdrop-filter: blur(3px);
            align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-card {
            background: var(--surface); border-radius: var(--radius-xl);
            padding: 32px 28px; width: 360px; box-shadow: 0 24px 60px rgba(0,0,0,.18);
            border: 1px solid var(--border);
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes modalIn { from { opacity:0; transform:scale(.88) translateY(12px); } to { opacity:1; transform:none; } }

        .modal-icon-wrap {
            width: 64px; height: 64px; border-radius: 50%; border: 2px solid #bbf7d0;
            background: #f0fdf4; display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin: 0 auto 18px;
        }
        .modal-title { text-align: center; font-size: 20px; font-weight: 700; color: var(--text-1); margin-bottom: 4px; }
        .modal-invoice { text-align: center; font-size: 12px; color: var(--text-3); font-family: 'JetBrains Mono', monospace; margin-bottom: 20px; }

        .modal-summary { background: var(--surface-2); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px; border: 1px solid var(--border); }
        .modal-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; }
        .modal-row + .modal-row { border-top: 1px solid var(--border); }
        .modal-lbl { font-size: 13px; color: var(--text-2); }
        .modal-val { font-size: 13px; font-weight: 600; font-family: 'JetBrains Mono', monospace; }
        .modal-total { font-size: 18px; font-weight: 700; color: var(--brand); }
        .modal-change { color: var(--success); }

        .modal-btns { display: flex; gap: 10px; }
        .modal-btn-outline {
            flex: 1; padding: 12px; border-radius: var(--radius-md);
            border: 1.5px solid var(--border); background: transparent;
            color: var(--text-2); font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s;
        }
        .modal-btn-outline:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .modal-btn-solid {
            flex: 1; padding: 12px; border-radius: var(--radius-md); border: none;
            background: var(--brand); color: white;
            font-size: 13px; font-weight: 700;
            cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s;
        }
        .modal-btn-solid:hover { background: var(--brand-dark); }

        /* Toast */
        .toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            background: var(--text-1); color: white; padding: 10px 20px;
            border-radius: var(--radius-md); font-size: 13px; font-weight: 500;
            z-index: 200; box-shadow: var(--shadow-md); white-space: nowrap;
            animation: toastIn .2s ease;
        }
        .toast.error { background: var(--danger); }
        @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(8px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }

        /* Scrollbars */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 99px; }
    </style>
</head>
<body>

<div id="pos-app">

    {{-- ═══════════════════════════════════
         LEFT PANEL — Products
    ═══════════════════════════════════ --}}
    <div class="left-panel">

        {{-- Top bar --}}
        <div class="top-bar">
            <div class="top-bar-left">
                <a href="{{ route('dashboard') }}" class="back-btn">← Back</a>
                <div class="divider-v"></div>
                <div class="brand-info">
                    <span class="brand-name">{{ auth()->user()->tenant->name }}</span>
                    @if(auth()->user()->branch)
                        <span class="branch-name">{{ auth()->user()->branch->name }}</span>
                    @endif
                </div>
                <div class="divider-v"></div>
                <span class="datetime" id="live-clock"></span>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="search-input" class="search-input"
                        placeholder="Search products or scan barcode…" />
                </div>
                <div class="user-chip">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <span class="user-name">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        {{-- Category bar --}}
        <div class="cat-bar">
            <button class="cat-btn active" data-cat="all" onclick="filterCategory('all', this)">All Items</button>
            @foreach($categories as $cat)
                <button class="cat-btn" data-cat="{{ $cat->id }}"
                    onclick="filterCategory('{{ $cat->id }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Product grid --}}
        <div class="product-grid-wrap">
            <div class="product-grid" id="product-grid">
                @php
                    $icons = ['🥤','🍎','📦','🛍️','🧴','🧃','🍫','🥛','🌽','🧹','💊','🧂','🥩','🍞','🧀','🥚','🍵','🫙'];
                @endphp
                @foreach($products as $product)
                    @php $icon = $icons[$product->id % count($icons)]; @endphp
                    <div class="product-card {{ ($product->track_stock && $product->stock_qty <= 0) ? 'out-of-stock' : '' }}"
                         data-id="{{ $product->id }}"
                         data-name="{{ $product->name }}"
                         data-price="{{ $product->price }}"
                         data-stock="{{ $product->stock_qty }}"
                         data-track="{{ $product->track_stock ? '1' : '0' }}"
                         data-cat="{{ $product->category_id ?? 'none' }}"
                         onclick="addToCart(this)">

                        @if($product->track_stock && $product->stock_qty <= 0)
                            <span class="stock-out-badge">OUT OF STOCK</span>
                        @endif

                        <div class="product-icon">{{ $icon }}</div>
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">KES {{ number_format($product->price, 2) }}</div>

                        @if($product->track_stock)
                            @if($product->stock_qty > 0 && $product->isLowStock())
                                <div class="product-stock stock-low">⚠ Only {{ $product->stock_qty }} left</div>
                            @elseif($product->stock_qty > 0)
                                <div class="product-stock stock-ok">{{ $product->stock_qty }} in stock</div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <div id="no-results" class="no-results" style="display:none">
                <div class="no-results-icon">🔍</div>
                No products found
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         RIGHT PANEL — Cart
    ═══════════════════════════════════ --}}
    <div class="right-panel">

        {{-- Cart header --}}
        <div class="cart-header">
            <div class="cart-title-wrap">
                <span class="cart-title">Current Sale</span>
                <span class="cart-badge" id="cart-count">0</span>
            </div>
            <button class="clear-btn" onclick="clearCart()">Clear all</button>
        </div>

        {{-- Customer --}}
        <div class="customer-wrap">
            <div class="section-label">Customer</div>
            <select id="customer-select" class="customer-select">
                <option value="">👤 Walk-in Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} · {{ $customer->phone }}</option>
                @endforeach
            </select>
        </div>

        {{-- Cart items --}}
        <div class="cart-items-wrap" id="cart-items">
            <div class="cart-empty" id="empty-cart">
                <div class="cart-empty-icon">🛒</div>
                <div class="cart-empty-text">Tap a product to add it to the sale</div>
            </div>
        </div>

        {{-- Totals --}}
        <div class="totals-wrap">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-val" id="subtotal-display">KES 0.00</span>
            </div>
            <div class="total-row">
                <span class="total-label" style="font-size:12px;color:var(--text-3)">Tax</span>
                <span class="total-note">Included in price</span>
            </div>
            <div class="total-row">
                <span class="total-label">Discount</span>
                <div class="discount-group">
                    <span class="discount-prefix">KES</span>
                    <input type="number" id="discount-input" class="discount-input"
                        value="0" min="0" placeholder="0" oninput="updateTotals()" />
                </div>
            </div>
            <div class="grand-row">
                <span class="grand-label">Total</span>
                <span class="grand-val" id="total-display">KES 0.00</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="payment-wrap">

            {{-- Split toggle --}}
            <div class="toggle-row">
                <span class="toggle-label">Split Payment</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="split-toggle" onchange="toggleSplit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            {{-- Single payment --}}
            <div id="single-payment">
                <div class="pay-methods">
                    <button class="pay-btn active" data-method="cash" onclick="setPayment('cash', this)">
                        <span class="p-icon">💵</span>Cash
                    </button>
                    <button class="pay-btn" data-method="card" onclick="setPayment('card', this)">
                        <span class="p-icon">💳</span>Card
                    </button>
                    <button class="pay-btn" data-method="mobile" onclick="setPayment('mobile', this)">
                        <span class="p-icon">📱</span>M-Pesa
                    </button>
                </div>
                <div class="amount-section">
                    <div class="amount-label">Amount Received</div>
                    <input type="number" id="amount-paid" class="amount-input"
                        placeholder="0.00" min="0" step="0.01" oninput="updateChange()" />
                </div>
                <div class="change-row">
                    <span class="change-label">Change Due</span>
                    <span class="change-val change-ok" id="change-display">KES 0.00</span>
                </div>
            </div>

            {{-- Split payment --}}
            <div id="split-payment" style="display:none">
                <div class="split-box">
                    <div class="split-row-inp">
                        <span class="split-icon">💵</span>
                        <span class="split-lbl">Cash</span>
                        <input type="number" id="split-cash" class="split-inp"
                            placeholder="0.00" min="0" step="0.01" oninput="updateSplitChange()" />
                    </div>
                    <div class="split-row-inp">
                        <span class="split-icon">💳</span>
                        <span class="split-lbl">Card</span>
                        <input type="number" id="split-card" class="split-inp"
                            placeholder="0.00" min="0" step="0.01" oninput="updateSplitChange()" />
                    </div>
                    <div class="split-row-inp">
                        <span class="split-icon">📱</span>
                        <span class="split-lbl">M-Pesa</span>
                        <input type="number" id="split-mobile" class="split-inp"
                            placeholder="0.00" min="0" step="0.01" oninput="updateSplitChange()" />
                    </div>
                    <div class="split-footer">
                        <div class="split-foot-item">
                            <span class="split-foot-lbl">Paid: </span>
                            <span class="split-foot-val" id="split-paid-display">KES 0.00</span>
                        </div>
                        <div class="split-foot-item">
                            <span class="split-foot-lbl">Remaining: </span>
                            <span class="split-foot-val" id="split-remaining" style="color:var(--danger)">KES 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <button class="complete-btn" id="complete-btn" onclick="processSale()">
                Complete Sale
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════
     RECEIPT MODAL
═══════════════════════════════════ --}}
<div class="modal-overlay" id="receipt-modal">
    <div class="modal-card">
        <div class="modal-icon-wrap">✅</div>
        <div class="modal-title">Sale Complete!</div>
        <div class="modal-invoice" id="receipt-invoice"></div>
        <div class="modal-summary">
            <div class="modal-row">
                <span class="modal-lbl">Total</span>
                <span class="modal-val modal-total" id="receipt-total"></span>
            </div>
            <div class="modal-row">
                <span class="modal-lbl">Payment</span>
                <span class="modal-val" id="receipt-payment"></span>
            </div>
            <div class="modal-row">
                <span class="modal-lbl">Change</span>
                <span class="modal-val modal-change" id="receipt-change"></span>
            </div>
        </div>
        <div class="modal-btns">
            <button class="modal-btn-outline" onclick="printReceipt()">🖨 Print Receipt</button>
            <button class="modal-btn-solid" onclick="newSale()">New Sale →</button>
        </div>
    </div>
</div>

<script>
/* ─── State ─── */
let cart          = [];
let paymentMethod = 'cash';
let isSplit       = false;
let currentSaleId = null;

/* ─── Live clock ─── */
function updateClock() {
    const now = new Date();
    document.getElementById('live-clock').textContent =
        now.toLocaleTimeString('en-KE', { hour: '2-digit', minute: '2-digit' }) + ' · ' +
        now.toLocaleDateString('en-KE', { weekday: 'short', day: 'numeric', month: 'short' });
}
updateClock();
setInterval(updateClock, 1000);

/* ─── Add to cart ─── */
function addToCart(el) {
    if (el.classList.contains('out-of-stock')) return;

    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    const stock = parseInt(el.dataset.stock);
    const track = el.dataset.track === '1';

    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (track && existing.qty >= stock) { toast('Not enough stock available', 'error'); return; }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, stock, track });
    }

    /* Card tap feedback */
    el.style.transform = 'scale(0.95)';
    setTimeout(() => el.style.transform = '', 120);

    renderCart();
}

/* ─── Render cart ─── */
function renderCart() {
    const wrap  = document.getElementById('cart-items');
    const empty = document.getElementById('empty-cart');
    const count = document.getElementById('cart-count');

    const totalItems = cart.reduce((s, i) => s + i.qty, 0);
    count.textContent = totalItems;

    if (cart.length === 0) {
        wrap.innerHTML = '';
        wrap.appendChild(empty);
        empty.style.display = 'block';
        updateTotals();
        return;
    }

    empty.style.display = 'none';
    wrap.innerHTML = cart.map((item, i) => `
        <div class="cart-item">
            <div class="cart-item-num">${i + 1}</div>
            <div class="cart-item-info">
                <div class="cart-item-name" title="${item.name}">${item.name}</div>
                <div class="cart-item-price">KES ${(item.price * item.qty).toFixed(2)}</div>
            </div>
            <div class="qty-control">
                <button class="qty-btn minus" data-action="decrease" data-index="${i}">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn plus" data-action="increase" data-index="${i}">+</button>
            </div>
            <button class="remove-btn" data-action="remove" data-index="${i}" title="Remove">✕</button>
        </div>
    `).join('');

    updateTotals();
}

/* ─── Cart event delegation ─── */
document.getElementById('cart-items').addEventListener('click', function(e) {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const action = btn.dataset.action;
    const i      = parseInt(btn.dataset.index);

    if (action === 'increase') {
        if (cart[i].track && cart[i].qty >= cart[i].stock) { toast('Not enough stock', 'error'); return; }
        cart[i].qty++;
        renderCart();
    }
    if (action === 'decrease') {
        cart[i].qty--;
        if (cart[i].qty <= 0) cart.splice(i, 1);
        renderCart();
    }
    if (action === 'remove') {
        cart.splice(i, 1);
        renderCart();
    }
});

function clearCart() {
    if (!cart.length) return;
    if (!confirm('Clear the current sale?')) return;
    cart = [];
    renderCart();
}

/* ─── Totals ─── */
function updateTotals() {
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discount-input').value) || 0;
    const total    = Math.max(0, subtotal - discount);

    document.getElementById('subtotal-display').textContent = 'KES ' + subtotal.toFixed(2);
    document.getElementById('total-display').textContent    = 'KES ' + total.toFixed(2);

    updateChange();
    updateSplitChange();
}

function getTotal() {
    return parseFloat(document.getElementById('total-display').textContent.replace('KES ', '')) || 0;
}

function updateChange() {
    const total  = getTotal();
    const paid   = parseFloat(document.getElementById('amount-paid').value) || 0;
    const change = paid - total;
    const el     = document.getElementById('change-display');
    el.textContent = 'KES ' + Math.max(0, change).toFixed(2);
    el.className   = 'change-val ' + (change >= 0 ? 'change-ok' : 'change-short');
}

function updateSplitChange() {
    const total  = getTotal();
    const cash   = parseFloat(document.getElementById('split-cash').value)   || 0;
    const card   = parseFloat(document.getElementById('split-card').value)   || 0;
    const mobile = parseFloat(document.getElementById('split-mobile').value) || 0;
    const paid   = cash + card + mobile;
    const remain = total - paid;

    document.getElementById('split-paid-display').textContent = 'KES ' + paid.toFixed(2);
    const remEl = document.getElementById('split-remaining');
    remEl.textContent  = 'KES ' + Math.max(0, remain).toFixed(2);
    remEl.style.color  = remain <= 0 ? 'var(--success)' : 'var(--danger)';
}

/* ─── Payment method ─── */
function setPayment(method, btn) {
    paymentMethod = method;
    document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function toggleSplit() {
    isSplit = document.getElementById('split-toggle').checked;
    document.getElementById('single-payment').style.display = isSplit ? 'none' : 'block';
    document.getElementById('split-payment').style.display  = isSplit ? 'block' : 'none';
}

/* ─── Category filter ─── */
function filterCategory(catId, btn) {
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = catId === 'all' || card.dataset.cat == catId ? '' : 'none';
    });
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    checkNoResults();
}

function checkNoResults() {
    const visible = [...document.querySelectorAll('.product-card')].filter(c => c.style.display !== 'none');
    document.getElementById('no-results').style.display = visible.length ? 'none' : 'block';
}

/* ─── Search ─── */
document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
    checkNoResults();
});

/* ─── Toast ─── */
function toast(msg, type = '') {
    const el = document.createElement('div');
    el.className = 'toast ' + type;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2800);
}

/* ─── Process sale ─── */
async function processSale() {
    if (!cart.length) { toast('Cart is empty', 'error'); return; }

    const total = getTotal();
    let payload;

    if (isSplit) {
        const cash   = parseFloat(document.getElementById('split-cash').value)   || 0;
        const card   = parseFloat(document.getElementById('split-card').value)   || 0;
        const mobile = parseFloat(document.getElementById('split-mobile').value) || 0;
        const paid   = cash + card + mobile;

        if (paid < total) {
            toast('Amount paid (KES ' + paid.toFixed(2) + ') is less than total (KES ' + total.toFixed(2) + ')', 'error');
            return;
        }
        payload = {
            items: cart.map(i => ({ id: i.id, qty: i.qty, price: i.price })),
            payment_method: 'split', split_payments: { cash, card, mobile },
            amount_paid: paid,
            discount: parseFloat(document.getElementById('discount-input').value) || 0,
            customer_id: document.getElementById('customer-select').value || null,
        };
    } else {
        const paid = parseFloat(document.getElementById('amount-paid').value) || 0;
        if (!paid || paid < total) {
            toast('Amount received must be at least KES ' + total.toFixed(2), 'error');
            return;
        }
        payload = {
            items: cart.map(i => ({ id: i.id, qty: i.qty, price: i.price })),
            payment_method: paymentMethod, amount_paid: paid,
            discount: parseFloat(document.getElementById('discount-input').value) || 0,
            customer_id: document.getElementById('customer-select').value || null,
        };
    }

    const btn = document.getElementById('complete-btn');
    btn.disabled = true; btn.textContent = 'Processing…';

    try {
        const res  = await fetch('{{ route("pos.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (data.success) {
            currentSaleId = data.sale_id;
            document.getElementById('receipt-invoice').textContent = data.invoice_no;
            document.getElementById('receipt-total').textContent   = 'KES ' + parseFloat(data.total).toFixed(2);
            document.getElementById('receipt-payment').textContent = payload.payment_method;
            document.getElementById('receipt-change').textContent  = 'KES ' + parseFloat(data.change).toFixed(2);
            document.getElementById('receipt-modal').classList.add('show');
        } else {
            toast(data.message || 'Sale failed. Please try again.', 'error');
        }
    } catch (e) {
        toast('Network error. Please try again.', 'error');
    } finally {
        btn.disabled = false; btn.textContent = 'Complete Sale';
    }
}

function printReceipt() {
    if (currentSaleId) window.open('{{ url("pos/receipt") }}/' + currentSaleId, '_blank');
}

function newSale() {
    document.getElementById('receipt-modal').classList.remove('show');
    cart = [];
    ['amount-paid','discount-input','split-cash','split-card','split-mobile'].forEach(id => {
        document.getElementById(id).value = id === 'discount-input' ? '0' : '';
    });
    document.getElementById('customer-select').value = '';
    if (isSplit) { document.getElementById('split-toggle').checked = false; toggleSplit(); }
    renderCart();
}
</script>
</body>
</html>