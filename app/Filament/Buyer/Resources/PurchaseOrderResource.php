<?php

namespace App\Filament\Buyer\Resources;

use App\Models\BuyerPurchaseOrder;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = BuyerPurchaseOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Orders';

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DatePicker::make('order_date'),
            Forms\Components\TextInput::make('total_amount')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('order_date')->date(),
            Tables\Columns\TextColumn::make('total_amount'),
        ])->actions([Tables\Actions\ViewAction::make()])->bulkActions([[]]);
    }

    public static function getPages(): array
    {
        return ['index' => PurchaseOrderResource\Pages\ListBuyerPurchaseOrders::route('/'),
            'view' => PurchaseOrderResource\Pages\ViewBuyerPurchaseOrder::route('/{record}'),];
    }
}
