<?php

namespace App\Filament\App\Resources\TnaMilestoneResource\Pages;

use App\Filament\App\Resources\TnaMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTnaMilestone extends EditRecord
{
    protected static string $resource = TnaMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
