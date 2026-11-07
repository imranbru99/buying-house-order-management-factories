<?php

namespace App\Filament\Buyer\Resources;

use App\Models\Shipment;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Shipment::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Logistics';

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DatePicker::make('eta'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('eta')->date(),
        ])->actions([Tables\Actions\ViewAction::make()])->bulkActions([[]]);
    }

    public static function getPages(): array
    {
        return ['index' => ShipmentResource\Pages\ListShipments::route('/'),
            'view' => ShipmentResource\Pages\ViewShipment::route('/{record}'),];
    }
}
