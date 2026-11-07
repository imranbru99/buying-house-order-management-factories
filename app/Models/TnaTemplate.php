<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class TnaTemplate extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'tna_templates';

    protected $fillable = ['tenant_id', 'code', 'name'];

    protected function casts(): array
    {
        return [];
    }

}
