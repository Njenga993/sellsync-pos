<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'logo_path',
        'receipt_header',
        'receipt_footer',
        'receipt_show_logo',
        'receipt_show_address',
        'receipt_show_phone',
        'receipt_show_email',
        'receipt_show_tax',
        'receipt_show_loyalty',
        'currency_symbol',
        'currency_code',
        'tax_name',
        'tax_number',
        'address',
        'city',
        'phone',
        'email',
        'website',
    ];

    protected $casts = [
        'receipt_show_logo'    => 'boolean',
        'receipt_show_address' => 'boolean',
        'receipt_show_phone'   => 'boolean',
        'receipt_show_email'   => 'boolean',
        'receipt_show_tax'     => 'boolean',
        'receipt_show_loyalty' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForTenant(string $tenantId): self
    {
        return self::firstOrCreate(
            ['tenant_id' => $tenantId],
            [
                'receipt_header'       => 'Thank you for shopping with us!',
                'receipt_footer'       => 'Powered by POS System',
                'receipt_show_logo'    => true,
                'receipt_show_address' => true,
                'receipt_show_phone'   => true,
                'receipt_show_email'   => true,
                'receipt_show_tax'     => true,
                'receipt_show_loyalty' => true,
                'currency_symbol'      => 'KES',
                'currency_code'        => 'KES',
                'tax_name'             => 'VAT',
            ]
        );
    }
}