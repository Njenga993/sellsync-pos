<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'sale_id',
        'return_number',
        'total_refund',
        'refund_method',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'total_refund' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public static function generateReturnNumber(string $tenantId): string
    {
        $last   = self::where('tenant_id', $tenantId)->latest()->first();
        $number = $last ? (intval(substr($last->return_number, -5)) + 1) : 1;
        return 'RET-' . now()->format('Ymd') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-700',
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-600',
        };
    }
}