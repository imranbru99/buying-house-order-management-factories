<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class OrderSize extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'order_sizes';

    protected $fillable = ['tenant_id', 'order_color_id', 'style_size_id', 'quantity'];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

}
