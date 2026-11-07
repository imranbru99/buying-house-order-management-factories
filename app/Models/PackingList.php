<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackingList extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'packing_lists';

    protected $fillable = ['tenant_id', 'shipment_id', 'number'];

    protected function casts(): array
    {
        return [];
    }

}
