<?php

namespace App\Events;

use App\Models\FactoryAllocation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllocationCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public FactoryAllocation $allocation) {}
}
