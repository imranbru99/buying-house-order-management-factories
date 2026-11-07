<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterOfCredit extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'letters_of_credit';

    protected $fillable = ['tenant_id', 'buyer_id', 'number', 'amount', 'lc_value', 'currency', 'status', 'expiry_date', 'latest_shipment_date'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'lc_value' => 'decimal:4',
            'expiry_date' => 'date',
            'latest_shipment_date' => 'date',
        ];
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
}
