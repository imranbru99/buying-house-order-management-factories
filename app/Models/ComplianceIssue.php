<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceIssue extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'compliance_issues';

    protected $fillable = ['tenant_id', 'audit_id', 'status', 'severity', 'description'];

    protected function casts(): array
    {
        return [];
    }

}
