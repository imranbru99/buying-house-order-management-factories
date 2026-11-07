<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ProductionUpdate extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'production_updates';

    protected $fillable = ['tenant_id', 'production_plan_id', 'completed_quantity', 'reported_at', 'notes'];

    protected function casts(): array
    {
        return [
            'completed_quantity' => 'decimal:2',
            'reported_at' => 'datetime',
        ];
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class, 'production_plan_id');
    }
}
