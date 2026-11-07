<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'materials';

    protected $fillable = ['tenant_id', 'code', 'name', 'unit', 'on_hand'];

    protected function casts(): array
    {
        return [
            'on_hand' => 'decimal:2',
        ];
    }

}
