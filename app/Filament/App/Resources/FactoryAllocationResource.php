<?php

namespace App\Filament\App\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\FactoryAllocation;
use App\Services\Allocation\FactoryAllocationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use InvalidArgumentException;

class FactoryAllocationResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = FactoryAllocation::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Production';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_purchase_order_id')->relationship('purchaseOrder', 'number')->searchable()->preload(),
            Forms\Components\Select::make('factory_id')->relationship('factory', 'name')->searchable()->preload(),
            Forms\Components\Select::make('style_id')->relationship('style', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('allocated_quantity')->numeric(),
            Forms\Components\DatePicker::make('delivery_date'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DateTimePicker::make('approved_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('purchaseOrder.number')->label('PO'),
            Tables\Columns\TextColumn::make('factory.name'),
            Tables\Columns\TextColumn::make('allocated_quantity'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([
            Tables\Actions\Action::make('approveAllocation')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (FactoryAllocation $record): bool => $record->status !== 'approved')
                ->requiresConfirmation()
                ->action(function (FactoryAllocation $record, FactoryAllocationService $service): void {
                    try {
                        $service->approve($record);
                        Notification::make()->title('Allocation approved')->success()->send();
                    } catch (InvalidArgumentException $exception) {
                        Notification::make()->title($exception->getMessage())->danger()->send();
                    }
                }),
            Tables\Actions\EditAction::make(),
        ])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => FactoryAllocationResource\Pages\ListFactoryAllocations::route('/'),
            'create' => FactoryAllocationResource\Pages\CreateFactoryAllocation::route('/create'),
            'edit' => FactoryAllocationResource\Pages\EditFactoryAllocation::route('/{record}/edit'),];
    }
}
