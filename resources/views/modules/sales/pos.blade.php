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
            --brand:       #03A737;
            --brand-dark:  #028a2e;
            --brand-light: #e6f7eb;
            --brand-muted: #ccf4d6;

            --blue:        #3D7BE7;
            --blue-light:  #edf3fd;
            --black:       #02182F;
            --white:       #FFFEFE;

            --bg:          #f8f9fc;
            --surface:     #FFFEFE;
            --surface-2:   #f1f3f8;
            --border:      #e4e7ef;
            --border-2:    #d0d5e8;

            --text-1:      #02182F;
            --text-2:      #4b5563;
            --text-3:      #9ca3af;

            --danger:      #dc2626;
            --success:     #03A737;
            --warning:     #d97706;

            --radius-sm:   8px;
            --radius-md:   12px;
            --radius-lg:   16px;
            --radius-xl:   20px;

            --shadow-sm:   0 1px 3px rgba(2,24,47,.06), 0 1px 2px rgba(2,24,47,.04);
            --shadow-md:   0 4px 12px rgba(2,24,47,.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text-1);
            height: 100vh;
            overflow: hidden;
        }

        #pos-app { display: flex; height: 100vh; }
        @media(max-width:768px) { #pos-app { flex-direction: column; } }

        .left-panel { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: var(--bg); }
        @media(max-width:768px) { .left-panel { flex: none; height: 55%; } }

        .right-panel {
            width: 390px; display: flex; flex-direction: column;
            background: var(--surface); border-left: 1px solid var(--border);
            box-shadow: -4px 0 20px rgba(2,24,47,.04);
        }
        @media(max-width:768px) {
            .right-panel { width: 100%; height: 45%; border-left: none; border-top: 1px solid var(--border); }
        }

        .top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 16px; height: 56px;
            background: var(--surface); border-bottom: 1px solid var(--border);
            gap: 10px;
        }
        @media(min-width:640px) { .top-bar { padding: 0 24px; height: 60px; } }
        .top-bar-left { display: flex; align-items: center; gap: 10px; }
        @media(min-width:640px) { .top-bar-left { gap: 16px; } }

        .back-btn {
            display: flex; align-items: center; gap: 4px;
            color: var(--text-3); font-size: 12px; text-decoration: none;
            padding: 5px 8px; border-radius: var(--radius-sm);
            border: 1px solid var(--border); white-space: nowrap;
            transition: all .15s;
        }
        @media(min-width:640px) { .back-btn { font-size: 13px; padding: 6px 10px; gap: 6px; } }
        .back-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }

        .brand-info { display: flex; flex-direction: column; }
        .brand-name { font-size: 13px; font-weight: 700; color: var(--text-1); line-height: 1; }
        @media(min-width:640px) { .brand-name { font-size: 15px; } }
        .branch-name { font-size: 10px; color: var(--text-3); margin-top: 2px; }
        @media(min-width:640px) { .branch-name { font-size: 11px; } }

        .divider-v { width: 1px; height: 20px; background: var(--border); }
        @media(max-width:900px) { .divider-v { display: none; } }

        .datetime { font-size: 10px; color: var(--text-3); font-family: 'JetBrains Mono', monospace; white-space: nowrap; }
        @media(min-width:640px) { .datetime { font-size: 12px; } }
        @media(max-width:480px) { .datetime { display: none; } }

        .search-wrap { position: relative; }
        @media(max-width:640px) { .search-wrap { display: none; } }
        .search-input {
            background: var(--surface-2); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 9px 14px 9px 38px;
            border-radius: var(--radius-md); font-size: 13px; width: 240px;
            outline: none; font-family: 'Outfit', sans-serif; transition: all .2s;
        }
        @media(min-width:900px) { .search-input { width: 280px; } }
        .search-input::placeholder { color: var(--text-3); }
        .search-input:focus { border-color: var(--brand); background: var(--surface); box-shadow: 0 0 0 3px var(--brand-muted); }
        .search-icon { 
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%); 
            color: var(--text-3); display: flex; align-items: center;
        }
        .search-shortcut {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: var(--border); color: var(--text-3); font-size: 10px; font-weight: 600;
            padding: 2px 6px; border-radius: 4px; font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.05em; pointer-events: none;
        }

        .user-area { position: relative; flex-shrink: 0; }
        .user-chip {
            display: flex; align-items: center; gap: 6px;
            padding: 5px 10px; border-radius: 99px;
            background: var(--surface-2); border: 1px solid var(--border);
            cursor: pointer; transition: all .15s;
        }
        @media(min-width:640px) { .user-chip { padding: 6px 12px; gap: 8px; } }
        .user-chip:hover { border-color: var(--brand); background: var(--brand-light); }
        .user-avatar {
            width: 24px; height: 24px; border-radius: 50%;
            background: var(--brand); color: var(--white);
            font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        @media(min-width:640px) { .user-avatar { width: 26px; height: 26px; font-size: 10px; } }
        .user-name { font-size: 11px; font-weight: 500; color: var(--text-2); }
        @media(min-width:640px) { .user-name { font-size: 12px; } }
        @media(max-width:480px) { .user-name { display: none; } }
        .user-chevron { color: var(--text-3); transition: transform .15s; }
        .user-chevron.open { transform: rotate(180deg); }

        .user-dropdown {
            display: none; position: absolute; top: calc(100% + 6px); right: 0;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-md);
            box-shadow: 0 12px 40px rgba(2,24,47,.12); z-index: 60; min-width: 180px;
            padding: 6px; overflow: hidden;
        }
        .user-dropdown.show { display: block; }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: var(--radius-sm);
            text-decoration: none; font-size: 13px; font-weight: 500;
            transition: all .12s; width: 100%; border: none; cursor: pointer;
            font-family: 'Outfit', sans-serif;
        }
        .dropdown-item.default { color: var(--text-2); background: transparent; }
        .dropdown-item.default:hover { background: var(--surface-2); }
        .dropdown-item.danger { color: var(--danger); background: transparent; }
        .dropdown-item.danger:hover { background: #fef2f2; }
        .dropdown-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .cat-bar {
            display: flex; gap: 6px; padding: 10px 16px;
            background: var(--surface); border-bottom: 1px solid var(--border);
            overflow-x: auto; scrollbar-width: none;
        }
        @media(min-width:640px) { .cat-bar { padding: 12px 24px; gap: 8px; } }
        .cat-bar::-webkit-scrollbar { display: none; }
        .cat-btn {
            padding: 5px 12px; border-radius: 99px; font-size: 11px; font-weight: 500;
            white-space: nowrap; border: 1.5px solid var(--border);
            background: transparent; color: var(--text-2); cursor: pointer;
            transition: all .15s; font-family: 'Outfit', sans-serif;
        }
        @media(min-width:640px) { .cat-btn { padding: 6px 16px; font-size: 12px; } }
        .cat-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .cat-btn.active { background: var(--brand); border-color: var(--brand); color: var(--white); font-weight: 600; }

        .product-grid-wrap { flex: 1; overflow-y: auto; padding: 14px 16px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        @media(min-width:640px) { .product-grid-wrap { padding: 20px 24px; } }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; }
        @media(min-width:640px) { .product-grid { grid-template-columns: repeat(auto-fill, minmax(148px, 1fr)); gap: 12px; } }

        .product-card {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: var(--radius-lg); padding: 12px; cursor: pointer;
            transition: all .2s; position: relative; overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        @media(min-width:640px) { .product-card { padding: 16px; } }
        .product-card:hover {
            border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-muted), var(--shadow-md);
            transform: translateY(-1px);
        }
        .product-card:hover .product-icon { transform: scale(1.05); }
        .product-card:active { transform: scale(0.97); }

        .product-icon {
            width: 100%; aspect-ratio: 1.2; border-radius: var(--radius-md);
            background: var(--brand-light); display: flex; align-items: center;
            justify-content: center; margin-bottom: 10px;
            transition: transform 0.2s ease;
        }
        @media(min-width:640px) { .product-icon { margin-bottom: 12px; } }
        .product-icon svg { width: 22px; height: 22px; }
        @media(min-width:640px) { .product-icon svg { width: 28px; height: 28px; } }
        .product-name {
            font-size: 11px; font-weight: 600; color: var(--text-1);
            line-height: 1.35; margin-bottom: 4px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        @media(min-width:640px) { .product-name { font-size: 12px; margin-bottom: 6px; } }
        .product-price {
            font-size: 13px; font-weight: 700; color: var(--brand);
            font-family: 'JetBrains Mono', monospace;
        }
        @media(min-width:640px) { .product-price { font-size: 15px; } }
        .product-stock { font-size: 9px; margin-top: 3px; font-weight: 500; }
        @media(min-width:640px) { .product-stock { font-size: 10px; margin-top: 4px; } }
        .stock-ok { color: var(--text-3); }
        .stock-low { color: var(--warning); }
        .stock-out-badge {
            position: absolute; top: 6px; right: 6px;
            background: var(--danger); color: var(--white);
            font-size: 8px; font-weight: 700; padding: 2px 5px;
            border-radius: 99px; letter-spacing: .04em;
        }
        @media(min-width:640px) { .stock-out-badge { font-size: 9px; padding: 2px 6px; top: 8px; right: 8px; } }
        .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }

        .no-results { 
            text-align: center; color: var(--text-3); padding: 40px; font-size: 13px; 
            display: none;
        }
        @media(min-width:640px) { .no-results { padding: 60px; font-size: 14px; } }
        .no-results-icon { margin-bottom: 10px; color: var(--border-2); }
        @media(min-width:640px) { .no-results-icon { margin-bottom: 12px; } }

        .cart-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; border-bottom: 1px solid var(--border);
        }
        @media(min-width:640px) { .cart-header { padding: 16px 20px; } }
        .cart-title-wrap { display: flex; align-items: center; gap: 8px; }
        @media(min-width:640px) { .cart-title-wrap { gap: 10px; } }
        .cart-title { font-size: 14px; font-weight: 700; color: var(--text-1); }
        @media(min-width:640px) { .cart-title { font-size: 15px; } }
        .cart-badge {
            background: var(--brand); color: var(--white);
            font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 99px;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            min-width: 18px; text-align: center;
        }
        @media(min-width:640px) { .cart-badge { font-size: 11px; padding: 2px 9px; min-width: 20px; } }
        .cart-badge.pulse { transform: scale(1.3); }
        .clear-btn {
            font-size: 11px; color: var(--text-3); background: none; border: none;
            cursor: pointer; font-family: 'Outfit', sans-serif; padding: 4px 8px;
            border-radius: var(--radius-sm); border: 1px solid var(--border);
            transition: all .15s;
        }
        @media(min-width:640px) { .clear-btn { font-size: 12px; padding: 5px 10px; } }
        .clear-btn:hover { color: var(--danger); border-color: var(--danger); background: #fef2f2; }

        .customer-wrap { padding: 10px 16px; border-bottom: 1px solid var(--border); }
        @media(min-width:640px) { .customer-wrap { padding: 12px 20px; } }
        .section-label { font-size: 9px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
        @media(min-width:640px) { .section-label { font-size: 10px; margin-bottom: 6px; } }
        .customer-select {
            width: 100%; background: var(--surface-2); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 7px 10px; border-radius: var(--radius-md);
            font-size: 12px; outline: none; font-family: 'Outfit', sans-serif;
            transition: all .15s; cursor: pointer;
        }
        @media(min-width:640px) { .customer-select { padding: 8px 12px; font-size: 13px; } }
        .customer-select:focus { border-color: var(--brand); background: var(--surface); }

        .cart-items-wrap { flex: 1; overflow-y: auto; padding: 6px 16px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        @media(min-width:640px) { .cart-items-wrap { padding: 8px 20px; } }
        .cart-empty { 
            text-align: center; padding: 30px 16px; 
            opacity: 0.6; transition: opacity 0.3s;
        }
        @media(min-width:640px) { .cart-empty { padding: 40px 20px; } }
        .cart-empty-icon { margin-bottom: 10px; }
        @media(min-width:640px) { .cart-empty-icon { margin-bottom: 12px; } }
        .cart-empty-icon svg { width: 38px; height: 38px; }
        @media(min-width:640px) { .cart-empty-icon svg { width: 48px; height: 48px; } }
        .cart-empty-text { font-size: 12px; color: var(--text-3); }
        @media(min-width:640px) { .cart-empty-text { font-size: 13px; } }

        .cart-item {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 0; border-bottom: 1px solid var(--border);
            animation: slideIn 0.2s ease;
        }
        @media(min-width:640px) { .cart-item { padding: 11px 0; gap: 12px; } }
        .cart-item:last-child { border-bottom: none; }
        @keyframes slideIn { 
            from { opacity: 0; transform: translateX(10px); } 
            to { opacity: 1; transform: translateX(0); } 
        }
        .cart-item-num {
            width: 20px; height: 20px; border-radius: 5px;
            background: var(--brand-light); color: var(--brand);
            font-size: 9px; font-weight: 700; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        @media(min-width:640px) { .cart-item-num { width: 22px; height: 22px; border-radius: 6px; font-size: 10px; } }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name { font-size: 12px; font-weight: 500; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        @media(min-width:640px) { .cart-item-name { font-size: 13px; } }
        .cart-item-price { font-size: 11px; color: var(--brand); font-family: 'JetBrains Mono', monospace; margin-top: 2px; }
        @media(min-width:640px) { .cart-item-price { font-size: 12px; } }

        .qty-control { display: flex; align-items: center; gap: 4px; }
        @media(min-width:640px) { .qty-control { gap: 6px; } }
        .qty-btn {
            width: 24px; height: 24px; border-radius: var(--radius-sm);
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text-2); font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s; font-family: 'Outfit', sans-serif; font-weight: 500;
        }
        @media(min-width:640px) { .qty-btn { width: 28px; height: 28px; font-size: 15px; } }
        .qty-btn:hover.plus { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .qty-btn:hover.minus { border-color: var(--danger); color: var(--danger); background: #fef2f2; }
        .qty-btn:active { transform: scale(0.9); }
        .qty-num { font-size: 12px; font-weight: 700; color: var(--text-1); width: 22px; text-align: center; font-family: 'JetBrains Mono', monospace; }
        @media(min-width:640px) { .qty-num { font-size: 14px; width: 28px; } }

        .remove-btn {
            background: none; border: none; color: var(--text-3); cursor: pointer;
            font-size: 13px; width: 22px; height: 22px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        @media(min-width:640px) { .remove-btn { font-size: 15px; width: 26px; height: 26px; } }
        .remove-btn:hover { color: var(--danger); background: #fef2f2; }

        .totals-wrap { padding: 10px 16px; border-top: 1px solid var(--border); background: var(--surface-2); }
        @media(min-width:640px) { .totals-wrap { padding: 14px 20px; } }
        .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        @media(min-width:640px) { .total-row { margin-bottom: 7px; } }
        .total-label { font-size: 12px; color: var(--text-2); }
        @media(min-width:640px) { .total-label { font-size: 13px; } }
        .total-val { font-size: 12px; color: var(--text-1); font-family: 'JetBrains Mono', monospace; }
        @media(min-width:640px) { .total-val { font-size: 13px; } }
        .total-note { font-size: 10px; color: var(--text-3); }
        @media(min-width:640px) { .total-note { font-size: 11px; } }

        .discount-group { display: flex; align-items: center; gap: 4px; }
        @media(min-width:640px) { .discount-group { gap: 6px; } }
        .discount-prefix { font-size: 11px; color: var(--text-3); }
        @media(min-width:640px) { .discount-prefix { font-size: 12px; } }
        .discount-input {
            width: 60px; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 3px 6px; border-radius: var(--radius-sm);
            font-size: 12px; font-family: 'JetBrains Mono', monospace; outline: none;
            text-align: right; transition: border-color .15s;
        }
        @media(min-width:640px) { .discount-input { width: 80px; padding: 4px 8px; font-size: 13px; } }
        .discount-input:focus { border-color: var(--brand); }

        .grand-row {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 8px; margin-top: 4px; border-top: 2px solid var(--border-2);
        }
        @media(min-width:640px) { .grand-row { padding-top: 10px; margin-top: 6px; } }
        .grand-label { font-size: 14px; font-weight: 700; color: var(--text-1); }
        @media(min-width:640px) { .grand-label { font-size: 16px; } }
        .grand-val { font-size: 18px; font-weight: 700; color: var(--brand); font-family: 'JetBrains Mono', monospace; }
        @media(min-width:640px) { .grand-val { font-size: 22px; } }

        .payment-wrap { padding: 10px 16px 14px; border-top: 1px solid var(--border); }
        @media(min-width:640px) { .payment-wrap { padding: 14px 20px 18px; } }

        .toggle-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        @media(min-width:640px) { .toggle-row { margin-bottom: 12px; } }
        .toggle-label { font-size: 11px; color: var(--text-2); font-weight: 500; }
        @media(min-width:640px) { .toggle-label { font-size: 12px; } }
        .toggle-switch { position: relative; display: inline-block; width: 38px; height: 22px; }
        @media(min-width:640px) { .toggle-switch { width: 42px; height: 24px; } }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background: var(--border-2); border-radius: 99px; transition: .2s;
        }
        .toggle-slider::before {
            content: ''; position: absolute; width: 16px; height: 16px;
            left: 3px; top: 3px; background: var(--white); border-radius: 50%;
            transition: .2s; box-shadow: var(--shadow-sm);
        }
        @media(min-width:640px) { .toggle-slider::before { width: 18px; height: 18px; } }
        input:checked + .toggle-slider { background: var(--brand); }
        input:checked + .toggle-slider::before { transform: translateX(16px); }
        @media(min-width:640px) { input:checked + .toggle-slider::before { transform: translateX(18px); } }

        .pay-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin-bottom: 10px; }
        @media(min-width:640px) { .pay-methods { gap: 8px; margin-bottom: 12px; } }
        .pay-btn {
            padding: 8px 3px; border-radius: var(--radius-md); border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text-2); font-size: 11px; font-weight: 500;
            cursor: pointer; transition: all .15s; font-family: 'Outfit', sans-serif;
            display: flex; flex-direction: column; align-items: center; gap: 4px;
        }
        @media(min-width:640px) { .pay-btn { padding: 10px 4px; font-size: 12px; gap: 5px; } }
        .pay-btn svg { width: 15px; height: 15px; }
        @media(min-width:640px) { .pay-btn svg { width: 18px; height: 18px; } }
        .pay-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .pay-btn.active { background: var(--brand); border-color: var(--brand); color: var(--white); }
        .pay-btn.active svg { color: var(--white); }

        .amount-section { margin-bottom: 8px; }
        @media(min-width:640px) { .amount-section { margin-bottom: 10px; } }
        .amount-label { font-size: 10px; color: var(--text-3); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
        @media(min-width:640px) { .amount-label { font-size: 11px; margin-bottom: 6px; } }
        .amount-input {
            width: 100%; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 9px 12px; border-radius: var(--radius-md);
            font-size: 17px; font-weight: 700; font-family: 'JetBrains Mono', monospace;
            outline: none; transition: all .2s;
        }
        @media(min-width:640px) { .amount-input { padding: 11px 14px; font-size: 20px; } }
        .amount-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px var(--brand-muted); }
        .amount-input::placeholder { color: var(--border-2); font-size: 13px; font-weight: 400; }
        @media(min-width:640px) { .amount-input::placeholder { font-size: 15px; } }

        .change-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        @media(min-width:640px) { .change-row { margin-bottom: 14px; } }
        .change-label { font-size: 12px; color: var(--text-2); }
        @media(min-width:640px) { .change-label { font-size: 13px; } }
        .change-val { font-size: 13px; font-weight: 700; font-family: 'JetBrains Mono', monospace; transition: color .15s; }
        @media(min-width:640px) { .change-val { font-size: 15px; } }
        .change-ok { color: var(--success); }
        .change-short { color: var(--danger); }

        .split-box { background: var(--surface-2); border: 1.5px solid var(--border); border-radius: var(--radius-md); padding: 10px; margin-bottom: 10px; }
        @media(min-width:640px) { .split-box { padding: 12px; margin-bottom: 12px; } }
        .split-row-inp { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
        @media(min-width:640px) { .split-row-inp { gap: 10px; margin-bottom: 8px; } }
        .split-row-inp:last-child { margin-bottom: 0; }
        .split-row-inp svg { width: 14px; height: 14px; flex-shrink: 0; }
        @media(min-width:640px) { .split-row-inp svg { width: 16px; height: 16px; } }
        .split-lbl { font-size: 11px; color: var(--text-2); font-weight: 500; width: 42px; }
        @media(min-width:640px) { .split-lbl { font-size: 12px; width: 48px; } }
        .split-inp {
            flex: 1; background: var(--surface); border: 1.5px solid var(--border);
            color: var(--text-1); padding: 6px 8px; border-radius: var(--radius-sm);
            font-size: 12px; font-family: 'JetBrains Mono', monospace; outline: none;
            transition: border-color .15s;
        }
        @media(min-width:640px) { .split-inp { padding: 7px 10px; font-size: 13px; } }
        .split-inp:focus { border-color: var(--brand); }
        .split-footer { display: flex; justify-content: space-between; padding-top: 8px; border-top: 1px solid var(--border); margin-top: 8px; }
        @media(min-width:640px) { .split-footer { padding-top: 10px; margin-top: 10px; } }
        .split-foot-item { font-size: 11px; }
        @media(min-width:640px) { .split-foot-item { font-size: 12px; } }
        .split-foot-lbl { color: var(--text-3); }
        .split-foot-val { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: var(--text-1); }

        .complete-btn {
            width: 100%; padding: 12px; border-radius: var(--radius-md); border: none;
            background: var(--brand); color: var(--white);
            font-size: 14px; font-weight: 700; font-family: 'Outfit', sans-serif;
            cursor: pointer; letter-spacing: .01em; transition: all .2s;
            box-shadow: 0 4px 12px rgba(3,167,55,.3);
        }
        @media(min-width:640px) { .complete-btn { padding: 14px; font-size: 15px; } }
        .complete-btn:hover { background: var(--brand-dark); box-shadow: 0 6px 20px rgba(3,167,55,.4); transform: translateY(-1px); }
        .complete-btn:active { transform: translateY(0); box-shadow: none; }
        .complete-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 100;
            background: rgba(2,24,47,.35); backdrop-filter: blur(3px);
            align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-card {
            background: var(--surface); border-radius: var(--radius-xl);
            padding: 24px 20px; width: 90%; max-width: 360px; box-shadow: 0 24px 60px rgba(2,24,47,.18);
            border: 1px solid var(--border);
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1);
        }
        @media(min-width:640px) { .modal-card { padding: 32px 28px; } }
        @keyframes modalIn { from { opacity:0; transform:scale(.88) translateY(12px); } to { opacity:1; transform:none; } }

        .modal-icon-wrap {
            width: 56px; height: 56px; border-radius: 50%; border: 2px solid var(--brand-muted);
            background: var(--brand-light); display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        @media(min-width:640px) { .modal-icon-wrap { width: 64px; height: 64px; margin-bottom: 18px; } }
        .modal-icon-wrap svg { width: 24px; height: 24px; color: var(--success); }
        @media(min-width:640px) { .modal-icon-wrap svg { width: 28px; height: 28px; } }
        .modal-title { text-align: center; font-size: 18px; font-weight: 700; color: var(--text-1); margin-bottom: 4px; }
        @media(min-width:640px) { .modal-title { font-size: 20px; } }
        .modal-invoice { text-align: center; font-size: 11px; color: var(--text-3); font-family: 'JetBrains Mono', monospace; margin-bottom: 16px; }
        @media(min-width:640px) { .modal-invoice { font-size: 12px; margin-bottom: 20px; } }

        .modal-summary { background: var(--surface-2); border-radius: var(--radius-md); padding: 12px; margin-bottom: 16px; border: 1px solid var(--border); }
        @media(min-width:640px) { .modal-summary { padding: 16px; margin-bottom: 20px; } }
        .modal-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; }
        @media(min-width:640px) { .modal-row { padding: 6px 0; } }
        .modal-row + .modal-row { border-top: 1px solid var(--border); }
        .modal-lbl { font-size: 12px; color: var(--text-2); }
        @media(min-width:640px) { .modal-lbl { font-size: 13px; } }
        .modal-val { font-size: 12px; font-weight: 600; font-family: 'JetBrains Mono', monospace; }
        @media(min-width:640px) { .modal-val { font-size: 13px; } }
        .modal-total { font-size: 16px; font-weight: 700; color: var(--brand); }
        @media(min-width:640px) { .modal-total { font-size: 18px; } }
        .modal-change { color: var(--success); }

        .modal-btns { display: flex; gap: 8px; }
        @media(min-width:640px) { .modal-btns { gap: 10px; } }
        .modal-btn-outline {
            flex: 1; padding: 10px; border-radius: var(--radius-md);
            border: 1.5px solid var(--border); background: transparent;
            color: var(--text-2); font-size: 12px; font-weight: 600;
            cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s;
            display: flex; align-items: center; justify-content: center; gap: 4px;
        }
        @media(min-width:640px) { .modal-btn-outline { padding: 12px; font-size: 13px; gap: 6px; } }
        .modal-btn-outline:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .modal-btn-solid {
            flex: 1; padding: 10px; border-radius: var(--radius-md); border: none;
            background: var(--brand); color: var(--white);
            font-size: 12px; font-weight: 700;
            cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .15s;
        }
        @media(min-width:640px) { .modal-btn-solid { padding: 12px; font-size: 13px; } }
        .modal-btn-solid:hover { background: var(--brand-dark); }

        .toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            background: var(--text-1); color: var(--white); padding: 10px 20px;
            border-radius: var(--radius-md); font-size: 13px; font-weight: 500;
            z-index: 200; box-shadow: var(--shadow-md); white-space: nowrap;
            animation: toastIn .2s ease;
        }
        .toast.error { background: var(--danger); }
        @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(8px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 99px; }
    </style>
</head>
<body>

<div id="pos-app">

    <div class="left-panel">

        <div class="top-bar">
            <div class="top-bar-left">
                @can('view_dashboard')
                <a href="{{ route('dashboard') }}" class="back-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="back-btn-text">Back</span>
                </a>
                <div class="divider-v"></div>
                @endcan
                <div class="brand-info">
                    <span class="brand-name">{{ auth()->user()->tenant->name }}</span>
                    @if(auth()->user()->branch)
                        <span class="branch-name">{{ auth()->user()->branch->name }}</span>
                    @endif
                </div>
                <div class="divider-v"></div>
                <span class="datetime" id="live-clock"></span>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <div class="search-wrap">
                    <span class="search-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="search-input" class="search-input"
                        placeholder="Search or scan barcode…" />
                    <span class="search-shortcut" id="search-shortcut">/</span>
                </div>
                
                <div class="user-area">
                    <div class="user-chip" onclick="toggleDropdown()">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <svg class="user-chevron" id="user-chevron" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <div class="user-dropdown" id="user-dropdown">
                        @can('view_dashboard')
                        <a href="{{ route('dashboard') }}" class="dropdown-item default">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                            Dashboard
                        </a>
                        @endcan
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="cat-bar">
            <button class="cat-btn active" data-cat="all" onclick="filterCategory('all', this)">All Items</button>
            @foreach($categories as $cat)
                <button class="cat-btn" data-cat="{{ $cat->id }}"
                    onclick="filterCategory('{{ $cat->id }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <div class="product-grid-wrap">
            <div class="product-grid" id="product-grid">
                @php
                    $iconColors = ['#03A737','#3D7BE7','#02182F','#03A737','#d97706','#dc2626','#3D7BE7','#02182F'];
                @endphp
                @foreach($products as $product)
                    @php 
                        $colorIndex = $product->id % count($iconColors);
                        $iconColor = $iconColors[$colorIndex];
                    @endphp
                    <div class="product-card {{ ($product->track_stock && $product->stock_qty <= 0) ? 'out-of-stock' : '' }}"
                         data-id="{{ $product->id }}"
                         data-name="{{ $product->name }}"
                         data-price="{{ $product->price }}"
                         data-stock="{{ $product->stock_qty }}"
                         data-track="{{ $product->track_stock ? '1' : '0' }}"
                         data-cat="{{ $product->category_id ?? 'none' }}"
                         data-barcode="{{ $product->barcode }}"
                         data-sku="{{ $product->sku }}"
                         onclick="addToCart(this)">

                        @if($product->track_stock && $product->stock_qty <= 0)
                            <span class="stock-out-badge">OUT</span>
                        @endif

                        <div class="product-icon">
                            <svg fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">KES {{ number_format($product->price, 2) }}</div>

                        @if($product->track_stock)
    @if($product->stock_qty <= 0)
    @elseif($product->stock_qty <= $product->low_stock_alert)
        <div class="product-stock stock-low">Only {{ $product->stock_qty }} left</div>
    @else
        <div class="product-stock stock-ok">{{ $product->stock_qty }} in stock</div>
    @endif
@endif
                    </div>
                @endforeach
            </div>
            <div id="no-results" class="no-results">
                <div class="no-results-icon">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                No products found
            </div>
        </div>
    </div>

    <div class="right-panel">

        <div class="cart-header">
            <div class="cart-title-wrap">
                <span class="cart-title">Current Sale</span>
                <span class="cart-badge" id="cart-count">0</span>
            </div>
            <button class="clear-btn" onclick="clearCart()">Clear all</button>
        </div>

        <div class="customer-wrap">
            <div class="section-label">Customer</div>
            <select id="customer-select" class="customer-select">
                <option value="">Walk-in Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} · {{ $customer->phone }}</option>
                @endforeach
            </select>
        </div>

        <div class="cart-items-wrap" id="cart-items">
            <div class="cart-empty" id="empty-cart">
                <div class="cart-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <div class="cart-empty-text">Tap a product to add it to the sale</div>
            </div>
        </div>

        <div class="totals-wrap">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-val" id="subtotal-display">KES 0.00</span>
            </div>
            <div class="total-row">
                <span class="total-label" style="font-size:11px;color:var(--text-3)">Tax</span>
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

        <div class="payment-wrap">

            <div class="toggle-row">
                <span class="toggle-label">Split Payment</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="split-toggle" onchange="toggleSplit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div id="single-payment">
                <div class="pay-methods">
                    <button class="pay-btn active" data-method="cash" onclick="setPayment('cash', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Cash
                    </button>
                    <button class="pay-btn" data-method="card" onclick="setPayment('card', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Card
                    </button>
                    <button class="pay-btn" data-method="mobile" onclick="setPayment('mobile', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        M-Pesa
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

            <div id="split-payment" style="display:none">
                <div class="split-box">
                    <div class="split-row-inp">
                        <svg fill="none" stroke="#03A737" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="split-lbl">Cash</span>
                        <input type="number" id="split-cash" class="split-inp"
                            placeholder="0.00" min="0" step="0.01" oninput="updateSplitChange()" />
                    </div>
                    <div class="split-row-inp">
                        <svg fill="none" stroke="#3D7BE7" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="split-lbl">Card</span>
                        <input type="number" id="split-card" class="split-inp"
                            placeholder="0.00" min="0" step="0.01" oninput="updateSplitChange()" />
                    </div>
                    <div class="split-row-inp">
                        <svg fill="none" stroke="#02182F" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
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

            <button class="complete-btn" id="complete-btn" onclick="processSale()" disabled>
                Complete Sale
            </button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="receipt-modal">
    <div class="modal-card">
        <div class="modal-icon-wrap">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
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
            <button class="modal-btn-outline" onclick="printReceipt()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Receipt
            </button>
            <button class="modal-btn-solid" onclick="newSale()">New Sale</button>
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

/* ─── User dropdown ─── */
function toggleDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    const chevron = document.getElementById('user-chevron');
    dropdown.classList.toggle('show');
    chevron.classList.toggle('open');
}

document.addEventListener('click', function(e) {
    const userArea = document.querySelector('.user-area');
    const dropdown = document.getElementById('user-dropdown');
    const chevron = document.getElementById('user-chevron');
    if (userArea && !userArea.contains(e.target)) {
        dropdown.classList.remove('show');
        chevron.classList.remove('open');
    }
});

/* ─── Keyboard shortcuts ─── */
document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
    if (e.key === '/') {
        e.preventDefault();
        document.getElementById('search-input').focus();
    }
});

