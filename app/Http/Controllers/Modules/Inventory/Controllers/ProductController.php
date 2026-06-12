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
            ->where('branch_id', auth()->user()->branch_id)
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

        Product::create([
            'tenant_id'       => auth()->user()->tenant_id,
            'branch_id'       => auth()->user()->branch_id,
            'category_id'     => $request->category_id,
            'name'            => $request->name,
            'sku'             => $request->sku ?? 'SKU-' . strtoupper(Str::random(8)),
            'barcode'         => $request->barcode,
            'price'           => $request->price,
            'cost_price'      => $request->cost_price ?? 0,
            'tax_rate'        => $request->tax_rate ?? 0,
            'stock_qty'       => $request->stock_qty ?? 0,
            'low_stock_alert' => $request->low_stock_alert ?? 5,
            'track_stock'     => $request->boolean('track_stock', true),
            'description'     => $request->description,
            'status'          => $request->status,
        ]);

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
            'stock_qty'       => $request->stock_qty ?? 0,
            'low_stock_alert' => $request->low_stock_alert ?? 5,
            'track_stock'     => $request->boolean('track_stock', true),
            'description'     => $request->description,
            'status'          => $request->status,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function importForm()
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)->get();
        return view('modules.inventory.products.import', compact('categories'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        
        $header = fgetcsv($handle);
        
        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'The CSV file appears to be empty.');
        }
        
        $header = array_map(function($col) {
            return strtolower(trim($col));
        }, $header);
        
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
            
            if (empty(array_filter($row))) {
                continue;
            }
            
            $row = array_pad($row, count($header), null);
            $data = array_combine($header, $row);
            
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
            
            $categoryId = null;
            $categoryName = trim($data['category'] ?? '');
            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    ['tenant_id' => $tenantId, 'name' => $categoryName],
                    ['status' => 'active', 'slug' => Str::slug($categoryName)]
                );
                $categoryId = $category->id;
            }
            
            $sku = trim($data['sku'] ?? '');
            if (!empty($sku)) {
                $existingSku = Product::where('tenant_id', $tenantId)
                    ->where('branch_id', $branchId)
                    ->where('sku', $sku)
                    ->exists();
                if ($existingSku) {
                    $skipped++;
                    $errors[] = "Row {$rowNumber}: SKU '{$sku}' already exists for '{$name}'";
                    continue;
                }
            }
            
            if (empty($sku)) {
                $sku = 'SKU-' . strtoupper(Str::random(8));
            }
            
            try {
                Product::create([
                    'tenant_id'       => $tenantId,
                    'branch_id'       => $branchId,
                    'name'            => $name,
                    'sku'             => $sku,
                    'barcode'         => trim($data['barcode'] ?? '') ?: null,
                    'category_id'     => $categoryId,
                    'price'           => floatval($price),
                    'cost_price'      => floatval($data['cost_price'] ?? 0),
                    'tax_rate'        => floatval($data['tax_rate'] ?? 0),
                    'stock_qty'       => intval($data['stock_qty'] ?? 0),
                    'low_stock_alert' => intval($data['low_stock_alert'] ?? 5),
                    'track_stock'     => true,
                    'status'          => 'active',
                ]);
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row {$rowNumber}: Failed to import '{$name}' - " . $e->getMessage();
            }
        }
        
        fclose($handle);
        
        $message = "{$imported} product(s) imported successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) skipped.";
        }
        
        if (!empty($errors) && $imported === 0) {
            return redirect()->back()
                ->with('error', implode('<br>', array_slice($errors, 0, 10)))
                ->withInput();
        }
        
        return redirect()->route('products.index')
            ->with('success', $message);
    }
}