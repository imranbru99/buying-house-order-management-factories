<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class InspectionDefect extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'inspection_defects';

    protected $fillable = ['tenant_id', 'inspection_id', 'defect_code', 'quantity', 'severity'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

}
