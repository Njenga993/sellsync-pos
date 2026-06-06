<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Edit Product
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
            --brand-black: #02182F;
            --brand-white: #FFFEFE;
        }
        
        .form-panel {
            background: var(--brand-white);
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
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
        
        .form-group { margin-bottom: 16px; }
        
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
        
        .form-textarea {
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
            resize: vertical;
            min-height: 80px;
        }
        .form-textarea:focus {
            border-color: var(--brand-green);
            background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .checkbox-custom {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 4px;
            background: var(--brand-white);
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
            flex-shrink: 0;
        }
        .checkbox-custom:checked {
            background: var(--brand-green);
            border-color: var(--brand-green);
        }
        .checkbox-custom:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        @media (max-width: 640px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
        
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
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:720px;margin:0 auto;padding:0 16px">

            {{-- Product Info Bar --}}
            <div class="info-bar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Editing: <strong>{{ $product->name }}</strong>
                @if($product->sku)
                    · SKU: <span style="font-family:'JetBrains Mono',monospace;font-size:11px">{{ $product->sku }}</span>
                @endif
            </div>

            <form method="POST" action="{{ route('products.update', $product) }}">
                @csrf @method('PUT')

                {{-- Basic Information --}}
                <div class="form-panel">
                    <div class="section-title">Basic Information</div>

                    <div class="form-group">
                        <label class="form-label" for="name">Product Name *</label>
                        <input id="name" name="name" type="text" class="form-input"
                            value="{{ old('name', $product->name) }}" required />
                        @if($errors->has('name'))
                            <div class="error-msg">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">No category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active"   {{ old('status', $product->status) === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="description">Description (optional)</label>
                        <textarea id="description" name="description" rows="3" class="form-textarea"
                            placeholder="Brief description of the product...">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="form-panel">
                    <div class="section-title">Pricing</div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label" for="price">Selling Price (KES) *</label>
                            <input id="price" name="price" type="number" step="0.01" class="form-input"
                                value="{{ old('price', $product->price) }}" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="cost_price">Cost Price (KES)</label>
                            <input id="cost_price" name="cost_price" type="number" step="0.01" class="form-input"
                                value="{{ old('cost_price', $product->cost_price) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tax_rate">Tax Rate (%)</label>
                            <input id="tax_rate" name="tax_rate" type="number" step="0.01" class="form-input"
                                value="{{ old('tax_rate', $product->tax_rate) }}" />
                        </div>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="form-panel">
                    <div class="section-title">Inventory</div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="sku">SKU</label>
                            <input id="sku" name="sku" type="text" class="form-input"
                                value="{{ old('sku', $product->sku) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="barcode">Barcode</label>
                            <input id="barcode" name="barcode" type="text" class="form-input"
                                value="{{ old('barcode', $product->barcode) }}" />
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label" for="stock_qty">Stock Quantity</label>
                            <input id="stock_qty" name="stock_qty" type="number" class="form-input"
                                value="{{ old('stock_qty', $product->stock_qty) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="low_stock_alert">Low Stock Alert At</label>
                            <input id="low_stock_alert" name="low_stock_alert" type="number" class="form-input"
                                value="{{ old('low_stock_alert', $product->low_stock_alert) }}" />
                        </div>
                        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:10px">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-family:'Outfit',sans-serif;font-size:13px;color:#374151;user-select:none">
                                <input type="checkbox" name="track_stock" value="1"
                                    {{ old('track_stock', $product->track_stock) ? 'checked' : '' }}
                                    class="checkbox-custom" />
                                Track stock
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div style="display:flex;gap:12px">
                    <button type="submit" class="btn-primary">Update Product</button>
                    <a href="{{ route('products.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>