<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'user_id',
        'type',
        'qty',
        'before_qty',
        'after_qty',
        'reference',
        'notes',
    ];

    protected $casts = [
        'qty'        => 'integer',
        'before_qty' => 'integer',
        'after_qty'  => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'stock_in'    => 'Stock In',
            'stock_out'   => 'Stock Out',
            'adjustment'  => 'Adjustment',
            'sale'        => 'Sale',
            'return'      => 'Return',
            default       => ucfirst($this->type),
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'stock_in'  => 'bg-green-100 text-green-700',
            'stock_out' => 'bg-red-100 text-red-700',
            'adjustment'=> 'bg-blue-100 text-blue-700',
            'sale'      => 'bg-yellow-100 text-yellow-700',
            'return'    => 'bg-purple-100 text-purple-700',
            default     => 'bg-gray-100 text-gray-600',
        };
    }
}