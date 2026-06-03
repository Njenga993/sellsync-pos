<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_return_id',
        'sale_item_id',
        'product_id',
        'product_name',
        'qty',
        'unit_price',
        'subtotal',
        'restock',
    ];

    protected $casts = [
        'qty'        => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'restock'    => 'boolean',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
}