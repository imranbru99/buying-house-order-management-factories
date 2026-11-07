<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ShipmentTrackingEvent extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'shipment_tracking_events';

    protected $fillable = ['tenant_id', 'shipment_id', 'status', 'occurred_at', 'description'];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

}