document.getElementById('search-input').addEventListener('focus', function() {
    document.getElementById('search-shortcut').style.display = 'none';
});
document.getElementById('search-input').addEventListener('blur', function() {
    if (!this.value) {
        document.getElementById('search-shortcut').style.display = '';
    }
});

/* ─── Barcode Scanner ─── */
let barcodeBuffer = '';
let barcodeTimer = null;

document.getElementById('search-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const barcode = this.value.trim();
        if (barcode) {
            document.querySelectorAll('.product-card').forEach(c => c.style.display = '');
            document.getElementById('no-results').style.display = 'none';
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            const allBtn = document.querySelector('.cat-btn[data-cat="all"]');
            if (allBtn) allBtn.classList.add('active');
            
            addProductByBarcode(barcode);
            this.value = '';
        }
    }
});

document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
    
    if (e.key.length === 1) {
        barcodeBuffer += e.key;
        clearTimeout(barcodeTimer);
        barcodeTimer = setTimeout(function() {
            if (barcodeBuffer.length > 3) {
                addProductByBarcode(barcodeBuffer);
            }
            barcodeBuffer = '';
        }, 50);
    }
});

function addProductByBarcode(barcode) {
    console.log('Searching for barcode/SKU:', barcode);
    
    const allCards = document.querySelectorAll('.product-card');
    let found = null;
    
    for (const card of allCards) {
        const cardBarcode = (card.dataset.barcode || '').trim();
        const cardSku = (card.dataset.sku || '').trim();
        
        console.log('Checking:', cardSku, cardBarcode);
        
        if (cardBarcode.toLowerCase() === barcode.toLowerCase() || cardSku.toLowerCase() === barcode.toLowerCase()) {
            found = card;
            break;
        }
    }
    
    if (found && !found.classList.contains('out-of-stock')) {
        addToCart(found);
        found.style.transform = 'scale(0.95)';
        found.style.borderColor = '#03A737';
        found.style.boxShadow = '0 0 0 3px rgba(3,167,55,0.3)';
        setTimeout(() => {
            found.style.transform = '';
            found.style.borderColor = '';
            found.style.boxShadow = '';
        }, 300);
        toast('Added: ' + found.dataset.name, '');
    } else if (!found) {
        console.log('Not found. Available SKUs:', [...allCards].map(c => c.dataset.sku).join(', '));
        toast('Product not found: ' + barcode, 'error');
    }
}

