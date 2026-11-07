<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ActivityLog extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'activity_logs';

    protected $fillable = ['tenant_id', 'user_id', 'log_name', 'description'];

    protected function casts(): array
    {
        return [];
    }

}
