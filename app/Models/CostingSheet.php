<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostingSheet extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'costing_sheets';

    protected $fillable = ['tenant_id', 'quotation_id', 'style_id', 'number', 'version', 'status', 'total_cost', 'currency', 'profit_margin', 'commission', 'costing_type', 'approved_at'];

    protected function casts(): array
    {
        return [
            'total_cost' => 'decimal:4',
            'profit_margin' => 'decimal:4',
            'commission' => 'decimal:4',
            'approved_at' => 'datetime',
        ];
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CostingItem::class);
    }

    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }
}
