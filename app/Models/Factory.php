<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'factories';

    protected $fillable = ['tenant_id', 'code', 'name', 'country', 'monthly_capacity', 'available_capacity', 'approval_status', 'quality_score', 'delivery_score', 'status'];

    protected function casts(): array
    {
        return [
            'monthly_capacity' => 'decimal:2',
            'available_capacity' => 'decimal:2',
            'quality_score' => 'decimal:2',
            'delivery_score' => 'decimal:2',
        ];
    }

}
