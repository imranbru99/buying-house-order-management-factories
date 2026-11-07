<?php

namespace App\Filament\App\Resources\CostingSheetResource\Pages;

use App\Filament\App\Resources\CostingSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCostingSheet extends EditRecord
{
    protected static string $resource = CostingSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
