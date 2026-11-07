<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sample extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'samples';

    protected $fillable = ['tenant_id', 'style_id', 'buyer_id', 'factory_id', 'number', 'sample_type', 'status', 'due_date', 'quantity'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }

    public function style(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }
}
