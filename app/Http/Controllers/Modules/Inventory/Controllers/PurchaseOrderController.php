<?php

namespace App\Http\Controllers\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::where('tenant_id', auth()->user()->tenant_id)
            ->with('supplier', 'user')
            ->withCount('items')
            ->latest()
            ->paginate(20);

        return view('modules.inventory.purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')->get();
        $products  = Product::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')->get();

        return view('modules.inventory.purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => ['required', 'exists:suppliers,id'],
            'order_date'           => ['required', 'date'],
            'expected_date'        => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.qty_ordered'  => ['required', 'integer', 'min:1'],
            'items.*.unit_cost'    => ['required', 'numeric', 'min:0'],
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['qty_ordered'] * $item['unit_cost'];
        }

        $order = PurchaseOrder::create([
            'tenant_id'     => auth()->user()->tenant_id,
            'supplier_id'   => $request->supplier_id,
            'user_id'       => auth()->id(),
            'po_number'     => PurchaseOrder::generatePoNumber(auth()->user()->tenant_id),
            'status'        => 'ordered',
            'order_date'    => $request->order_date,
            'expected_date' => $request->expected_date,
            'subtotal'      => $subtotal,
            'tax_amount'    => 0,
            'total'         => $subtotal,
            'notes'         => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $product  = Product::find($item['product_id']);
            $subtotal = $item['qty_ordered'] * $item['unit_cost'];

            $order->items()->create([
                'product_id'   => $item['product_id'],
                'product_name' => $product->name,
                'qty_ordered'  => $item['qty_ordered'],
                'qty_received' => 0,
                'unit_cost'    => $item['unit_cost'],
                'subtotal'     => $subtotal,
            ]);
        }

        return redirect()->route('purchase-orders.show', $order)
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'user', 'items.product');
        return view('modules.inventory.purchase_orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'items'               => ['required', 'array'],
            'items.*.id'          => ['required', 'exists:purchase_order_items,id'],
            'items.*.qty_received'=> ['required', 'integer', 'min:0'],
        ]);

        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;

        foreach ($request->items as $itemData) {
            $item      = PurchaseOrderItem::findOrFail($itemData['id']);
            $qtyToAdd  = (int) $itemData['qty_received'];

            if ($qtyToAdd <= 0) continue;

            $item->update(['qty_received' => $item->qty_received + $qtyToAdd]);

            $product   = $item->product;
            $beforeQty = $product->getStockForBranch($branchId);
            $afterQty  = $beforeQty + $qtyToAdd;

            $product->branches()->syncWithoutDetaching([
                $branchId => ['stock_qty' => $afterQty]
            ]);

            StockMovement::create([
                'tenant_id'  => $tenantId,
                'branch_id'  => $branchId,
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'stock_in',
                'qty'        => $qtyToAdd,
                'before_qty' => $beforeQty,
                'after_qty'  => $afterQty,
                'reference'  => $purchaseOrder->po_number,
                'notes'      => 'Received from PO: ' . $purchaseOrder->po_number,
            ]);
        }

        $allReceived = $purchaseOrder->items->every(
            fn($i) => $i->fresh()->qty_received >= $i->qty_ordered
        );

        $purchaseOrder->update([
            'status'        => $allReceived ? 'received' : 'partial',
            'received_date' => $allReceived ? now() : null,
        ]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', $allReceived ? 'All items received. PO complete!' : 'Partial receipt recorded.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Only draft orders can be deleted.');
        }

        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order deleted.');
    }
}