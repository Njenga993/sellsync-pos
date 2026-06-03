<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'user_id',
        'po_number',
        'status',
        'order_date',
        'expected_date',
        'received_date',
        'subtotal',
        'tax_amount',
        'total',
        'notes',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'subtotal'      => 'decimal:2',
        'tax_amount'    => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public static function generatePoNumber(string $tenantId): string
    {
        $last   = self::where('tenant_id', $tenantId)->latest()->first();
        $number = $last ? (intval(substr($last->po_number, -5)) + 1) : 1;
        return 'PO-' . now()->format('Ymd') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'bg-gray-100 text-gray-600',
            'ordered'  => 'bg-blue-100 text-blue-700',
            'partial'  => 'bg-yellow-100 text-yellow-700',
            'received' => 'bg-green-100 text-green-700',
            'cancelled'=> 'bg-red-100 text-red-700',
            default    => 'bg-gray-100 text-gray-600',
        };
    }
}