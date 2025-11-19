<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'brand',
        'category',
        'description',
        'dosage_form',
        'strength',
        'unit_price',
        'stock_quantity',
        'minimum_stock_level',
        'expiry_date',
        'batch_number',
        'manufacturer',
        'requires_prescription',
        'side_effects',
        'contraindications',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'side_effects' => 'array',
        'contraindications' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function inventoryAlerts()
    {
        return $this->hasMany(InventoryAlert::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= minimum_stock_level');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', 0);
    }

    // Helper methods
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->minimum_stock_level;
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date <= now()->addDays($days);
    }

    public function isOutOfStock()
    {
        return $this->stock_quantity == 0;
    }

    public function updateStock($quantity, $operation = 'subtract')
    {
        if ($operation === 'subtract') {
            $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
        } else {
            $this->stock_quantity += $quantity;
        }
        
        $this->save();
        
        // Check for alerts
        $this->checkInventoryAlerts();
    }

    private function checkInventoryAlerts()
    {
        if ($this->isOutOfStock()) {
            InventoryAlert::create([
                'medicine_id' => $this->id,
                'alert_type' => 'out_of_stock',
                'message' => "Medicine '{$this->name}' is out of stock",
            ]);
        } elseif ($this->isLowStock()) {
            InventoryAlert::create([
                'medicine_id' => $this->id,
                'alert_type' => 'low_stock',
                'message' => "Medicine '{$this->name}' is running low (Current: {$this->stock_quantity})",
            ]);
        }
    }
}