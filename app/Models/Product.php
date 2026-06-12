<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'sku',
        'barcode',
        'description',
        'price',
        'cost_price',
        'tax_rate',
        'track_stock',
        'status',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'cost_price'  => 'decimal:2',
        'tax_rate'    => 'decimal:2',
        'track_stock' => 'boolean',
    ];

    // ── Accessors for backward compatibility ──
    
    /**
     * Get stock for the currently active branch
     */
    public function getStockQtyAttribute(): int
    {
        $branchId = auth()->check() ? auth()->user()->branch_id : null;
        
        if (!$branchId || !$this->track_stock) {
            return 0;
        }

        return $this->branches()
            ->where('branch_id', $branchId)
            ->first()?->pivot?->stock_qty ?? 0;
    }

    /**
     * Get low stock alert for the currently active branch
     */
    public function getLowStockAlertAttribute(): int
    {
        $branchId = auth()->check() ? auth()->user()->branch_id : null;
        
        if (!$branchId) {
            return 5;
        }

        return $this->branches()
            ->where('branch_id', $branchId)
            ->first()?->pivot?->low_stock_alert ?? 5;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'cost_price', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Product {$eventName}: {$this->name}");
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * All branches with their stock levels
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_product')
            ->withPivot(['stock_qty', 'low_stock_alert'])
            ->withTimestamps();
    }

    /**
     * Get stock for a specific branch
     */
    public function getStockForBranch($branchId): int
    {
        return $this->branches()
            ->where('branch_id', $branchId)
            ->first()?->pivot?->stock_qty ?? 0;
    }

    /**
     * Check if stock is low for the active branch
     */
    public function isLowStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }

        $branchId = auth()->check() ? auth()->user()->branch_id : null;
        
        if (!$branchId) {
            return false;
        }

        $pivot = $this->branches()
            ->where('branch_id', $branchId)
            ->first()?->pivot;

        if (!$pivot) {
            return false;
        }

        return $pivot->stock_qty <= $pivot->low_stock_alert;
    }

    /**
     * Check if out of stock for the active branch
     */
    public function isOutOfStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }

        $branchId = auth()->check() ? auth()->user()->branch_id : null;
        
        if (!$branchId) {
            return false;
        }

        return $this->getStockForBranch($branchId) <= 0;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
 * Update stock for a specific branch
 */
public function updateStockForBranch($branchId, int $qty, ?int $lowStockAlert = null): void
{
    $data = ['stock_qty' => $qty];
    if ($lowStockAlert !== null) {
        $data['low_stock_alert'] = $lowStockAlert;
    }
    
    $this->branches()->syncWithoutDetaching([
        $branchId => $data
    ]);
}

/**
 * Increment stock for a specific branch
 */
public function incrementStockForBranch($branchId, int $amount): void
{
    $current = $this->getStockForBranch($branchId);
    $this->updateStockForBranch($branchId, $current + $amount);
}

/**
 * Decrement stock for a specific branch
 */
public function decrementStockForBranch($branchId, int $amount): void
{
    $current = $this->getStockForBranch($branchId);
    $this->updateStockForBranch($branchId, max(0, $current - $amount));
}
}