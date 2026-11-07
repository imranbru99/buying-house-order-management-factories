<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class DeliverySchedule extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'delivery_schedules';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_id', 'delivery_date', 'quantity', 'status'];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }

}
