<?php

namespace App\Models;

use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTnaSchedule extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $table = 'order_tna_schedules';

    protected $fillable = [
        'tenant_id',
        'buyer_purchase_order_id',
        'factory_work_order_id',
        'tna_milestone_id',
        'name',
        'responsible_user_id',
        'planned_date',
        'actual_date',
        'completion_percent',
        'status',
        'is_critical',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date',
            'actual_date' => 'date',
            'completion_percent' => 'decimal:2',
            'is_critical' => 'boolean',
        ];
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
