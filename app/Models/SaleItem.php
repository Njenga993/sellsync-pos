<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'qty',
        'unit_price',
        'cost_price',
        'discount',
        'tax_amount',
        'subtotal',
    ];

    protected $casts = [
        'qty'        => 'integer',
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount'   => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getProfit(): float
    {
        return ($this->unit_price - $this->cost_price) * $this->qty;
    }
}