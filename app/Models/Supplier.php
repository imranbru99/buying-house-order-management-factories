<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = ['tenant_id', 'code', 'name', 'country', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
