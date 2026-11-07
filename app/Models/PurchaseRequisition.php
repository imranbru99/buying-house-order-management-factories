<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'purchase_requisitions';

    protected $fillable = ['tenant_id', 'number', 'status'];

    protected function casts(): array
    {
        return [];
    }

}
