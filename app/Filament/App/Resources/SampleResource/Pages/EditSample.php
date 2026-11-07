<?php

namespace App\Filament\App\Resources\SampleResource\Pages;

use App\Filament\App\Resources\SampleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSample extends EditRecord
{
    protected static string $resource = SampleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
