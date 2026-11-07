<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;

class LcAmendment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'lc_amendments';

    protected $fillable = ['tenant_id', 'letter_of_credit_id', 'amendment_no', 'description'];

    protected function casts(): array
    {
        return [
            'amendment_no' => 'integer',
        ];
    }

}
