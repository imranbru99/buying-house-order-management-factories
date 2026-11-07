<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class Approval extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'approvals';

    protected $fillable = ['tenant_id', 'status', 'requested_by_user_id', 'approved_by_user_id', 'decided_at'];

    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

}
