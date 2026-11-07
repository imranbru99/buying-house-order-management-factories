<?php

namespace App\Filament\Factory\Resources\WorkOrderResource\Pages;

use App\Filament\Factory\Resources\WorkOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListWorkOrders extends ListRecords
{
    protected static string $resource = WorkOrderResource::class;
}
