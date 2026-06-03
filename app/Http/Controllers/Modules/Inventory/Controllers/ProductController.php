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

        Product::create([
            'tenant_id'       => auth()->user()->tenant_id,
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
}