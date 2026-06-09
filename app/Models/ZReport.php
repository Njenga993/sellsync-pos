<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'report_date',
        'shift',
        'status',
        'opening_float',
        'total_cash_sales',
        'total_card_sales',
        'total_mobile_sales',
        'total_split_sales',
        'total_sales',
        'total_transactions',
        'total_refunds',
        'total_cash_refunds',
        'total_expenses',
        'total_cash_expenses',
        'expected_cash',
        'actual_cash',
        'cash_variance',
        'notes',
        'closed_at',
    ];

    protected $casts = [
        'report_date'         => 'date',
        'closed_at'           => 'datetime',
        'opening_float'       => 'decimal:2',
        'total_cash_sales'    => 'decimal:2',
        'total_card_sales'    => 'decimal:2',
        'total_mobile_sales'  => 'decimal:2',
        'total_split_sales'   => 'decimal:2',
        'total_sales'         => 'decimal:2',
        'total_transactions'  => 'integer',
        'total_refunds'       => 'decimal:2',
        'total_cash_refunds'  => 'decimal:2',
        'total_expenses'      => 'decimal:2',
        'total_cash_expenses' => 'decimal:2',
        'expected_cash'       => 'decimal:2',
        'actual_cash'         => 'decimal:2',
        'cash_variance'       => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getVarianceStatusAttribute(): string
    {
        if ($this->cash_variance == 0) return 'balanced';
        return $this->cash_variance > 0 ? 'over' : 'short';
    }

    public function getVarianceColorAttribute(): string
    {
        return match($this->variance_status) {
            'balanced' => 'text-green-600',
            'over'     => 'text-blue-600',
            'short'    => 'text-red-600',
        };
    }

    public function isLocked(): bool
    {
        return $this->status === 'closed';
    }
}