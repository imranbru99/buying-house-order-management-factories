<?php

namespace App\Models;

use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = ['tenant_id', 'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'ip_address'];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

}
