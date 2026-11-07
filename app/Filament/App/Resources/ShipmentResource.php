<?php

namespace App\Filament\App\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\BuyerPurchaseOrderItem;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Logistics';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_purchase_order_id')
                ->relationship('purchaseOrder', 'number')
                ->searchable()
                ->preload()
                ->live()
                ->required(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('status')->default('planned'),
            Forms\Components\TextInput::make('incoterm'),
            Forms\Components\TextInput::make('shipping_method'),
            Forms\Components\DatePicker::make('etd'),
            Forms\Components\DatePicker::make('eta'),
            Forms\Components\TextInput::make('bol_number'),
            Forms\Components\Repeater::make('items')
                ->schema([
                    Forms\Components\Select::make('buyer_purchase_order_item_id')
                        ->label('PO line')
                        ->options(function (Get $get): array {
                            $orderId = $get('../../buyer_purchase_order_id') ?? $get('buyer_purchase_order_id');

                            if (! $orderId) {
                                return [];
                            }

                            return BuyerPurchaseOrderItem::query()
                                ->where('buyer_purchase_order_id', $orderId)
                                ->get()
                                ->mapWithKeys(fn (BuyerPurchaseOrderItem $item) => [
                                    $item->id => 'Line '.$item->line_no.' (qty '.$item->quantity.')',
                                ])
                                ->all();
                        })
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('quantity')->numeric()->required(),
                ])
                ->minItems(1)
                ->columnSpanFull()
                ->visibleOn('create')
                ->dehydrated(fn (?string $operation): bool => $operation === 'create'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('etd')->date(),
            Tables\Columns\TextColumn::make('eta')->date(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ShipmentResource\Pages\ListShipments::route('/'),
            'create' => ShipmentResource\Pages\CreateShipment::route('/create'),
            'edit' => ShipmentResource\Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
