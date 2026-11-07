<?php

namespace App\Filament\App\Resources\InspectionResource\Pages;

use App\Filament\App\Resources\InspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspection extends EditRecord
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
