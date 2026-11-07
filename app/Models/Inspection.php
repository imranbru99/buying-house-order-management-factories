<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'inspections';

    protected $fillable = ['tenant_id', 'factory_work_order_id', 'number', 'type', 'status'];

    protected function casts(): array
    {
        return [];
    }

    public function defects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InspectionDefect::class);
    }
}
