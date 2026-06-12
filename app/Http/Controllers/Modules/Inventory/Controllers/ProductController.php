<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('tenant_id', auth()->user()->tenant_id)
            ->with('category')
            ->latest()
            ->paginate(20);

        return view('modules.inventory.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        return view('modules.inventory.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'category_id'     => ['nullable', 'exists:categories,id'],
            'sku'             => ['nullable', 'string', 'max:100'],
            'barcode'         => ['nullable', 'string', 'max:100'],
            'price'           => ['required', 'numeric', 'min:0'],
            'cost_price'      => ['nullable', 'numeric', 'min:0'],
            'tax_rate'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_qty'       => ['nullable', 'integer', 'min:0'],
            'low_stock_alert' => ['nullable', 'integer', 'min:0'],
            'track_stock'     => ['nullable', 'boolean'],
            'description'     => ['nullable', 'string'],
            'status'          => ['required', 'in:active,inactive'],
        ]);

        $product = Product::create([
            'tenant_id'       => auth()->user()->tenant_id,
            'category_id'     => $request->category_id,
            'name'            => $request->name,
            'sku'             => $request->sku ?? 'SKU-' . strtoupper(Str::random(8)),
            'barcode'         => $request->barcode,
            'price'           => $request->price,
            'cost_price'      => $request->cost_price ?? 0,
            'tax_rate'        => $request->tax_rate ?? 0,
            'track_stock'     => $request->boolean('track_stock', true),
            'description'     => $request->description,
            'status'          => $request->status,
        ]);

        // Sync stock to the current branch's pivot table
        if ($product->track_stock) {
            $product->branches()->syncWithoutDetaching([
                auth()->user()->branch_id => [
                    'stock_qty'       => $request->stock_qty ?? 0,
                    'low_stock_alert' => $request->low_stock_alert ?? 5,
                ]
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'variants');
        return view('modules.inventory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        return view('modules.inventory.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'category_id'     => ['nullable', 'exists:categories,id'],
            'sku'             => ['nullable', 'string', 'max:100'],
            'barcode'         => ['nullable', 'string', 'max:100'],
            'price'           => ['required', 'numeric', 'min:0'],
            'cost_price'      => ['nullable', 'numeric', 'min:0'],
            'tax_rate'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock_qty'       => ['nullable', 'integer', 'min:0'],
            'low_stock_alert' => ['nullable', 'integer', 'min:0'],
            'track_stock'     => ['nullable', 'boolean'],
            'description'     => ['nullable', 'string'],
            'status'          => ['required', 'in:active,inactive'],
        ]);

        $product->update([
            'category_id'     => $request->category_id,
            'name'            => $request->name,
            'sku'             => $request->sku,
            'barcode'         => $request->barcode,
            'price'           => $request->price,
            'cost_price'      => $request->cost_price ?? 0,
            'tax_rate'        => $request->tax_rate ?? 0,
            'track_stock'     => $request->boolean('track_stock', true),
            'description'     => $request->description,
            'status'          => $request->status,
        ]);

        // Sync stock to the current branch's pivot table
        if ($product->track_stock) {
            $product->branches()->syncWithoutDetaching([
                auth()->user()->branch_id => [
                    'stock_qty'       => $request->stock_qty ?? 0,
                    'low_stock_alert' => $request->low_stock_alert ?? 5,
                ]
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Show the import form.
     */
    public function importForm()
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)->get();
        return view('modules.inventory.products.import', compact('categories'));
    }

    /**
     * Process the CSV import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Read header row
        $header = fgetcsv($handle);
        
        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'The CSV file appears to be empty.');
        }
        
        // Normalize header names (trim whitespace, lowercase)
        $header = array_map(function($col) {
            return strtolower(trim($col));
        }, $header);
        
        // Validate required columns exist
        $requiredColumns = ['name', 'price'];
        $missingColumns = array_diff($requiredColumns, $header);
        
        if (!empty($missingColumns)) {
            fclose($handle);
            return redirect()->back()->with('error', 
                'Missing required columns: ' . implode(', ', $missingColumns) . 
                '. Please use the template format.'
            );
        }
        
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNumber = 1;
        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;
        
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            
            // Skip completely empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Pad row to match header length
            $row = array_pad($row, count($header), null);
            
            // Combine header with row data
            $data = array_combine($header, $row);
            
            // Validate required fields
            $name = trim($data['name'] ?? '');
            $price = trim($data['price'] ?? '');
            
            if (empty($name)) {
                $skipped++;
                $errors[] = "Row {$rowNumber}: Missing product name";
                continue;
            }
            
            if ($price === '' || !is_numeric($price) || floatval($price) < 0) {
                $skipped++;
                $errors[] = "Row {$rowNumber}: Invalid or missing price for '{$name}'";
                continue;
            }
            
            // Find or create category
            $categoryId = null;
            $categoryName = trim($data['category'] ?? '');
            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'name' => $categoryName,
                    ],
                    [
                        'status' => 'active',
                        'slug' => Str::slug($categoryName),
                    ]
                );
                $categoryId = $category->id;
            }
            
            // Check for duplicate SKU
            $sku = trim($data['sku'] ?? '');
            if (!empty($sku)) {
                $existingSku = Product::where('tenant_id', $tenantId)
                    ->where('sku', $sku)
                    ->exists();
                if ($existingSku) {
                    $skipped++;
                    $errors[] = "Row {$rowNumber}: SKU '{$sku}' already exists for '{$name}'";
                    continue;
                }
            }
            
            // Generate SKU if empty
            if (empty($sku)) {
                $sku = 'SKU-' . strtoupper(Str::random(8));
            }
            
            // Create product
            try {
                $product = Product::create([
                    'tenant_id'       => $tenantId,
                    'name'            => $name,
                    'sku'             => $sku,
                    'barcode'         => trim($data['barcode'] ?? '') ?: null,
                    'category_id'     => $categoryId,
                    'price'           => floatval($price),
                    'cost_price'      => floatval($data['cost_price'] ?? 0),
                    'tax_rate'        => floatval($data['tax_rate'] ?? 0),
                    'track_stock'     => true,
                    'status'          => 'active',
                ]);

                // Sync stock to branch pivot
                $product->branches()->syncWithoutDetaching([
                    $branchId => [
                        'stock_qty'       => intval($data['stock_qty'] ?? 0),
                        'low_stock_alert' => intval($data['low_stock_alert'] ?? 5),
                    ]
                ]);

                $imported++;
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row {$rowNumber}: Failed to import '{$name}' - " . $e->getMessage();
            }
        }
        
        fclose($handle);
        
        // Build success message
        $message = "{$imported} product(s) imported successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) skipped.";
        }
        
        // Log errors if any
        if (!empty($errors) && $imported === 0) {
            return redirect()->back()
                ->with('error', implode('<br>', array_slice($errors, 0, 10)))
                ->withInput();
        }
        
        return redirect()->route('products.index')
            ->with('success', $message);
    }
}