<?php

namespace App\Filament\App\Resources\FactoryWorkOrderResource\Pages;

use App\Filament\App\Resources\FactoryWorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFactoryWorkOrder extends EditRecord
{
    protected static string $resource = FactoryWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
