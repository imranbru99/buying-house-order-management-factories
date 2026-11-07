<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'buyers';

    protected $fillable = ['tenant_id', 'code', 'name', 'brand_name', 'country', 'payment_terms', 'credit_limit', 'currency', 'status'];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:4',
        ];
    }

}
