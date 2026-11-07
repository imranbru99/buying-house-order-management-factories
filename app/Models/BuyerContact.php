<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class BuyerContact extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'buyer_contacts';

    protected $fillable = ['tenant_id', 'buyer_id', 'name', 'email', 'phone', 'role', 'is_primary'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

}
