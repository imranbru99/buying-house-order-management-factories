<?php

namespace App\Filament\App\Resources\BuyerResource\Pages;

use App\Filament\App\Resources\BuyerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyer extends EditRecord
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
