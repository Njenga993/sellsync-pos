<?php

namespace App\Http\Controllers\Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        $products = Product::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->with('category')
            ->get();

        $customers = Customer::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->get();

        return view('modules.sales.pos', compact('categories', 'products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => ['required', 'array', 'min:1'],
            'items.*.id'     => ['required', 'exists:products,id'],
            'items.*.qty'    => ['required', 'integer', 'min:1'],
            'items.*.price'  => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string'],
            'amount_paid'    => ['required', 'numeric', 'min:0'],
        ]);

        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;
        $subtotal = 0;
        $taxTotal = 0;
        $items    = [];

        foreach ($request->items as $item) {
            $product   = Product::findOrFail($item['id']);
            $qty       = $item['qty'];
            $unitPrice = $item['price'];
            $lineTotal = round($unitPrice * $qty, 2);

            // Tax inclusive: extract tax from price
            $taxRate   = $product->tax_rate ?? 0;
            $taxAmount = $taxRate > 0
                ? round($lineTotal - ($lineTotal / (1 + $taxRate / 100)), 2)
                : 0;

            $subtotal += $lineTotal;
            $taxTotal += $taxAmount;

            $items[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'qty'          => $qty,
                'unit_price'   => $unitPrice,
                'cost_price'   => $product->cost_price,
                'tax_amount'   => $taxAmount,
                'discount'     => 0,
                'subtotal'     => $lineTotal,
            ];

            // Deduct stock from branch pivot table
            if ($product->track_stock) {
                $beforeQty = $product->getStockForBranch($branchId);
                $afterQty = max(0, $beforeQty - $qty);
                
                $product->branches()->syncWithoutDetaching([
                    $branchId => [
                        'stock_qty' => $afterQty,
                    ]
                ]);

                StockMovement::create([
                    'tenant_id'  => $tenantId,
                    'branch_id'  => $branchId,
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(),
                    'type'       => 'sale',
                    'qty'        => -$qty,
                    'before_qty' => $beforeQty,
                    'after_qty'  => $afterQty,
                    'reference'  => 'POS Sale',
                    'notes'      => 'Auto-recorded from POS',
                ]);
            }
        }

        $discount = $request->discount ?? 0;
        $total    = round($subtotal - $discount, 2);
        $paid     = round($request->amount_paid, 2);

        // Final payment validation on server side
        if ($paid < $total) {
            return response()->json([
                'success' => false,
                'message' => 'Amount paid (KES ' . number_format($paid, 2) . ') is less than total (KES ' . number_format($total, 2) . ').',
            ], 422);
        }

        $change = round($paid - $total, 2);

        // Handle split payment label
        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'split' && $request->split_payments) {
            $splits = $request->split_payments;
            $parts  = [];
            if (!empty($splits['cash'])   && $splits['cash']   > 0) $parts[] = 'Cash';
            if (!empty($splits['card'])   && $splits['card']   > 0) $parts[] = 'Card';
            if (!empty($splits['mobile']) && $splits['mobile'] > 0) $parts[] = 'M-Pesa';
            $paymentMethod = implode(' + ', $parts);
        }

        $sale = Sale::create([
            'tenant_id'       => $tenantId,
            'branch_id'       => $branchId,
            'user_id'         => auth()->id(),
            'customer_id'     => $request->customer_id ?? null,
            'invoice_no'      => Sale::generateInvoiceNo($tenantId),
            'subtotal'        => $subtotal,
            'tax_amount'      => $taxTotal,
            'discount_amount' => $discount,
            'total'           => $total,
            'amount_paid'     => $paid,
            'change_amount'   => max(0, $change),
            'payment_method'  => $paymentMethod,
            'payment_status'  => 'paid',
            'status'          => 'completed',
        ]);

        foreach ($items as $item) {
            $sale->items()->create($item);
        }

        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            if ($customer) {
                $points = floor($total / 100);
                $customer->increment('loyalty_points', $points);
                $customer->increment('total_spent', $total);
            }
        }

        return response()->json([
            'success'    => true,
            'invoice_no' => $sale->invoice_no,
            'total'      => $total,
            'change'     => max(0, $change),
            'sale_id'    => $sale->id,
        ]);
    }

    public function receipt(Sale $sale)
    {
        $sale->load('items', 'customer', 'user', 'branch');
        return view('modules.sales.receipt', compact('sale'));
    }
}