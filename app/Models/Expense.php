<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = ['tenant_id', 'number', 'amount', 'currency', 'category'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
        ];
    }

}
