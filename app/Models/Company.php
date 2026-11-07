<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = ['tenant_id', 'code', 'name', 'legal_name', 'status', 'currency'];

    protected function casts(): array
    {
        return [];
    }

}
