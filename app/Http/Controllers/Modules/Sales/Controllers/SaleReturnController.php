<?php

namespace App\Http\Controllers\Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index()
    {
        $returns = SaleReturn::where('tenant_id', auth()->user()->tenant_id)
            ->with('sale', 'user')
            ->withCount('items')
            ->latest()
            ->paginate(20);

        return view('modules.sales.returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $sale = null;

        if ($request->invoice_no) {
            $sale = Sale::where('tenant_id', auth()->user()->tenant_id)
                ->where('invoice_no', $request->invoice_no)
                ->with('items.product', 'customer')
                ->first();
        }

        return view('modules.sales.returns.create', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id'       => ['required', 'exists:sales,id'],
            'reason'        => ['required', 'string', 'max:255'],
            'refund_method' => ['required', 'in:cash,card,mobile,credit'],
            'notes'         => ['nullable', 'string'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.qty'         => ['required', 'integer', 'min:0'],
            'items.*.restock'     => ['nullable', 'boolean'],
        ]);

        $tenantId    = auth()->user()->tenant_id;
        $branchId    = auth()->user()->branch_id;
        $sale        = Sale::findOrFail($request->sale_id);
        $totalRefund = 0;
        $returnItems = [];

        foreach ($request->items as $item) {
            if ((int)$item['qty'] <= 0) continue;

            $saleItem    = $sale->items()->findOrFail($item['sale_item_id']);
            $qty         = min((int)$item['qty'], $saleItem->qty);
            $subtotal    = round($saleItem->unit_price * $qty, 2);
            $totalRefund += $subtotal;

            $returnItems[] = [
                'sale_item_id' => $saleItem->id,
                'product_id'   => $saleItem->product_id,
                'product_name' => $saleItem->product_name,
                'qty'          => $qty,
                'unit_price'   => $saleItem->unit_price,
                'subtotal'     => $subtotal,
                'restock'      => isset($item['restock']) ? true : false,
            ];
        }

        if (empty($returnItems)) {
            return back()->with('error', 'No items selected for return.');
        }

        $return = SaleReturn::create([
            'tenant_id'     => $tenantId,
            'branch_id'     => $branchId,
            'user_id'       => auth()->id(),
            'sale_id'       => $sale->id,
            'return_number' => SaleReturn::generateReturnNumber($tenantId),
            'total_refund'  => $totalRefund,
            'refund_method' => $request->refund_method,
            'reason'        => $request->reason,
            'notes'         => $request->notes,
            'status'        => 'completed',
        ]);

        foreach ($returnItems as $item) {
            $return->items()->create($item);

            if ($item['restock']) {
                $product   = Product::find($item['product_id']);
                $beforeQty = $product->stock_qty;
                $product->increment('stock_qty', $item['qty']);

                StockMovement::create([
                    'tenant_id'  => $tenantId,
                    'branch_id'  => $branchId,
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(),
                    'type'       => 'return',
                    'qty'        => $item['qty'],
                    'before_qty' => $beforeQty,
                    'after_qty'  => $beforeQty + $item['qty'],
                    'reference'  => $return->return_number,
                    'notes'      => 'Restocked from return: ' . $return->return_number,
                ]);
            }
        }

        return redirect()->route('returns.show', $return)
            ->with('success', 'Return processed successfully. Refund: KES ' . number_format($totalRefund, 2));
    }

    public function show(SaleReturn $return)
    {
        $return->load('sale.customer', 'items', 'user');
        return view('modules.sales.returns.show', compact('return'));
    }
}