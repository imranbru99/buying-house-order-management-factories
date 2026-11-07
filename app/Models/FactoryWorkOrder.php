<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryWorkOrder extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'factory_work_orders';

    protected $fillable = ['tenant_id', 'factory_allocation_id', 'factory_id', 'buyer_purchase_order_id', 'number', 'status', 'quantity', 'delivery_date', 'acknowledged_at', 'accepted_at', 'rejected_at', 'factory_comments'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'delivery_date' => 'date',
            'acknowledged_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
}
