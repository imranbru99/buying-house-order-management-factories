<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillOfMaterial extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'bills_of_materials';

    protected $fillable = ['tenant_id', 'style_id', 'version', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
