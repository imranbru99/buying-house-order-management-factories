<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\BuyerPurchaseOrderResource;
use App\Models\BuyerPurchaseOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent purchase orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BuyerPurchaseOrder::query()->latest('order_date')->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable(),
                Tables\Columns\TextColumn::make('buyer.name')->label('Buyer'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('order_date')->date(),
                Tables\Columns\TextColumn::make('total_amount')->money(fn ($record) => $record->currency ?? 'USD'),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (BuyerPurchaseOrder $record): string => BuyerPurchaseOrderResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ])
            ->paginated(false);
    }
}
