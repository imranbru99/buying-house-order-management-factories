<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class CostingItem extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'costing_items';

    protected $fillable = ['tenant_id', 'costing_sheet_id', 'category', 'description', 'quantity', 'unit_cost', 'line_total'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:4',
            'line_total' => 'decimal:4',
        ];
    }

    public function sheet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CostingSheet::class, 'costing_sheet_id');
    }
}
