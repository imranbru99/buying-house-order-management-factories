<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantUsageLog extends Model
{
    protected $fillable = [
        'tenant_id',
        'metric',
        'quantity',
        'recorded_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'recorded_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
