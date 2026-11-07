<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'audits';

    protected $fillable = ['tenant_id', 'factory_id', 'number', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
