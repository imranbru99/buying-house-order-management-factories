<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialInvoice extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'commercial_invoices';

    protected $fillable = ['tenant_id', 'shipment_id', 'number', 'total_amount', 'currency'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:4',
        ];
    }

}
