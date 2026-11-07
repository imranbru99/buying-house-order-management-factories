<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'quotations';

    protected $fillable = ['tenant_id', 'rfq_id', 'buyer_id', 'number', 'status', 'total_amount', 'currency'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:4',
        ];
    }

}
