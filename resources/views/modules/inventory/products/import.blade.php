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
                    Import Products
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
        
        .file-upload-area {
            border: 2px dashed #e4e7ef;
            border-radius: 14px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            background: #fafbff;
        }
        .file-upload-area:hover {
            border-color: var(--brand-green);
            background: var(--brand-green-light);
        }
        .file-upload-area.dragover {
            border-color: var(--brand-green);
            background: var(--brand-green-light);
        }
        
        .file-input {
            display: none;
        }
        
        .upload-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--brand-green-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }
        .upload-icon svg {
            width: 24px;
            height: 24px;
            color: var(--brand-green);
        }
        
        .upload-text {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--brand-black);
            margin-bottom: 4px;
        }
        .upload-hint {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #9ca3af;
        }
        .upload-hint span {
            color: var(--brand-green);
            font-weight: 600;
        }
        
        .file-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--brand-green);
            font-weight: 600;
            margin-top: 12px;
            display: none;
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
            display: none;
            margin-top: 16px;
            width: 100%;
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            text-align: center;
        }
        .btn-cancel:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-light);
        }
        
        .template-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
        }
        .template-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 14px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .template-table td {
            padding: 10px 14px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        .template-table tr:last-child td { border-bottom: none; }
        .required { color: #dc2626; }
        .optional { color: #9ca3af; }
        
        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--brand-green);
            text-decoration: none;
            padding: 8px 14px;
            border: 1.5px solid var(--brand-green);
            border-radius: 8px;
            transition: all 0.15s;
        }
        .download-link:hover {
            background: var(--brand-green-light);
        }
        
        .info-bar {
            background: var(--brand-green-light);
            border: 1px solid #b8e6c4;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #028a2e;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:680px;margin:0 auto;padding:0 16px">

            {{-- Upload Section --}}
            <div class="form-panel">
                <div class="section-title">Upload CSV File</div>

                <form method="POST" action="{{ route('products.import.process') }}" enctype="multipart/form-data" id="import-form">
                    @csrf
                    
                    <div class="file-upload-area" id="drop-zone" onclick="document.getElementById('csv-file').click()">
                        <div class="upload-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div class="upload-text">Click to upload or drag and drop</div>
                        <div class="upload-hint">CSV files only · Max 2MB · <span>UTF-8 encoding</span></div>
                        <div class="file-name" id="file-name"></div>
                    </div>
                    
                    <input type="file" name="file" id="csv-file" class="file-input" accept=".csv" onchange="handleFileSelect(this)" />
                    
                    <button type="submit" class="btn-primary" id="submit-btn">Import Products</button>
                </form>
                
                @if ($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 18px;margin-top:16px;font-family:'Outfit',sans-serif;font-size:13px;color:#b91c1c">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Template & Instructions --}}
            <div class="form-panel">
                <div class="section-title">CSV Format & Template</div>
                
                <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#6b7280;margin-bottom:16px">
                    Your CSV file must include the following columns in this exact order. 
                    <strong>Name</strong> and <strong>Price</strong> are required.
                </p>
                
                <div style="overflow-x:auto;margin-bottom:16px">
                    <table class="template-table">
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>Required</th>
                                <th>Example</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:600">name</td>
                                <td><span class="required">Required</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">Coca Cola 500ml</td>
                                <td>Product name</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">sku</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">BEV-001</td>
                                <td>Stock keeping unit</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">barcode</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">6164000012345</td>
                                <td>Product barcode</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">category</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">Beverages</td>
                                <td>Creates category if new</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">price</td>
                                <td><span class="required">Required</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">150.00</td>
                                <td>Selling price in KES</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">cost_price</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">100.00</td>
                                <td>Cost price in KES</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">tax_rate</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">16</td>
                                <td>Tax percentage (0-100)</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">stock_qty</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">50</td>
                                <td>Opening stock quantity</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600">low_stock_alert</td>
                                <td><span class="optional">Optional</span></td>
                                <td class="mono" style="font-family:'JetBrains Mono',monospace;font-size:11px">10</td>
                                <td>Alert when below this (default: 5)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <a href="#" onclick="downloadTemplate()" class="download-link">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download CSV Template
                    </a>
                </div>
                
                <div class="info-bar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <strong>Tip:</strong> Save your Excel file as CSV (Comma delimited) (*.csv) before uploading. 
                        Make sure the first row contains the column headers exactly as shown above.
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:12px">
                <a href="{{ route('products.index') }}" class="btn-cancel" style="flex:1">Back to Products</a>
            </div>
        </div>
    </div>

<script>
function handleFileSelect(input) {
    const file = input.files[0];
    const fileName = document.getElementById('file-name');
    const submitBtn = document.getElementById('submit-btn');
    
    if (file) {
        fileName.textContent = '📄 ' + file.name;
        fileName.style.display = 'block';
        submitBtn.style.display = 'block';
    } else {
        fileName.style.display = 'none';
        submitBtn.style.display = 'none';
    }
}

// Drag and drop
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', function() {
    dropZone.classList.remove('dragover');
});
dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('csv-file').files = e.dataTransfer.files;
        handleFileSelect(document.getElementById('csv-file'));
    }
});

function downloadTemplate() {
    const csvContent = 'name,sku,barcode,category,price,cost_price,tax_rate,stock_qty,low_stock_alert\n' +
        'Coca Cola 500ml,BEV-001,6164000012345,Beverages,150.00,100.00,16,50,10\n' +
        'Fanta Orange 500ml,BEV-002,6164000012346,Beverages,150.00,100.00,16,40,10\n' +
        'Blue Band 500g,SPR-001,6164000012347,Spreads,250.00,180.00,16,30,5\n' +
        'Bread Loaf,BAK-001,,Bakery,65.00,45.00,0,20,5\n' +
        'Milk 1L,DRY-001,,Dairy,120.00,90.00,0,60,15';
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'product_import_template.csv';
    link.click();
}
</script>
</div>
</x-app-layout>