<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'inquiries';

    protected $fillable = ['tenant_id', 'buyer_id', 'number', 'status', 'inquiry_date'];

    protected function casts(): array
    {
        return [
            'inquiry_date' => 'date',
        ];
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
}
