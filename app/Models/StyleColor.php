<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class StyleColor extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'style_colors';

    protected $fillable = ['tenant_id', 'style_id', 'code', 'name', 'hex'];

    protected function casts(): array
    {
        return [];
    }

}
