<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'payments';

    protected $fillable = ['tenant_id', 'invoice_id', 'number', 'amount', 'currency', 'paid_at'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'paid_at' => 'datetime',
        ];
    }

}
