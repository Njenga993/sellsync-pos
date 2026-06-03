<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'loyalty_points',
        'credit_limit',
        'total_spent',
        'status',
    ];

    protected $casts = [
        'loyalty_points' => 'integer',
        'credit_limit'   => 'decimal:2',
        'total_spent'    => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    public function redeemLoyaltyPoints(int $points): bool
    {
        if ($this->loyalty_points < $points) {
            return false;
        }
        $this->decrement('loyalty_points', $points);
        return true;
    }
}