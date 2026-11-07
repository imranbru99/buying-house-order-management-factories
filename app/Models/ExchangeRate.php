<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ExchangeRate extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'exchange_rates';

    protected $fillable = ['tenant_id', 'base_currency', 'quote_currency', 'rate', 'effective_at'];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:8',
            'effective_at' => 'datetime',
        ];
    }

}
