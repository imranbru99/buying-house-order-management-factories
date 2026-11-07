<?php

namespace App\Filament\Buyer\Resources\SampleApprovalResource\Pages;

use App\Filament\Buyer\Resources\SampleApprovalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSamples extends ListRecords
{
    protected static string $resource = SampleApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
