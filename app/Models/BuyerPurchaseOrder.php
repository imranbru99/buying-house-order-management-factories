<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerPurchaseOrder extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'buyer_purchase_orders';

    protected $fillable = ['tenant_id', 'buyer_id', 'number', 'internal_number', 'status', 'order_date', 'ex_factory_date', 'delivery_date', 'total_amount', 'total_quantity', 'currency', 'payment_terms', 'incoterms', 'approved_at', 'confirmed_at'];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'ex_factory_date' => 'date',
            'delivery_date' => 'date',
            'total_amount' => 'decimal:4',
            'total_quantity' => 'decimal:2',
            'approved_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function getPoNumberAttribute(): ?string
    {
        return $this->attributes['number'] ?? null;
    }

    public function setPoNumberAttribute(?string $value): void
    {
        if ($value !== null) {
            $this->attributes['number'] = $value;
        }
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BuyerPurchaseOrderItem::class);
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function allocations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FactoryAllocation::class);
    }
}
