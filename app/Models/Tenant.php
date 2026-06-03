<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
//use Stancl\Tenancy\Contracts\TenantWithDatabase;
//use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant 
{
    use  HasDomains;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'business_type',
        'plan',
        'status',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'data' => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'business_type',
            'plan',
            'status',
            'trial_ends_at',
        ];
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}