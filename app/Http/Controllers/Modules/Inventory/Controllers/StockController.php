<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;

        // Get products that track stock and have stock in this branch
        $products = Product::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->where('track_stock', true)
            ->with('category')
            ->paginate(20);

        // Count low stock and out of stock using the accessors
        $lowStockCount = 0;
        $outOfStockCount = 0;
        
        $allTracked = Product::where('tenant_id', $tenantId)
            ->where('track_stock', true)
            ->get();
            
        foreach ($allTracked as $p) {
            $stock = $p->getStockForBranch($branchId);
            $alert = $p->low_stock_alert;
            
            if ($stock <= 0) {
                $outOfStockCount++;
            } elseif ($stock <= $alert) {
                $lowStockCount++;
            }
        }

        $summary = [
            'total_products'  => Product::where('tenant_id', $tenantId)->where('track_stock', true)->count(),
            'low_stock'       => $lowStockCount,
            'out_of_stock'    => $outOfStockCount,
            'total_movements' => StockMovement::where('tenant_id', $tenantId)->count(),
        ];

        return view('modules.inventory.stock.index', compact('products', 'summary'));
    }

    public function history(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $movements = StockMovement::where('tenant_id', $tenantId)
            ->with('product', 'user')
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->type,       fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $products = Product::where('tenant_id', $tenantId)
            ->where('track_stock', true)
            ->get();

        return view('modules.inventory.stock.history', compact('movements', 'products'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:stock_in,stock_out,adjustment'],
            'qty'        => ['required', 'integer', 'min:1'],
            'notes'      => ['nullable', 'string'],
            'reference'  => ['nullable', 'string'],
        ]);

        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;
        $product  = Product::findOrFail($request->product_id);
        $beforeQty = $product->getStockForBranch($branchId);

        if ($request->type === 'stock_out' && $beforeQty < $request->qty) {
            return back()->with('error', 'Not enough stock. Current stock: ' . $beforeQty);
        }

        $newQty = match($request->type) {
            'stock_in'   => $beforeQty + $request->qty,
            'stock_out'  => $beforeQty - $request->qty,
            'adjustment' => $request->qty,
        };

        $moved = match($request->type) {
            'stock_in'   => $request->qty,
            'stock_out'  => -$request->qty,
            'adjustment' => $request->qty - $beforeQty,
        };

        // Update stock in pivot table for this branch
        $product->branches()->syncWithoutDetaching([
            $branchId => [
                'stock_qty' => $newQty,
            ]
        ]);

        StockMovement::create([
            'tenant_id'  => $tenantId,
            'branch_id'  => $branchId,
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'type'       => $request->type,
            'qty'        => $moved,
            'before_qty' => $beforeQty,
            'after_qty'  => $newQty,
            'reference'  => $request->reference,
            'notes'      => $request->notes,
        ]);

        return back()->with('success', 'Stock updated successfully.');
    }
}