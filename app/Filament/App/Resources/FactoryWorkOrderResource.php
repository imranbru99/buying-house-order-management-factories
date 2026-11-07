<?php

namespace App\Filament\App\Resources;

use App\Models\FactoryWorkOrder;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FactoryWorkOrderResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = FactoryWorkOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Production';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\Select::make('factory_id')->relationship('factory', 'name')->searchable(),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('quantity')->numeric(),
            Forms\Components\DatePicker::make('delivery_date'),
            Forms\Components\Textarea::make('factory_comments')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('factory.name'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('delivery_date')->date(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => FactoryWorkOrderResource\Pages\ListFactoryWorkOrders::route('/'),
            'create' => FactoryWorkOrderResource\Pages\CreateFactoryWorkOrder::route('/create'),
            'edit' => FactoryWorkOrderResource\Pages\EditFactoryWorkOrder::route('/{record}/edit'),];
    }
}
