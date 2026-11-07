<?php

namespace App\Events;

use App\Models\BuyerPurchaseOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(public BuyerPurchaseOrder $order) {}
}
