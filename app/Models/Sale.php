<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'customer_id',
        'invoice_no',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'amount_paid',
        'change_amount',
        'payment_method',
        'payment_status',
        'notes',
        'status',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total'           => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'change_amount'   => 'decimal:2',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoiceNo(string $tenantId): string
    {
        $last = self::where('tenant_id', $tenantId)->latest()->first();
        $number = $last ? (intval(substr($last->invoice_no, -6)) + 1) : 1;
        return 'INV-' . strtoupper(substr($tenantId, 0, 4)) . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}