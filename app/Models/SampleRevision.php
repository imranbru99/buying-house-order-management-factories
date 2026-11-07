<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class SampleRevision extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'sample_revisions';

    protected $fillable = ['tenant_id', 'sample_id', 'revision_no', 'status', 'notes'];

    protected function casts(): array
    {
        return [
            'revision_no' => 'integer',
        ];
    }

}
