<?php

namespace App\Filament\App\Resources\FactoryAllocationResource\Pages;

use App\Filament\App\Resources\FactoryAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFactoryAllocations extends ListRecords
{
    protected static string $resource = FactoryAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
