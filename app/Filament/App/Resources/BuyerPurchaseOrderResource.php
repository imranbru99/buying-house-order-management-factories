<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BuyerPurchaseOrderResource\Pages;
use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\BuyerPurchaseOrder;
use App\Services\Documents\DocumentPdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BuyerPurchaseOrderResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = BuyerPurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('internal_number'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DatePicker::make('order_date'),
            Forms\Components\DatePicker::make('ex_factory_date'),
            Forms\Components\DatePicker::make('delivery_date'),
            Forms\Components\TextInput::make('total_amount')->numeric(),
            Forms\Components\TextInput::make('total_quantity')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('payment_terms'),
            Forms\Components\TextInput::make('incoterms'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('buyer.name')->label('Buyer'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('order_date')->date(),
                Tables\Columns\TextColumn::make('total_amount')->money(fn ($record) => $record->currency ?? 'USD'),
            ])
            ->actions([
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (BuyerPurchaseOrder $record, DocumentPdfService $pdfs) {
                        $binary = $pdfs->purchaseOrderPdf($record);

                        return response()->streamDownload(
                            fn () => print($binary),
                            'purchase-order-'.$record->number.'.pdf',
                            ['Content-Type' => 'application/pdf'],
                        );
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BuyerPurchaseOrderResource\RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuyerPurchaseOrders::route('/'),
            'create' => Pages\CreateBuyerPurchaseOrder::route('/create'),
            'edit' => Pages\EditBuyerPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
