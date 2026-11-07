<?php

namespace App\Filament\App\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\CostingSheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CostingSheetResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = CostingSheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Pre-Order';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\Select::make('style_id')->relationship('style', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('version'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('total_cost')
                ->numeric()
                ->disabled()
                ->dehydrated(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('profit_margin')->numeric(),
            Forms\Components\TextInput::make('commission')->numeric(),
            Forms\Components\TextInput::make('costing_type'),
            Forms\Components\DateTimePicker::make('approved_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('total_cost'),
            Tables\Columns\TextColumn::make('currency'),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            CostingSheetResource\RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => CostingSheetResource\Pages\ListCostingSheets::route('/'),
            'create' => CostingSheetResource\Pages\CreateCostingSheet::route('/create'),
            'edit' => CostingSheetResource\Pages\EditCostingSheet::route('/{record}/edit'),
        ];
    }
}
