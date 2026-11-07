<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ShipmentItem extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'shipment_items';

    protected $fillable = ['tenant_id', 'shipment_id', 'buyer_purchase_order_item_id', 'quantity'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

}