/* ─── Add to cart ─── */
function addToCart(el) {
    if (el.classList.contains('out-of-stock')) return;

    const id    = parseInt(el.dataset.id);
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    const stock = parseInt(el.dataset.stock);
    const track = el.dataset.track === '1';

    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (track && existing.qty >= stock) { 
            toast('Not enough stock available', 'error'); 
            return; 
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, stock, track });
    }

    el.style.transform = 'scale(0.95)';
    setTimeout(() => el.style.transform = '', 120);

    renderCart();
}

function renderCart() {
    const wrap  = document.getElementById('cart-items');
    const count = document.getElementById('cart-count');

    const totalItems = cart.reduce((s, i) => s + i.qty, 0);
    count.textContent = totalItems;
    count.classList.add('pulse');
    setTimeout(() => count.classList.remove('pulse'), 200);

    if (cart.length === 0) {
        wrap.innerHTML = `
            <div class="cart-empty" id="empty-cart">
                <div class="cart-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <div class="cart-empty-text">Tap a product to add it to the sale</div>
            </div>
        `;
        updateTotals();
        updateCompleteButton();
        return;
    }

    wrap.innerHTML = cart.map((item, i) => `
        <div class="cart-item" data-index="${i}">
            <div class="cart-item-num">${i + 1}</div>
            <div class="cart-item-info">
                <div class="cart-item-name" title="${item.name}">${item.name}</div>
                <div class="cart-item-price">KES ${(item.price * item.qty).toFixed(2)}</div>
            </div>
            <div class="qty-control">
                <button class="qty-btn minus" onclick="updateQty(${i}, -1)" type="button">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn plus" onclick="updateQty(${i}, 1)" type="button">+</button>
            </div>
            <button class="remove-btn" onclick="removeItem(${i})" title="Remove" type="button">×</button>
        </div>
    `).join('');

    updateTotals();
    updateCompleteButton();
}

