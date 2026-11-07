<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\ProductionUpdate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductionUpdateResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = ProductionUpdate::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Production';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\Select::make('production_plan_id')
                ->label('Production plan')
                ->relationship('plan', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => 'Plan #'.$record->id.' (WO '.($record->workOrder?->number ?? 'n/a').')')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('completed_quantity')->numeric()->required(),
            Forms\Components\DateTimePicker::make('reported_at')->disabled()->dehydrated(false),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('completed_quantity'),
            Tables\Columns\TextColumn::make('reported_at')->dateTime(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ProductionUpdateResource\Pages\ListProductionUpdates::route('/'),
            'create' => ProductionUpdateResource\Pages\CreateProductionUpdate::route('/create'),
            'edit' => ProductionUpdateResource\Pages\EditProductionUpdate::route('/{record}/edit'),
        ];
    }
}
