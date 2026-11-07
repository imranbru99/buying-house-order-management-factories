<?php

namespace App\Filament\App\Resources\InquiryResource\Pages;

use App\Filament\App\Resources\InquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInquirys extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
