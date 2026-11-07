<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = ['tenant_id', 'department_id', 'user_id', 'code', 'name', 'email', 'phone', 'job_title', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
