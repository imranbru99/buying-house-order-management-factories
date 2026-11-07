<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrectiveAction extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'corrective_actions';

    protected $fillable = ['tenant_id', 'inspection_defect_id', 'status', 'action_plan', 'due_date'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

}