function updateCompleteButton() {
    const btn = document.getElementById('complete-btn');
    btn.disabled = cart.length === 0;
}

function updateQty(index, change) {
    if (index < 0 || index >= cart.length) return;
    const item = cart[index];
    
    if (change > 0) {
        if (item.track && item.qty >= item.stock) {
            toast('Not enough stock available', 'error');
            return;
        }
        item.qty++;
    } else {
        item.qty--;
        if (item.qty <= 0) {
            cart.splice(index, 1);
            renderCart();
            return;
        }
    }
    
    updateCartItemDisplay(index);
    updateTotals();
}

function updateCartItemDisplay(index) {
    const item = cart[index];
    const cartItem = document.querySelector(`.cart-item[data-index="${index}"]`);
    if (!cartItem) return;
    
    const priceEl = cartItem.querySelector('.cart-item-price');
    if (priceEl) priceEl.textContent = `KES ${(item.price * item.qty).toFixed(2)}`;
    
    const qtyEl = cartItem.querySelector('.qty-num');
    if (qtyEl) qtyEl.textContent = item.qty;
    
    const totalItems = cart.reduce((s, i) => s + i.qty, 0);
    const count = document.getElementById('cart-count');
    count.textContent = totalItems;
    count.classList.add('pulse');
    setTimeout(() => count.classList.remove('pulse'), 200);
}

function removeItem(index) {
    if (index < 0 || index >= cart.length) return;
    cart.splice(index, 1);
    renderCart();
}

function clearCart() {
    if (!cart.length) return;
    if (!confirm('Clear the current sale?')) return;
    cart = [];
    renderCart();
}

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

document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
    checkNoResults();
});

function toast(msg, type = '') {
    const el = document.createElement('div');
    el.className = 'toast ' + type;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2800);
}

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
            toast('Amount paid is less than total', 'error');
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
    btn.disabled = true; btn.textContent = 'Processing...';

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
            document.getElementById('receipt-payment').textContent = payload.payment_method === 'split' ? 'Split Payment' : paymentMethod;
            document.getElementById('receipt-change').textContent  = 'KES ' + parseFloat(data.change).toFixed(2);
            document.getElementById('receipt-modal').classList.add('show');
        } else {
            toast(data.message || 'Sale failed', 'error');
        }
    } catch (e) {
        toast('Network error', 'error');
    } finally {
        btn.disabled = false; btn.textContent = 'Complete Sale';
        updateCompleteButton();
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
    if (isSplit) { 
        document.getElementById('split-toggle').checked = false; 
        toggleSplit(); 
    }
    renderCart();
}
</script>
</body>
</html>