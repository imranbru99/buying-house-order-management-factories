<?php

namespace App\Filament\Supplier\Resources\SupplierPurchaseOrderResource\Pages;

use App\Filament\Supplier\Resources\SupplierPurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierPurchaseOrders extends ListRecords
{
    protected static string $resource = SupplierPurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
