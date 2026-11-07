<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'shipments';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_id', 'number', 'status', 'incoterm', 'shipping_method', 'etd', 'atd', 'eta', 'ata', 'bol_number'];

    protected function casts(): array
    {
        return [
            'etd' => 'date',
            'atd' => 'date',
            'eta' => 'date',
            'ata' => 'date',
        ];
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }
}
