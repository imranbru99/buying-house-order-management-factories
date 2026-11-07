<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'branches';

    protected $fillable = ['tenant_id', 'company_id', 'code', 'name', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
