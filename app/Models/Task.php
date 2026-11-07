<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = ['tenant_id', 'title', 'status', 'assigned_to_user_id', 'due_date'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

}
