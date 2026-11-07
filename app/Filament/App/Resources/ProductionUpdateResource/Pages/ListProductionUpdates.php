<?php

namespace App\Filament\App\Resources\ProductionUpdateResource\Pages;

use App\Filament\App\Resources\ProductionUpdateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionUpdates extends ListRecords
{
    protected static string $resource = ProductionUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
