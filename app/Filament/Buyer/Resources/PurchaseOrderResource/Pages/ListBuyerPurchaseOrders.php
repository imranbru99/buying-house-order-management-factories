<?php

namespace App\Filament\Buyer\Resources\PurchaseOrderResource\Pages;

use App\Filament\Buyer\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuyerPurchaseOrders extends ListRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
