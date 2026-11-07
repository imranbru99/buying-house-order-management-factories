<?php

namespace App\Filament\App\Resources\ProductionUpdateResource\Pages;

use App\Filament\App\Resources\ProductionUpdateResource;
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
