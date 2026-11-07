<?php

namespace App\Filament\App\Resources\TnaMilestoneResource\Pages;

use App\Filament\App\Resources\TnaMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTnaMilestones extends ListRecords
{
    protected static string $resource = TnaMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
