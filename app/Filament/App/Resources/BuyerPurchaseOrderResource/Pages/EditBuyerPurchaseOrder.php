<?php

namespace App\Filament\App\Resources\BuyerPurchaseOrderResource\Pages;

use App\Filament\App\Resources\BuyerPurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyerPurchaseOrder extends EditRecord
{
    protected static string $resource = BuyerPurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
