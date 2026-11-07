<?php

namespace App\Filament\App\Resources\FactoryResource\Pages;

use App\Filament\App\Resources\FactoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFactorys extends ListRecords
{
    protected static string $resource = FactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
