<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class TnaMilestone extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'tna_milestones';

    protected $fillable = ['tenant_id', 'tna_template_id', 'code', 'name', 'offset_days', 'sort_order'];

    protected function casts(): array
    {
        return [
            'offset_days' => 'integer',
            'sort_order' => 'integer',
        ];
    }

}
