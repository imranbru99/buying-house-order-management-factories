<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class ShipmentDocument extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'shipment_documents';

    protected $fillable = ['tenant_id', 'shipment_id', 'document_type', 'file_path'];

    protected function casts(): array
    {
        return [];
    }

}
