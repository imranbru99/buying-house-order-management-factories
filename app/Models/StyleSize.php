<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class StyleSize extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'style_sizes';

    protected $fillable = ['tenant_id', 'style_id', 'code', 'name', 'sort_order'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

}
