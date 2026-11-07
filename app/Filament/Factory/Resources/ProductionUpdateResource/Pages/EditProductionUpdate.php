<?php

namespace App\Filament\Factory\Resources\ProductionUpdateResource\Pages;

use App\Filament\Factory\Resources\ProductionUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductionUpdate extends EditRecord
{
    protected static string $resource = ProductionUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
