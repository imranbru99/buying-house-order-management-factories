<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'commissions';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_id', 'amount', 'currency', 'status'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
        ];
    }

}
