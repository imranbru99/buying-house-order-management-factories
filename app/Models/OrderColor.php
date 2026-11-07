<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class OrderColor extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'order_colors';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_item_id', 'style_color_id', 'quantity'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

}
