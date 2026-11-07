<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionPlan extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'production_plans';

    protected $fillable = ['tenant_id', 'factory_work_order_id', 'status', 'plan_start', 'plan_end'];

    protected function casts(): array
    {
        return [
            'plan_start' => 'date',
            'plan_end' => 'date',
        ];
    }

    public function updates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductionUpdate::class);
    }

    public function workOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FactoryWorkOrder::class, 'factory_work_order_id');
    }
}
