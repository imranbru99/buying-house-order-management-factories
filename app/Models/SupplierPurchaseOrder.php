<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPurchaseOrder extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'supplier_purchase_orders';

    protected $fillable = ['tenant_id', 'supplier_id', 'number', 'status', 'total_amount'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:4',
        ];
    }

}
