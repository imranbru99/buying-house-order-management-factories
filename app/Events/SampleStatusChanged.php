<?php

namespace App\Events;

use App\Models\Sample;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SampleStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Sample $sample,
        public string $fromStatus,
        public string $toStatus,
    ) {}
}
