<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class MaterialReceipt extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'material_receipts';

    protected $fillable = ['tenant_id', 'supplier_purchase_order_id', 'number', 'quantity', 'received_at'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'received_at' => 'datetime',
        ];
    }

}
