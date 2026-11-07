<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'product_categories';

    protected $fillable = ['tenant_id', 'code', 'name', 'parent_id'];

    protected function casts(): array
    {
        return [];
    }

}
