<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'product_name',
        'qty_ordered',
        'qty_received',
        'unit_cost',
        'subtotal',
    ];

    protected $casts = [
        'qty_ordered'  => 'integer',
        'qty_received' => 'integer',
        'unit_cost'    => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}