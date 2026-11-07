<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryAllocation extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'factory_allocations';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_id', 'factory_id', 'style_id', 'allocated_quantity', 'delivery_date', 'status', 'approved_at'];

    protected function casts(): array
    {
        return [
            'allocated_quantity' => 'decimal:2',
            'delivery_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    public function workOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FactoryWorkOrder::class);
    }
}
