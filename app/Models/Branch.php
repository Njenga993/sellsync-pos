<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'city',
        'phone',
        'email',
        'is_main',
        'status',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Products with per-branch stock levels via pivot
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_product')
            ->withPivot(['stock_qty', 'low_stock_alert'])
            ->withTimestamps();
    }
}