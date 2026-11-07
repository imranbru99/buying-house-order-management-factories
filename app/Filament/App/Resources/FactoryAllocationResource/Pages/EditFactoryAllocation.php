<?php

namespace App\Filament\App\Resources\FactoryAllocationResource\Pages;

use App\Filament\App\Resources\FactoryAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFactoryAllocation extends EditRecord
{
    protected static string $resource = FactoryAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
