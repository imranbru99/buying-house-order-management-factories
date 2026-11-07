<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class BuyerPurchaseOrderItem extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'buyer_purchase_order_items';

    protected $fillable = ['tenant_id', 'buyer_purchase_order_id', 'style_id', 'line_no', 'quantity', 'unit_price', 'line_total'];

    protected function casts(): array
    {
        return [
            'line_no' => 'integer',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:4',
            'line_total' => 'decimal:4',
        ];
    }

    public function purchaseOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BuyerPurchaseOrder::class, 'buyer_purchase_order_id');
    }

    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }
}
