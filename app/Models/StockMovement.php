<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class StockMovement extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'stock_movements';

    protected $fillable = ['tenant_id', 'material_id', 'type', 'quantity', 'moved_at'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'moved_at' => 'datetime',
        ];
    }

}
