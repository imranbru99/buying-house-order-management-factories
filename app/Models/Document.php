<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'documents';

    protected $fillable = ['tenant_id', 'title', 'disk', 'path', 'mime_type', 'size'];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

}
